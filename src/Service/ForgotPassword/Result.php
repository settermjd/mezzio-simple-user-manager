<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ForgotPassword;

final class Result
{
    /**
     * General Failure
     */
    public const int FAILURE = 0;

    /**
     * Failure due to record already existing for the identity provided.
     */
    public const int FAILURE_RECORD_EXISTS_FOR_PROVIDED_IDENTITY = -1;

    /**
     * Failure due to uncategorized reasons.
     */
    public const int FAILURE_UNCATEGORIZED = -4;

    /**
     * Authentication success.
     */
    public const int SUCCESS = 1;

    /**
     * Authentication result code
     */
    protected int $code;

    /**
     * An array of string reasons why the authentication attempt was unsuccessful
     *
     * If authentication was successful, this should be an empty array.
     */
    protected array $messages;

    /**
     * Sets the result code, identity, and failure messages
     */
    public function __construct(int $code, array $messages = [])
    {
        $this->code     = (int) $code;
        $this->messages = $messages;
    }

    /**
     * Returns whether the result represents a successful authentication attempt
     *
     */
    public function isValid(): bool
    {
        return $this->code > 0;
    }

    /**
     * getCode() - Get the result code for this authentication attempt
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Returns an array of string reasons why the authentication attempt was unsuccessful
     *
     * If authentication was successful, this method returns an empty array.
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
