<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

use ArrayIterator;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SimpleUserManager\Exception\InvalidConfigException;
use SimpleUserManager\Policy\NullRedirectPolicy;
use SimpleUserManager\Policy\RoutePolicyFilterIterator;

use function iterator_to_array;

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
 * Then, with that, it will check if the current route, using a
 * RouteCollector object, has the path '/forgot-password'. If so, it will
 * instantiate and return a RoutePolicyMiddlware object with a
 * RedirectFromForgotPasswordPolicy object as a constructor parameter, if the
 * matching policy object is registered as a service. If not, it will return a
 * NullRedirectPolicy object, which does not redirect anywhere.
 */
readonly class RoutePolicyMiddlewareFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws InvalidConfigException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RoutePolicyMiddleware
    {
        if (! $container->has('config')) {
            throw new InvalidConfigException(
                "Missing 'route_policy' key in the application configuration."
            );
        }
        $config = $container->get('config')['route_policy'] ?? [];

        $path           = ServerRequestFactory::fromGlobals()->getUri()->getPath();
        $filteredConfig = iterator_to_array(
            new RoutePolicyFilterIterator(new ArrayIterator($config), $path)
        );
        $policy         = $filteredConfig === []
            ? $container->get(NullRedirectPolicy::class)
            : $container->get($filteredConfig[$path]);

        return new RoutePolicyMiddleware($policy);
    }
}
