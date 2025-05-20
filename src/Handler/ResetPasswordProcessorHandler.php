<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\EventManager\EventManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Service\ResetPassword\Adapter\AdapterInterface;
use SimpleUserManager\Service\ResetPassword\Result;
use SimpleUserManager\Validator\ResetPasswordValidator;

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
final readonly class ResetPasswordProcessorHandler implements RequestHandlerInterface
{
    public const string ROUTE_NAME_RESET_PASSWORD = "/reset-password";

    public function __construct(
        private AdapterInterface $adapter,
        private ResetPasswordValidator $inputFilter,
        private EventManagerInterface $eventManager,
        private LoggerInterface|null $logger = null,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        if ($parsedBody === null) {
            $this->logger->warning("Could not process register user request", [
                'Reason' => 'Request body was null',
            ]);
            return new RedirectResponse(self::ROUTE_NAME_RESET_PASSWORD);
        }
        assert(is_array($parsedBody));

        $this->inputFilter->setData($parsedBody);
        if (! $this->inputFilter->isValid()) {
            return new RedirectResponse(self::ROUTE_NAME_RESET_PASSWORD);
        }

        if (
            $this->adapter->resetPassword(
                $this->inputFilter->getValue("email"),
                $this->inputFilter->getValue("password"),
            )->getCode() === Result::SUCCESS
        ) {
            $this->eventManager->trigger("reset-password-successful");
        }

        return new RedirectResponse(self::ROUTE_NAME_RESET_PASSWORD);
    }
}
