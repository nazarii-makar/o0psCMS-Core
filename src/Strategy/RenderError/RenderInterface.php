<?php

namespace o0psCore\Strategy\RenderError;

use Zend\EventManager\EventInterface;

/**
 * Interface RenderErrorInterface
 * @package o0psCore\Strategy\RenderError
 */
interface RenderErrorInterface
{
    /**
     * @param EventInterface $e
     *
     * @return mixed
     */
    public function onRenderError(EventInterface $e);
}