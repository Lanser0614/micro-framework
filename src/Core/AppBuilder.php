<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core;

use Doctrine\ORM\EntityManager;
use Lanser\MyFreamwork\App\Provider\AppServiceProvider;
use Lanser\MyFreamwork\Core\Attributes\Route;
use Lanser\MyFreamwork\Core\Container\Container;
use Lanser\MyFreamwork\Core\Container\Exception\ServiceNotFoundException;
use Lanser\MyFreamwork\Core\Request\Request;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use RegexIterator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class AppBuilder
{
    private array $controllers = [];
    private ?RequestObject $controller = null;

    /**
     * @throws ReflectionException
     * @throws \Exception
     */
    public function build(
        string $directory,
        string $abstractControllerClass,
    )
    {
        $provider = new AppServiceProvider();
        $provider->register();
        $this->handleControllers($directory, $abstractControllerClass);
        $controllerObject = $this->handleRequest();
        $controller = $provider->make($controllerObject->reflection->name);

        return $controller->{$controllerObject->method}();
    }


    /**
     * @param string $directory
     * @param string $abstractControllerClass
     * @return $this
     * @throws ReflectionException
     */
    public function handleControllers(string $directory, string $abstractControllerClass): static
    {
        $childControllers = [];

        // Recursively get all PHP files in the directory
        $phpFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $phpFiles = new RegexIterator($phpFiles, '/\.php$/');

        // Include each PHP file
        foreach ($phpFiles as $file) {
            require_once $file->getPathname();
        }

        // Get all declared classes
        $allClasses = get_declared_classes();

        // Filter classes to find those that extend the AbstractController
        foreach ($allClasses as $className) {
            $reflection = new ReflectionClass($className);
            // Check if the class is a subclass of AbstractController and is instantiable
            if ($reflection->isSubclassOf($abstractControllerClass) && !$reflection->isAbstract()) {
//                dd($reflection->getMethods());
//                $attributes = $reflection->getAttributes();
                $this->controllers[] = $className;
            }
        }

        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public function handleRequest()
    {
        $routeAttributes = [];
        foreach ($this->controllers as $controller) {
            $controller = new ReflectionClass($controller);

            // Iterate over each method in the controller
            foreach ($controller->getMethods() as $method) {
                // Check if the method has attributes
                $attributes = $method->getAttributes(Route::class);

                if ($attributes) {
                    $routeAttributes[$this->getHashRoute($attributes[0])] = [
                        'controller' => $controller->getName(),
                        'method' => $method->getName(),
                    ];
                }

            }
        }

        if (empty($routeAttributes)) {
            throw new ServiceNotFoundException();
        }

        if (empty($routeAttributes[Request::getHashRequest()])) {
            throw new ServiceNotFoundException();
        }

        $routeAttributes[Request::getHashRequest()];
        return new RequestObject(new ReflectionClass($routeAttributes[Request::getHashRequest()]['controller']), $routeAttributes[Request::getHashRequest()]['method']);
    }

    /**
     * @param \ReflectionAttribute $attribute
     * @return string
     */
    private function getUrlPath(\ReflectionAttribute $attribute): string
    {
        return trim(parse_url($attribute->getArguments()['route'], PHP_URL_PATH), '/');
    }


    private function getAttributeHttpMethod(\ReflectionAttribute $attribute)
    {
        return $attribute->getArguments()['method'];
    }

    private function getHashRoute(\ReflectionAttribute $attribute): string
    {
        return $this->getUrlPath($attribute) . '|' . $this->getAttributeHttpMethod($attribute);
    }
}


