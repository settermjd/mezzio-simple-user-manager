<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class ends a user's session
 *
 * Session here does not refer to a PHP/web session, rather the more general
 * understanding of the word.
 *
 * This class doesn't need to know about how the session would end, as it would
 * likely use https://docs.laminas.dev/laminas-authentication/ (through
 * https://docs.mezzio.dev/mezzio-authentication-laminasauthentication), calling
 * $auth->clearIdentity(). Therefore, this should likely be refactored into a
 * middleware class, as it only needs to make the one function call, and does
 * not have to provide any form of user-facing views, etc.
 */
final readonly class LogoutHandler implements RequestHandlerInterface
{
    public function __construct(private AuthenticationServiceInterface|null $auth = null)
    {
    }

    /**
     * process clears the stored authentication identity and then continues on with the request pipeline
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->auth === null) {
            return new RedirectResponse('/login');
        }

        $this->auth->clearIdentity();

        return new RedirectResponse('/');
    }
}
