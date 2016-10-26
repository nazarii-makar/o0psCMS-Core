<?php

namespace o0psCore\Strategy\RenderError;

use Zend\EventManager\EventInterface;

interface RenderErrorInterface
{
    public function onRenderError(EventInterface $e);
}