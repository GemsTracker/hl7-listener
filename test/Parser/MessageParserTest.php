<?php

declare(strict_types=1);

namespace Gems\Hl7Test;

use Gems\Hl7\Node\Message;
use Gems\Hl7\Node\Segment\MSHSegment;
use Gems\Hl7\Parser\MessageParser;
use Zalt\Base\TranslatorInterface;
use Zalt\Loader\ProjectOverloader;
use Zalt\Loader\ProjectOverloaderFactory;
use Zalt\Mock\MockTranslator;
use Zalt\Mock\SimpleServiceManager;

class MessageParserTest extends \PHPUnit\Framework\TestCase
{
    public array $serverManagerConfig = [
        'overLoader' => [
            'AddTo' => true,
            'Paths' => ['Zend', 'Symfony', 'Mezzio', 'Laminas', 'Zalt', 'Gems'],
            ],
    ];

    public function getLoader(): ProjectOverloader
    {
        static $loader;

        if ($loader instanceof ProjectOverloader) {
            return $loader;
        }

        $sm = $this->getServiceManager();
        $overFc = new ProjectOverloaderFactory();
        $loader = $overFc($sm);
        $sm->set(ProjectOverloader::class, $loader);

        return $loader;
    }

    public function getServiceManager(): SimpleServiceManager
    {
        static $sm;

        if (! $sm instanceof SimpleServiceManager) {
            $sm = new SimpleServiceManager(['config' => $this->serverManagerConfig]);

            $sm->set(TranslatorInterface::class, new MockTranslator());
        }

        return $sm;
    }

    public function getMessage(string $fileName): string
    {
        return file_get_contents(dirname(__DIR__) . '/resources/' . $fileName);
    }

    public function getParser(): MessageParser
    {
        return new MessageParser($this->getLoader());
    }

    public function testMessage(): void
    {
        $parser  = $this->getParser();
        $input   = $this->getMessage('ADT^A08^ADT_A01-2.txt');

        $message = $parser->parseMessage($input);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertInstanceOf(MSHSegment::class, $message->getMshSegment());
    }
}