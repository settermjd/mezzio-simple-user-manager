<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

/**
 * This class instantiates a RoutePolicyMiddleware object using a configuration
 * with the following structure:
 * 
 * [
 *   'route_policy' => [
 *     '/forgot-password' => RedirectFromForgotPasswordPolicy::class,
 *   ]
 * ]
 * 
 * Then, with that, it will check if the the current route, using a
 * RouteCollector object, has the path '/forgot-password'. If so, it will
 * instantiate and return a RoutePolicyMiddlware object with a
 * RedirectFromForgotPasswordPolicy object as a constructor parameter, if the
 * matching policy object is registered as a service. If not, it will return a
 * NullRedirectPolicy object, which does not redirect anywhere. 
 */
readonly class RoutePolicyMiddlewareFactory
{
    
}