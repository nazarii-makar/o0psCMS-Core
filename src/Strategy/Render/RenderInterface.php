<?php

namespace o0psCore\Strategy\Render;

use Zend\EventManager\EventInterface;

/**
 * Interface RenderInterface
 * @package o0psCore\Strategy\Render
 */
interface RenderInterface
{
    /**
     * @param EventInterface $e
     *
     * @return mixed
     */
    public function onRender(EventInterface $e);
}