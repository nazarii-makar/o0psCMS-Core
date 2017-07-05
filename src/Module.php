<?php

namespace o0psCore;

/**
 * Class Module
 * @package o0psCore
 */
class Module
{
    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
