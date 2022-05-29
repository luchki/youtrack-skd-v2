<?php

namespace Luchki\YoutrackSDK\Contracts;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface IRouteHandler
{
        public function __construct(Request $request, Response $response, array $args);

        public function getResponse(): Response;
}