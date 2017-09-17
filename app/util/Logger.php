<?php
/**
 * Logger.php
 */

namespace SoftnCMS\util;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonoLog;
use Psr\Log\LoggerInterface;

/**
 * Class Logger
 * @author Nicolás Marulanda P.
 */
class Logger implements LoggerInterface {
    
    const DEFAULT_LOGGING_CHANNEL = 'GENERAL';
    
    const DIRECTORY_LOG           = ABSPATH . 'log' . DIRECTORY_SEPARATOR;
    
    /** @var Logger */
    private static $INSTANCE = NULL;
    
    private        $backTrace;
    
    /** @var MonoLog */
    private $logger;
    
    /**
     * Logger constructor.
     */
    private function __construct() {
        $this->backTrace = '';
        $date            = getdate();
        $mon             = Arrays::get($date, 'mon');
        $year            = Arrays::get($date, 'year');
        $mDay            = Arrays::get($date, 'mday');
        $this->logger    = new MonoLog(self::DEFAULT_LOGGING_CHANNEL);
        $this->logger->pushHandler(new StreamHandler(sprintf('%1$s%2$s%3$s%4$s%3$s%5$s.log', self::DIRECTORY_LOG, $year, DIRECTORY_SEPARATOR, $mon, $mDay)));
    }
    
    /**
     * @return Logger
     */
    public static function getInstance() {
        if (self::$INSTANCE == NULL) {
            self::$INSTANCE = new Logger();
        }
        
        self::$INSTANCE->backTrace = self::$INSTANCE->debugBackTrace(debug_backtrace());
        
        return self::$INSTANCE;
    }
    
    private function debugBackTrace($backTrace) {
        $auxBackTrace = Arrays::get($backTrace, 1);
        
        if (Arrays::get($auxBackTrace, 'function') == '{closure}') {
            $auxBackTrace = Arrays::get($backTrace, 2);
        }
        
        $function = Arrays::get($auxBackTrace, 'function');
        $class    = Arrays::get($auxBackTrace, 'class');
        
        return sprintf('[%1$s] [%2$s] ', $class, $function);
    }
    
    public function withName($name = self::DEFAULT_LOGGING_CHANNEL) {
        $new         = clone $this;
        $new->logger = $this->logger->withName($name);
        
        return $new;
    }
    
    public function emergency($message, array $context = []) {
        if ($this->canWriteLog()) {
            $this->logger->emergency($this->addBackTrace($message), $context);
        }
    }
    
    private function canWriteLog() {
        return (defined('LOGGER') && LOGGER);
    }
    
    private function addBackTrace($message) {
        return $this->backTrace . $message;
    }
    
    public function alert($message, array $context = []) {
        if ($this->canWriteLog()) {
            $this->logger->emergency($this->addBackTrace($message), $context);
        }
    }
    
    public function critical($message, array $context = []) {
        if ($this->canWriteLog()) {
            $this->logger->critical($this->addBackTrace($message), $context);
        }
    }
    
    public function error($message, array $context = []) {
        if ($this->canWriteLog()) {
            $this->logger->error($this->addBackTrace($message), $context);
        }
    }
    
    public function warning($message, array $context = []) {
        if ($this->canWriteLog()) {
            $this->logger->warning($this->addBackTrace($message), $context);
        }
    }
    
    public function notice($message, array $context = []) {
        if ($this->canWriteLog()) {
            $this->logger->notice($this->addBackTrace($message), $context);
        }
    }
    
    public function info($message, array $context = []) {
        if ($this->canWriteLog()) {
            $this->logger->info($this->addBackTrace($message), $context);
        }
    }
    
    public function debug($message, array $context = []) {
        if (($this->canWriteLog() && defined('FULL_LOGGER') && FULL_LOGGER) || $this->logger->getName() == 'INSTALL') {
            $this->logger->debug($this->addBackTrace($message), $context);
        }
    }
    
    public function log($level, $message, array $context = []) {
        if ($this->canWriteLog()) {
            $this->logger->log($level, $this->addBackTrace($message), $context);
        }
    }
    
}
