<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\RegisterUser;

class Result
{
    /**
     * General Failure
     */
    public const FAILURE = 0;

    /**
     * Failure due to identity not being found.
     */
    public const FAILURE_IDENTITY_NOT_FOUND = -1;

    /**
     * Failure due to identity being ambiguous.
     */
    public const FAILURE_IDENTITY_AMBIGUOUS = -2;

    /**
     * Failure due to uncategorized reasons.
     */
    public const FAILURE_UNCATEGORIZED = -4;

    /**
     * Authentication success.
     */
    public const SUCCESS = 1;

    /**
     * Authentication result code
     *
     * @var int
     */
    protected $code;

    /**
     * An array of string reasons why the authentication attempt was unsuccessful
     *
     * If authentication was successful, this should be an empty array.
     *
     * @var array
     */
    protected $messages;

    /**
     * Sets the result code, identity, and failure messages
     *
     * @param  int     $code
     */
    public function __construct($code, array $messages = [])
    {
        $this->code     = (int) $code;
        $this->messages = $messages;
    }

    /**
     * Returns whether the result represents a successful authentication attempt
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->code > 0;
    }

    /**
     * getCode() - Get the result code for this authentication attempt
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns an array of string reasons why the authentication attempt was unsuccessful
     *
     * If authentication was successful, this method returns an empty array.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
