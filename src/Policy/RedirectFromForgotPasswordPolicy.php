<?php

declare(strict_types=1);

namespace SimpleUserManager\Policy;

use Laminas\Authentication\AuthenticationServiceInterface;

readonly class RedirectFromForgotPasswordPolicy implements RoutePolicyInterface
{
    public function __construct(
        private AuthenticationServiceInterface $authService,
        private string $redirectToURI
    ) {}

    /**
     * @inheritDoc
     * 
     * If a request is made to the forgot password route and a user identity is
     * available, then a redirect to a different URI must happen.
     */
    public function mustRedirect(): bool
    {
        return $this->authService->hasIdentity();
    }

    /**
     * @inheritDoc
     */
    public function getRedirectURI(): string
    {
        return $this->redirectToURI;
    }
}
