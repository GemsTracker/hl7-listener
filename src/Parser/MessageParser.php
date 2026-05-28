<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Parser
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Parser;

use Gems\Hl7\Exception\StructureException;
use Gems\Hl7\Node\Component;
use Gems\Hl7\Node\Field;
use Gems\Hl7\Node\Message;
use Gems\Hl7\Node\Repetition;
use Gems\Hl7\Node\Segment;
use Gems\Hl7\Node\Segment\MSHSegment;
use Gems\Hl7\Node\SubComponent;
use Zalt\Loader\ProjectOverloader;

/**
 * @package    Gems
 * @subpackage Hl7\Parser
 * @since      Class available since version 1.0
 */
class MessageParser
{
    /**
     * A installation specific segment loading class map
     *
     * @var array<string, string> Segment name => Segment class name
     */
    protected readonly array $segmentClassMap;

    public function __construct(
        protected readonly ProjectOverloader $projectOverloader,
    )
    {
        $this->segmentClassMap = [
            'AIL' => $projectOverloader->find('HL7\\Node\\Segment\\AILSegment'),
            'EVN' => $projectOverloader->find('HL7\\Node\\Segment\\EVNSegment'),
            'MSA' => $projectOverloader->find('HL7\\Node\\Segment\\MSASegment'),
            'MRG' => $projectOverloader->find('HL7\\Node\\Segment\\MRGSegment'),
            'MSH' => $projectOverloader->find('HL7\\Node\\Segment\\MSHSegment'),
            'NTE' => $projectOverloader->find('HL7\\Node\\Segment\\NTESegment'),
            'PID' => $projectOverloader->find('HL7\\Node\\Segment\\PIDSegment'),
            'PV1' => $projectOverloader->find('HL7\\Node\\Segment\\PV1Segment'),
            'SCH' => $projectOverloader->find('HL7\\Node\\Segment\\SCHSegment'),
            'ZDB' => $projectOverloader->find('HL7\\Node\\Segment\\ZDBSegment'),
        ];
    }

    protected function getEscapeSequences(string $hl7String): array
    {
        $escapeSequence = substr($hl7String, 3, 5);
        $escapeSequence = str_split($escapeSequence);

        $escapeSequences['field_delimiter'] = $escapeSequence[0];
        $escapeSequences['repeat_delimiter'] = $escapeSequence[2];
        $escapeSequences['component_delimiter'] = $escapeSequence[1];
        $escapeSequences['subcomponent_delimiter'] = $escapeSequence[4];
        $escapeSequences['cursor_return'] = chr(13);

        return $escapeSequences;
    }

    /**
     *
     * @param string $hl7String
     * @param boolean $encodingCheck  To prevent endless loops, use this switch
     * @return Message
     */
    protected function loadMessageFromString(string $hl7String, $encodingCheck = true): Message
    {
        $this->validate($hl7String);
        $escapeSequences = $this->getEscapeSequences($hl7String);

        /**
         * @var Message $message
         */
        $message = $this->projectOverloader->create('HL7\\Node\\Message');
        $message->setEscapeSequences($escapeSequences);

        $this->splitSegments($hl7String, $message);

        /**
         * To 'autosense' encoding, we need to read the msh segment first, when the
         * found encoding does not match the internal encoding, we need to convert
         * the string first, and then generate the message. since this could be a
         * waste of CPU cycles, it is advised to fix the encoding when possible.
         */
        if ($encodingCheck && $encoding = $message->getMshSegment()?->getCharacterset()) {
            $internalEncoding = mb_internal_encoding();
            if ($encoding !== $internalEncoding) {
                // Do something here to make sure the encoding is correct
                // echo mb_check_encoding($hl7String, 'WINDOWS-1252');
                // echo mb_check_encoding($hl7String, 'UTF-8');
                // echo mb_convert_encoding($hl7String, 'UTF-8', 'WINDOWS-1252');
                $message = mb_convert_encoding($hl7String, $internalEncoding, $encoding);

                // Use second parameter to prevent endless loops
                $message = $this->loadMessageFromString($message, false);
            }
        }
        return $message;
    }
    public function parseMessage(string $hl7String, bool $checkEncoding = true): ?Message
    {
        $hl7String = trim($hl7String, " \n\r\t\v\x00". chr(28)) . chr(28) . chr(13);

        return $this->loadMessageFromString($hl7String, $checkEncoding);
    }

    protected function splitSegments(string $hl7String, Message $message)
    {
        $escapeSequences = $message->getEscapeSequences();
        $segmentStrings = explode($escapeSequences['cursor_return'], $hl7String);
        foreach($segmentStrings as $segmentString) {
            /**
             * Last line
             */
            if($segmentString === '')
                break;

            $segment = $this->splitFields($segmentString, $escapeSequences);
            $message->append($segment);
        }
    }

    protected function splitFields(string $segmentString, array $escapeSequences): Segment
    {
        $segmentName = substr($segmentString, 0, 3);
        if(!array_key_exists($segmentName, $this->segmentClassMap)) {
            $segment = new Segment($segmentName);
        } else {
            $className = $this->segmentClassMap[$segmentName];
            $segment = new $className($segmentName);
        }
        $fieldStrings = explode($escapeSequences['field_delimiter'], substr($segmentString, 4));
        foreach($fieldStrings as $fieldString) {
            $field = $this->splitRepetitions($fieldString, $escapeSequences);
            $segment->append($field);
        }
        return $segment;
    }

    protected function splitRepetitions(string $fieldString, array $escapeSequences): Field
    {
        $field = new Field();
        $repetitionStrings = explode($escapeSequences['repeat_delimiter'], $fieldString);
        foreach($repetitionStrings as $repetitionString) {
            $repetition = $this->splitComponents($repetitionString, $escapeSequences);
            $field->append($repetition);
        }
        return $field;
    }

    protected function splitComponents(string $repetitionString, array $escapeSequences): Repetition
    {
        $componentStrings = explode($escapeSequences['component_delimiter'], $repetitionString);
        if(count($componentStrings) === 1) {
            /**
             * Check for subcomponents in single component
             */
            $component = $this->splitSubComponents($componentStrings[0], $escapeSequences);
            if($component->count() === 0) {
                $repetition = new Repetition($componentStrings[0]);
                return $repetition;
            }
        }

        $repetition = new Repetition();
        foreach($componentStrings as $componentString) {
            $component = $this->splitSubComponents($componentString, $escapeSequences);
            $repetition->append($component);
        }
        return $repetition;
    }

    protected function splitSubComponents(string $componentString, array $escapeSequences): Component
    {
        $subcomponentStrings = explode($escapeSequences['subcomponent_delimiter'], $componentString);
        if(count($subcomponentStrings) === 1) {
            $component = new Component($subcomponentStrings[0]);
            return $component;
        }
        $component = new Component();
        foreach($subcomponentStrings as $subcomponentString) {
            $subcomponent = new SubComponent($subcomponentString);
            $component->append($subcomponent);
        }
        return $component;
    }

    protected function validate(string $hl7String): void
    {
        if(strlen($hl7String) == 0)
            throw new StructureException('HL7 string is empty');

        /**
         * Check for MSH starter
         */
        $header = substr($hl7String, 0, 3);
        if($header !== MSHSegment::IDENTIFIER) {
            throw new StructureException('HL7 starts with "'.$header.'". Expected "MSH"');
        }
    }
}