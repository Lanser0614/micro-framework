<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Controller;

abstract class AbstractController
{
    protected function jsonResponse($data, int $statusCode = 200): bool|string
    {
        // Set the content type header
        header('Content-Type: application/json', true, $statusCode);

        // Output the JSON-encoded data
        return json_encode($data);
    }
}