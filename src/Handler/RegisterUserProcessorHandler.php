<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\DefaultUser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;
use SimpleUserManager\Validator\RegisterUserValidator;

use function assert;
use function is_array;

/**
 * This class registers a user with an underlying data source
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
final readonly class RegisterUserProcessorHandler implements RequestHandlerInterface
{
    public const string ROUTE_NAME_REGISTER_USER = "/user/register";

    public function __construct(
        private AdapterInterface $adapter,
        private RegisterUserValidator $inputFilter,
        private LoggerInterface|null $logger = null,
    ) {
    }

    /**
     * The method registers a new user with the underlying data source
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        if ($parsedBody === null) {
            $this->logger->warning("Could not process register user request", [
                'Reason' => 'Request body was null',
            ]);
            return new RedirectResponse(self::ROUTE_NAME_REGISTER_USER);
        }
        assert(is_array($parsedBody));

        $this->inputFilter->setData($parsedBody);
        if (! $this->inputFilter->isValid()) {
            return new RedirectResponse(self::ROUTE_NAME_REGISTER_USER);
        }

        $user = new DefaultUser(
            identity: "",
            details: [
                "email"     => $this->inputFilter->getValue("email"),
                "password"  => $this->inputFilter->getValue("password"),
                "firstName" => $this->inputFilter->getValue("first_name"),
                "lastName"  => $this->inputFilter->getValue("last_name"),
            ]
        );
        $this->adapter->registerUser($user);

        return new RedirectResponse(self::ROUTE_NAME_REGISTER_USER);
    }
}
