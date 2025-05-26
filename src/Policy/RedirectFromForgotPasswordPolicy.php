<?php

declare(strict_types=1);

namespace SimpleUserManager\Policy;

use Laminas\Authentication\AuthenticationServiceInterface;

/**
 * This class handles redirections from the application's ForgotPassword route.
 *
 * A redirect is required if there is already a logged-in user when a request
 * to the forgot password route is made.
 */
readonly class RedirectFromForgotPasswordPolicy implements RoutePolicyInterface
{
    public function __construct(
        private AuthenticationServiceInterface $authService,
        private string $redirectToUri
    ) {
    }

    /**
     * @inheritDoc
     *
     * If a request is made to the forgot password route and a user identity is
     * available, then a redirect to a different URI must happen.
     */
    public function mustRedirect(): bool
    {
        return $this->authService->hasIdentity() && $this->redirectToUri !== '';
    }

    /**
     * @inheritDoc
     */
    public function getRedirectURI(): string
    {
        return $this->redirectToUri;
    }
}
