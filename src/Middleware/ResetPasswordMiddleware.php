<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\EventManager\EventManagerInterface;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SimpleUserManager\Service\ResetPassword\Adapter\AdapterInterface;

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
final readonly class ResetPasswordMiddleware implements MiddlewareInterface
{
    public const string ROUTE_NAME_RESET_PASSWORD = "/reset-password";

    public function __construct(
        private AdapterInterface $adapter,
        private InputFilterInterface $inputFilter,
        private EventManagerInterface $eventManager
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $this->inputFilter->setData($request->getParsedBody());
        if (! $this->inputFilter->isValid()) {
            return new RedirectResponse(self::ROUTE_NAME_RESET_PASSWORD);
        }

        if (
            $this->adapter->resetPassword(
                $this->inputFilter->getValue("email"),
                $this->inputFilter->getValue("password"),
            )
        ) {
            $this->eventManager->trigger("reset-password-successful");
        }

        return $handler->handle($request);
    }
}
