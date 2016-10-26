<?php

namespace o0psCore\Strategy\Finish;

use Zend\EventManager\EventInterface;

interface FinishInterface
{
    public function onFinish(EventInterface $e);
}