<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Container\Exception;

use Exception;
use Interop\Container\Exception\NotFoundException as InteropNotFoundException;

class ServiceNotFoundException extends Exception implements InteropNotFoundException
{

}