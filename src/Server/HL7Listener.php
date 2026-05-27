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
// use PharmaIntelligence\MLLP\MLLPParser;
use Gems\Config\ConfigAccessor;
use Gems\Factory\LaminasDbAdapterFactory;
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

    protected readonly bool $verbose;

    public function __construct(
        protected readonly string $listener,
        protected readonly array $config,
        protected readonly AdapterInterface $db,
    ) {
        $this->listenerConfig = $this->config['hl7listener'][$this->listener] ?? [];

        $port       = $this->listenerConfig['port'];
        $ipAddress  = $this->listenerConfig['ipAddress'] ?? '127.0.0.1';
        $dbPingTime = $this->listenerConfig['dbPingTime'] ?? 6;
        $this->verbose = $this->listenerConfig['verbose'] ?? ($this->config['hl7listener']['verbase'] ?? false);

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

//        $this->on('data', [$this, 'onReceiving']);
//        $this->on('error', [$this, 'onError']);
    }

    /**
     * @return void Check the db abd reestablish the connection if broken'
     */
    public function checkDb()
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

    public function handleRequest(ConnectionInterface $connection) {
        $this->emit('connection', array($connection));
        $connection->on('data', function($data) use ($connection) {
            try {
                // $data = MLLPParser::unwrap($data);
                $this->emit('data', array($data, $connection));
            } catch(\InvalidArgumentException $e) {
                $this->handleInvalidMLLPEnvelope($data, $connection);
                $this->emit('error', array('Invalid MLLP envelope. Received: "'.$data.'"'));
            }
        });
    }

    public function initLogging()
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
            $self->log(PHP_EOL . 'Sending to ' . $connection->getRemoteAddress() . ' at ' . date('c') . ' bytes ' . strlen($data) . ' data: ' . PHP_EOL .
                str_replace(chr(13), PHP_EOL, $data) . ' ');
        });

        // Log received data
        $this->on('data', function($data, ConnectionInterface $connection) use ($self) {
            $self->log('Received from ' . $connection->getRemoteAddress() . ' at ' . date('c') . ' bytes ' . strlen($data) . ' data:' . PHP_EOL .
                str_replace(chr(13), PHP_EOL, $data));
        });
    }

    public function log(string $message)
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
    public function onError($errorMessage, ConnectionInterface $connection)
    {
        echo PHP_EOL . PHP_EOL . sprintf(
                'Error from {{{ ' . $connection->getRemoteAddress() . ' at %s: %s }}}' . PHP_EOL,
                date('c'),
                $errorMessage);
    }

    /**
     * The action when a message is saved
     *
     * @param mixed $data
     * @param ConnectionInterface $connection
     */
    public function onReceiving($data, ConnectionInterface $connection)
    {
        // Do something here to make sure the encoding is correct
        // echo mb_check_encoding($data, 'WINDOWS-1252');
        // echo mb_check_encoding($data, 'UTF-8');
        // echo mb_convert_encoding($data, 'UTF-8', 'WINDOWS-1252');
//        $message = $this->messageLoader->loadMessage($data);
//
//        if (! $message) {
//            echo "Invalid message send.\n";
//        }
//
//        $saveMessage = $this->isMessageSaveable($message);
//        // $saveMessage = false;
//        // echo "Save msg: $saveMessage\n";
//
//        if ($saveMessage)  {
//            $encoding = $message->getMessageHeaderSegment()->getCharacterset();
//            $internal = mb_internal_encoding();
//            if ($internal != $encoding) {
//                $messageId = $this->saveToDb(mb_convert_encoding($data, $internal, $encoding), $message);
//            } else {
//                $messageId = $this->saveToDb($data, $message);
//            }
//
//            // echo "Msg id: $messageId\n";
//        }
//
//        $this->sendAcknowledgement($message, $connection);
//
//        // Do not end the connection as it blocks later messages
//        // $connection->end();
//
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
        unset($ack);
        unset($message);
    }

    /**
     *
     * @param string $data Raw data
     * @param Message $message
     * @return int Message id from database
     */
    public function saveToDb($data) // , Message $message)
    {
//        $msh = $message->getMshSegment();
//
//        if ($msh) {
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
//        }
//
//        return false;
    }

    public function send($data, ConnectionInterface $connection) {
        $this->emit('send', array($data));

        $connection->on('error', function(ConnectionInterface $connection, $error) {
            $this->emit('error', array('Error sending data: '.$error));
        });

        // $data = MLLPParser::enclose($data);
        $connection->write($data);
        $connection->removeAllListeners('error');

    }

    protected function handleInvalidMLLPEnvelope($data, ConnectionInterface $connection) {
        $connection->end('INVALID ENVELOPE');
    }
}