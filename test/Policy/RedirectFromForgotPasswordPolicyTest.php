<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Policy;

use Laminas\Authentication\AuthenticationServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Policy\RedirectFromForgotPasswordPolicy;

class RedirectFromForgotPasswordPolicyTest extends TestCase
{
    #[DataProvider('invalidRedirectUriDataProvider')]
    public function testMustRedirectIfAuthIdentityIsAvailableAndRedirectUriIsSet(
        bool $hasAuthIdentity,
        string $redirectUri,
        bool $mustRedirect,
    ): void {
        $policy = new RedirectFromForgotPasswordPolicy(
            $this->createConfiguredMock(AuthenticationServiceInterface::class, [
                'hasIdentity' => $hasAuthIdentity,
            ]),
            $redirectUri
        );
        $mustRedirect
            ? $this->assertTrue($policy->mustRedirect())
            : $this->assertFalse($policy->mustRedirect());
    }

    /**
     * @return array<string,array<int,bool|string>>
     */
    public static function invalidRedirectUriDataProvider(): array
    {
        return [
            'Has auth identity but no redirect URI'    => [
                true,
                '',
                false,
            ],
            'Has no auth identity but a redirect URI'  => [
                false,
                '/user-profile',
                false,
            ],
            'Has no auth identity and no redirect URI' => [
                false,
                '',
                false,
            ],
        ];
    }
}
