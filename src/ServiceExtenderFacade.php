<?php

namespace KhantNyar\ServiceExtender;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KhantNyar\ServiceExtender\Skeleton\SkeletonClass
 */
class ServiceExtenderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'service-extender';
    }
}
