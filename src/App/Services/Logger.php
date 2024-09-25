<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\App\Services;

class Logger implements LoggerInterface
{

    public function log(): string
    {
       return 'ok';
    }
}