<?php

declare(strict_types=1);

namespace SimpleUserManager\Policy;

readonly class NullRedirectPolicy implements RoutePolicyInterface
{
    /**
     * @inheritDoc
     */
    public function mustRedirect(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectURI(): string
    {
        return '';
    }
}
