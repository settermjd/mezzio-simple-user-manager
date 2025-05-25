<?php

declare(strict_types=1);

namespace SimpleUserManager\Policy;

interface RoutePolicyInterface
{
    /**
     * This function determines if a redirect must happen.
     */
    public function mustRedirect(): bool;

    /**
     * This function provides the URI to redirect to, if a redirect must happen.
     */
    public function getRedirectURI(): string;
}
