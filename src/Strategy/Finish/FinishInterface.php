<?php

namespace o0psCore\Strategy\Finish;

use Zend\EventManager\EventInterface;

/**
 * Interface FinishInterface
 * @package o0psCore\Strategy\Finish
 */
interface FinishInterface
{
    /**
     * @param EventInterface $e
     *
     * @return mixed
     */
    public function onFinish(EventInterface $e);
}