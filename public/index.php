<?php

use Lanser\MyFreamwork\Core\AppBuilder;
use Lanser\MyFreamwork\Core\Container\Exception\ServiceNotFoundException;
use Lanser\MyFreamwork\Core\Controller\AbstractController;

require '../vendor/autoload.php';


try {
    echo (new AppBuilder())->build('../src/App', AbstractController::class);
} catch (ServiceNotFoundException $e) {
    // Method not found, show the 404 page
    http_response_code(404);
    require __DIR__ . '/Views/404.php';
} catch (ReflectionException $e) {

}
