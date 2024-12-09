<?php

declare(strict_types=1);

namespace SimpleUserManager\Entity;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\AdapterServiceFactory;
use SimpleUserManager\Handler\ForgotPasswordHandler;
use SimpleUserManager\Handler\ForgotPasswordHandlerFactory;
use SimpleUserManager\Handler\LoginHandler;
use SimpleUserManager\Handler\LoginHandlerFactory;
use SimpleUserManager\Handler\RegisterUserHandler;
use SimpleUserManager\Handler\RegisterUserHandlerFactory;
use SimpleUserManager\Handler\ResetPasswordHandler;
use SimpleUserManager\Handler\ResetPasswordHandlerFactory;
use SimpleUserManager\Handler\UserProfileHandler;
use SimpleUserManager\Handler\UserProfileHandlerFactory;
use SimpleUserManager\Middleware\ForgotPasswordMiddleware;
use SimpleUserManager\Middleware\ForgotPasswordMiddlewareFactory;
use SimpleUserManager\Middleware\LogoutMiddleware;
use SimpleUserManager\Middleware\LogoutMiddlewareFactory;
use SimpleUserManager\Middleware\RegisterUserMiddleware;
use SimpleUserManager\Middleware\RegisterUserMiddlewareFactory;
use SimpleUserManager\Middleware\ResetPasswordMiddleware;
use SimpleUserManager\Middleware\ResetPasswordMiddlewareFactory;
use SimpleUserManager\Service;
use SimpleUserManager\Validator\ForgotPasswordValidator;
use SimpleUserManager\Validator\RegisterUserValidator;
use SimpleUserManager\Validator\ResetPasswordValidator;

class ConfigProvider
{
    /**
     * Return the configuration array.
     *
     * @psalm-return array<string, mixed>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @psalm-return array<string, array<string, class-string>>
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                ForgotPasswordValidator::class => ForgotPasswordValidator::class,
                RegisterUserValidator::class   => RegisterUserValidator::class,
                ResetPasswordValidator::class  => ResetPasswordValidator::class,
            ],
            'factories'  => [
                AdapterInterface::class                                => AdapterServiceFactory::class,
                ForgotPasswordHandler::class                           => ForgotPasswordHandlerFactory::class,
                ForgotPasswordMiddleware::class                        => ForgotPasswordMiddlewareFactory::class,
                LoginHandler::class                                    => LoginHandlerFactory::class,
                LogoutMiddleware::class                                => LogoutMiddlewareFactory::class,
                RegisterUserHandler::class                             => RegisterUserHandlerFactory::class,
                RegisterUserMiddleware::class                          => RegisterUserMiddlewareFactory::class,
                ResetPasswordHandler::class                            => ResetPasswordHandlerFactory::class,
                ResetPasswordMiddleware::class                         => ResetPasswordMiddlewareFactory::class,
                UserProfileHandler::class                              => UserProfileHandlerFactory::class,
                Service\ForgotPassword\Adapter\AdapterInterface::class => Service\ResetPassword\Adapter\DefaultAdapterFactory::class,
                Service\RegisterUser\Adapter\AdapterInterface::class   => Service\RegisterUser\Adapter\DefaultAdapterFactory::class,
                Service\ResetPassword\Adapter\AdapterInterface::class  => Service\ResetPassword\Adapter\DefaultAdapterFactory::class,
            ],
        ];
    }
}