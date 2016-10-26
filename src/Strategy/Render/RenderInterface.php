<?php

namespace o0psCore\Strategy\Render;

use Zend\EventManager\EventInterface;

interface RenderInterface
{
    public function onRender(EventInterface $e);
}