<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Container\Exception;

use Exception;
use Interop\Container\Exception\ContainerException as InteropContainerException;

class ContainerException extends Exception implements InteropContainerException
{

}