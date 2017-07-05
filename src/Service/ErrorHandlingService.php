<?php

namespace o0psCore\Service;

/**
 * Class ErrorHandlingService
 * @package o0psCore\Service
 */
class ErrorHandlingService
{
    /**
     * @var
     */
    protected $logger;

    /**
     * ErrorHandlingService constructor.
     *
     * @param $logger
     */
    function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Exception $e
     */
    function logException($e)
    {
        $trace = $e->getTraceAsString();
        $i     = 0;
        do {
            $messages[] = "#" . $i++ . " " . $e->getMessage();
        } while ($e = $e->getPrevious());

        $log = "\nException: " . implode("\n", $messages);
        $log .= "\nTrace: " . $trace;

        $this->logger->err($log);
    }
}