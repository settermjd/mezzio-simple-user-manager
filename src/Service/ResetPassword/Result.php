<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ResetPassword;

class Result
{
    /**
     * General Failure
     */
    public const int FAILURE = 0;

    /**
     * Failure due to identity not being found.
     */
    public const int FAILURE_IDENTITY_NOT_FOUND = -1;

    /**
     * Failure due to identity being ambiguous.
     */
    public const int FAILURE_IDENTITY_AMBIGUOUS = -2;

    /**
     * Failure due to uncategorized reasons.
     */
    public const int FAILURE_UNCATEGORIZED = -4;

    /**
     * Password reset successfully.
     */
    public const int SUCCESS = 1;

    /**
     * Authentication result code
     */
    protected int $code;

    /**
     * An array of string reasons why the authentication attempt was unsuccessful
     * If authentication was successful, this should be an empty array.
     *
     * @var array<int,string> $messages
     */
    protected array $messages;

    /**
     * Sets the result code, identity, and failure messages
     *
     * @param array<int,string> $messages
     */
    public function __construct(int $code, array $messages = [])
    {
        $this->code     = $code;
        $this->messages = $messages;
    }

    /**
     * Returns whether the result represents a successful reset password attempt
     */
    public function isValid(): bool
    {
        return $this->code > 0;
    }

    /**
     * Get the result code for this authentication attempt
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Returns an array of string reasons why the authentication attempt was unsuccessful
     *
     * If authentication was successful, this method returns an empty array.
     *
     * @return array<int,string> $messages
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
