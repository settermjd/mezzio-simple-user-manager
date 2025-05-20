<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\EventManager\EventManagerInterface;
use Mezzio\Authentication\DefaultUser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;
use SimpleUserManager\Service\ForgotPassword\Result;
use SimpleUserManager\Validator\ForgotPasswordValidator;

use function assert;
use function is_array;

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
final readonly class ForgotPasswordProcessorHandler implements RequestHandlerInterface
{
    public const string ROUTE_NAME_FORGOT_PASSWORD    = "/forgot-password";
    public const string EVENT_FORGOT_PASSWORD_SUCCESS = "forgot-message-success";
    public const string EVENT_FORGOT_PASSWORD_FAIL    = "forgot-message-fail";

    public function __construct(
        private AdapterInterface $adapter,
        private ForgotPasswordValidator $inputFilter,
        private EventManagerInterface $eventManager,
        private LoggerInterface|null $logger = null
    ) {
    }

    /**
     * process clears the stored authentication identity and then continues on
     * with the request pipeline
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        if ($parsedBody === null) {
            $this->logger?->warning("Could not process forgot password request", [
                'Reason' => 'Request body was null',
            ]);
            return new RedirectResponse(self::ROUTE_NAME_FORGOT_PASSWORD);
        }

        assert(is_array($parsedBody));

        $this->inputFilter->setData($parsedBody);
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

        return new RedirectResponse(self::ROUTE_NAME_FORGOT_PASSWORD);
    }
}
