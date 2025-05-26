<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SimpleUserManager\Policy\RoutePolicyInterface;

readonly class RoutePolicyMiddleware implements MiddlewareInterface
{
    public function __construct(private RoutePolicyInterface $routePolicy)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->routePolicy->mustRedirect()) {
            return new RedirectResponse($this->routePolicy->getRedirectURI());
        }

        return $handler->handle($request);
    }
}
