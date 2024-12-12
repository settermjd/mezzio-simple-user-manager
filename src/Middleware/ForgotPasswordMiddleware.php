<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\EventManager\EventManagerInterface;
use Laminas\InputFilter\InputFilterInterface;
use Mezzio\Authentication\DefaultUser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;
use SimpleUserManager\Service\ForgotPassword\Result;

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
final readonly class ForgotPasswordMiddleware implements MiddlewareInterface
{
    public const string ROUTE_NAME_FORGOT_PASSWORD    = "/forgot-password";
    public const string EVENT_FORGOT_PASSWORD_SUCCESS = "forgot-message-success";
    public const string EVENT_FORGOT_PASSWORD_FAIL    = "forgot-message-fail";

    public function __construct(
        private AdapterInterface $adapter,
        private InputFilterInterface $inputFilter,
        private EventManagerInterface $eventManager
    ) {
    }

    /**
     * process clears the stored authentication identity and then continues on
     * with the request pipeline
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->inputFilter->setData($request->getParsedBody());
        if (! $this->inputFilter->isValid()) {
            return new RedirectResponse(self::ROUTE_NAME_FORGOT_PASSWORD);
        }

        $result = $this->adapter->forgotPassword($this->inputFilter->getValue("email"));
        $result->getCode() === Result::SUCCESS
            ? $this->eventManager->trigger(
                self::EVENT_FORGOT_PASSWORD_SUCCESS,
                new DefaultUser(identity: "", details: [
                    "email" => $this->inputFilter->getValue("email"),
                ])
            )
            : $this->eventManager->trigger(
                self::EVENT_FORGOT_PASSWORD_FAIL,
                new DefaultUser(identity: "", details: [
                    "email" => $this->inputFilter->getValue("email"),
                ])
            );

        return $handler->handle($request);
    }
}
