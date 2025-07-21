<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Policy;

use ArrayIterator;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Policy\NullRedirectPolicy;
use SimpleUserManager\Policy\RedirectFromForgotPasswordPolicy;
use SimpleUserManager\Policy\RoutePolicyFilterIterator;

use function iterator_count;
use function iterator_to_array;

class RoutePolicyFilterIteratorTest extends TestCase
{
    /**
     * @param array<string,class-string> $routePolicyConfig
     * @param array<string,class-string> $filteredConfig
     */
    #[TestWith([
        [
            '/forgot-password' => RedirectFromForgotPasswordPolicy::class,
            '/user-profile'    => NullRedirectPolicy::class,
            '/login'           => NullRedirectPolicy::class,
            '/logout'          => NullRedirectPolicy::class,
            '/register'        => NullRedirectPolicy::class,
        ],
        [
            '/forgot-password' => RedirectFromForgotPasswordPolicy::class,
        ],
        '/forgot-password',
    ])]
    public function testCanFilterOutNonMatchingRoutes(
        array $routePolicyConfig,
        array $filteredConfig,
        string $currentRoute
    ): void {
        $filter = new RoutePolicyFilterIterator(new ArrayIterator($routePolicyConfig), $currentRoute);

        $this->assertEquals(1, iterator_count($filter));
        $this->assertSame($filteredConfig, iterator_to_array($filter));
    }
}
