<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\App\Provider;

use Lanser\MyFreamwork\App\Services\Logger;
use Lanser\MyFreamwork\App\Services\LoggerInterface;
use Lanser\MyFreamwork\Core\Container\Container;

class AppServiceProvider extends Container
{
    public function register(): void
    {
        $this->bind(LoggerInterface::class, Logger::class);
    }
}