<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Server
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Server;

use Evenement\EventEmitter;
use Evenement\EventEmitterInterface;
use Gems\Hl7\Node\Message;
use Gems\Hl7\Node\Message\ACKMessage;
use Gems\Hl7\Node\Segment\MSHSegment;
use Gems\Hl7\Parser\MessageParser;
use Gems\Hl7\Parser\MLLPParser;
use Laminas\Db\Adapter\AdapterInterface;
use React\EventLoop\Loop;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;

/**
 * @package    Gems
 * @subpackage Hl7\Server
 * @since      Class available since version 1.0
 */
class HL7Listener extends EventEmitter implements EventEmitterInterface
{
    protected readonly array $listenerConfig;
    protected readonly SocketServer $socketServer;

    protected bool $verbose;

    public function __construct(
        protected readonly string $listener,
        protected readonly array $config,
        protected readonly AdapterInterface $db,
        protected readonly MessageParser $messageParser,
    ) {
        $this->listenerConfig = $this->config['hl7listener'][$this->listener] ?? [];

        $port       = $this->listenerConfig['port'];
        $ipAddress  = $this->listenerConfig['ipAddress'] ?? '127.0.0.1';
        $dbPingTime = $this->listenerConfig['dbPingTime'] ?? 6;
        $this->verbose = (bool) ($this->listenerConfig['verbose'] ?? ($this->config['hl7listener']['verbose'] ?? false));

        $this->socketServer = new SocketServer("tcp://$ipAddress:$port");
        $this->socketServer->on('connection', function(ConnectionInterface $connection) {
            $this->handleRequest($connection);
        });

        if ($dbPingTime) {
            Loop::addPeriodicTimer($dbPingTime, [$this, 'checkDb']);
        }

        $this->initLogging();
        $this->log(sprintf(
            "Starting server at %s on %s:%s",
            date('c'),
            $ipAddress,
            $port
        ));

        $this->on('data', [$this, 'onReceiving']);
        $this->on('error', [$this, 'onError']);
    }

    /**
     * @return void Check the db abd reestablish the connection if broken'
     */
    public function checkDb(): void
    {
        if (isset($this->listenerConfig['checkAliveLog'])) {
            $msg = "Listener Timer Check at " . date('d-m-Y H:i:s');
            file_put_contents($this->listenerConfig['checkAliveLog'], $msg);

            if ($this->verbose) {
                echo $msg . PHP_EOL;
            }
        }
        $connection = $this->db->getDriver()->getConnection();
        if ($connection instanceof \Laminas\Db\Adapter\Driver\ConnectionInterface && ! $connection->isConnected()) {
            $msg = 'reconnecting to database';
            error_log($msg);
            $connection->connect();

            if ($this->verbose) {
                echo $msg . PHP_EOL;
            }
        }
    }

    public function handleRequest(ConnectionInterface $connection): void
    {
        $this->emit('connection', array($connection));
        $connection->on('data', function($data) use ($connection) {
            try {
                $data = MLLPParser::unwrap($data);
                $this->emit('data', array($data, $connection));
            } catch(\InvalidArgumentException $e) {
                $this->handleInvalidMLLPEnvelope($data, $connection);
                $this->emit('error', array('Invalid MLLP envelope. Received: "'.$data.'"'));
            }
        });
    }

    public function initLogging(): void
    {
        $self = $this;

        // Log connection info
        $this->on('connection', function(ConnectionInterface $connection) use ($self) {
            $self->log(sprintf(
                'Connection at %s from %s.',
                date('c'),
                $connection->getRemoteAddress()));
        });

        // Log error info
        $this->on('error', function($errorMessage, ConnectionInterface $connection) use ($self) {
            $self->log(sprintf(
                'Error from ' . $connection->getRemoteAddress() . ' at %s: %s   ',
                date('c'),
                $errorMessage));
        });

        // Log sent data
        $this->on('send', function($data, ConnectionInterface $connection) use ($self) {
            $self->log(PHP_EOL . 'Sending to ' . $connection->getRemoteAddress() . ' at ' . date('c') . ' bytes ' . strlen((string) $data) . ' data: ' . PHP_EOL .
                str_replace(chr(13), PHP_EOL, (string) $data) . ' ');
        });

        // Log received data
        $this->on('data', function($data, ConnectionInterface $connection) use ($self) {
            $self->log('Received from ' . $connection->getRemoteAddress() . ' at ' . date('c') . ' bytes ' . strlen($data) . ' data:' . PHP_EOL .
                str_replace(chr(13), PHP_EOL, $data));
        });
    }

    /**
     * Filter function to establish which messages to save
     *
     * @param Message $message
     * @return boolean
     */
    public function isMessageSaveable(Message $message)
    {
        return $message->getMshSegment() instanceof MSHSegment;
    }

    public function log(string $message): void
    {
        if ($this->verbose) {
            echo $message . PHP_EOL;
        }
        if (isset($this->listenerConfig['listenerLog'])) {
            file_put_contents($this->listenerConfig['listenerLog'], $message . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * The action when a message is saved
     *
     * @param mixed $errorMessage
     * @param ConnectionInterface $connection
     */
    public function onError($errorMessage, ConnectionInterface $connection): void
    {
        $verbose = $this->verbose;
        $this->verbose = true;

        $msg = sprintf(
            'Error from {{{ ' . $connection->getRemoteAddress() . ' at %s: %s }}}',
            date('c'),
            $errorMessage);

        // Make sure the error is shown somewhere
        $this->log(PHP_EOL . PHP_EOL . $msg);
        error_log($msg);
        $this->verbose = $verbose;
    }

    /**
     * The action when a message is saved
     *
     * @param mixed $data
     * @param ConnectionInterface $connection
     */
    public function onReceiving($data, ConnectionInterface $connection): void
    {
        $message = $this->messageParser->parseMessage($data);

        $saveMessage = $this->isMessageSaveable($message);
        // $saveMessage = false;
        // echo "Save msg: $saveMessage\n";

        if ($saveMessage)  {
            $messageId = $this->saveToDb($data, $message);

            // echo "Msg id: $messageId\n";
        }

        $this->sendAcknowledgement($message, $connection);


//        if ($saveMessage && $messageId) {
//            $queueIds = $this->queueManager->processMessage($messageId, $message);
//            // print_r($queueIds);
//
//            if ($this->runQueue) {
//                foreach ($queueIds as $queueId) {
//                    $this->queueManager->executeQueueItem($queueId, $message);
//                }
//            }
//        }
//
        unset($message);
    }

    /**
     *
     * @param string $data Raw data
     * @param Message $message
     * @return int Message id from database
     */
    public function saveToDb($data, Message $message)
    {
        $msh = $message->getMshSegment();

        if ($msh) {
//            if (! $this->_messageTable) {
//                $this->_initMessageTable();
//            }
//
//            $values = [
//                'hm_datetime'   => $msh->getDateTimeOfMessage()->getObject()->format('Y-m-d H:i:s'),
//                'hm_type'       => $msh->getMessageType()->__toString(),
//                'hm_msgid'      => $msh->getMessageControlId(),
//                'hm_processing' => $msh->getProcessingId(),
//                'hm_version'    => $msh->getVersionId(),
//                'hm_message'    => $data,
//            ];
//
//            // error_log(print_r($values, true));
//
//            if ($this->_messageTable->insert($values)) {
//                return $this->_messageTable->getLastInsertValue();
//            }
        }

        return false;
    }

    public function send($data, ConnectionInterface $connection): void
    {
        $this->emit('send', [$data, $connection]);

        $connection->on('error', function(ConnectionInterface $connection, $error) {
            $this->emit('error', array('Error sending data: '.$error));
        });

        $data = MLLPParser::enclose($data);
        $connection->write($data);
        $connection->removeAllListeners('error');

    }

    /**
     * Send the return acknowledgement
     *
     * @param Message $message
     * @return $this
     */
    public function sendAcknowledgement(Message $message, ConnectionInterface $connection)
    {
        $ack = new ACKMessage($message);

        $this->send((string) $ack, $connection);

        return $this;
    }

    protected function handleInvalidMLLPEnvelope($data, ConnectionInterface $connection): void
    {
        $connection->end('INVALID ENVELOPE');
    }
}