<?php

declare(strict_types=1);

namespace SimpleUserManager;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\AdapterServiceFactory;
use Laminas\EventManager\EventManagerInterface;
use Mezzio\Application;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use SimpleUserManager\Factory\EventManagerFactory;
use SimpleUserManager\Handler\ForgotPasswordHandler;
use SimpleUserManager\Handler\ForgotPasswordHandlerFactory;
use SimpleUserManager\Handler\ForgotPasswordProcessorHandler;
use SimpleUserManager\Handler\ForgotPasswordProcessorHandlerFactory;
use SimpleUserManager\Handler\LoginHandler;
use SimpleUserManager\Handler\LoginHandlerFactory;
use SimpleUserManager\Handler\LogoutHandler;
use SimpleUserManager\Handler\LogoutHandlerFactory;
use SimpleUserManager\Handler\RegisterUserHandler;
use SimpleUserManager\Handler\RegisterUserHandlerFactory;
use SimpleUserManager\Handler\RegisterUserProcessorHandler;
use SimpleUserManager\Handler\RegisterUserProcessorHandlerFactory;
use SimpleUserManager\Handler\ResetPasswordHandler;
use SimpleUserManager\Handler\ResetPasswordHandlerFactory;
use SimpleUserManager\Handler\ResetPasswordProcessorHandler;
use SimpleUserManager\Handler\ResetPasswordProcessorHandlerFactory;
use SimpleUserManager\Handler\UserProfileHandler;
use SimpleUserManager\Handler\UserProfileHandlerFactory;
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
            'routes'       => $this->getRouteConfig(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'sum-app'    => [__DIR__ . '/../templates/app'],
                'sum-layout' => [__DIR__ . '/../templates/layout'],
            ],
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
                EventManagerInterface::class                           => EventManagerFactory::class,
                ForgotPasswordHandler::class                           => ForgotPasswordHandlerFactory::class,
                ForgotPasswordProcessorHandler::class                  => ForgotPasswordProcessorHandlerFactory::class,
                LoginHandler::class                                    => LoginHandlerFactory::class,
                LogoutHandler::class                                   => LogoutHandlerFactory::class,
                RegisterUserHandler::class                             => RegisterUserHandlerFactory::class,
                RegisterUserProcessorHandler::class                    => RegisterUserProcessorHandlerFactory::class,
                ResetPasswordHandler::class                            => ResetPasswordHandlerFactory::class,
                ResetPasswordProcessorHandler::class                   => ResetPasswordProcessorHandlerFactory::class,
                UserProfileHandler::class                              => UserProfileHandlerFactory::class,
                Service\ForgotPassword\Adapter\AdapterInterface::class => Service\ForgotPassword\Adapter\DefaultAdapterFactory::class,
                Service\RegisterUser\Adapter\AdapterInterface::class   => Service\RegisterUser\Adapter\DefaultAdapterFactory::class,
                Service\ResetPassword\Adapter\AdapterInterface::class  => Service\ResetPassword\Adapter\DefaultAdapterFactory::class,
            ],
            'delegators' => [
                Application::class => [
                    ApplicationConfigInjectionDelegator::class,
                ],
            ],
        ];
    }

    public function getRouteConfig(): array
    {
        return [
            [
                'path'            => '/forgot-password',
                'middleware'      => ForgotPasswordHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'path'            => '/forgot-password',
                'middleware'      => ForgotPasswordHandler::class,
                'allowed_methods' => ['POST'],
            ],
            [
                'path'            => '/reset-password',
                'middleware'      => ResetPasswordHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'path'            => '/reset-password',
                'middleware'      => ResetPasswordHandler::class,
                'allowed_methods' => ['POST'],
            ],
            [
                'path'            => '/logout',
                'middleware'      => LogoutHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'path'            => '/login',
                'middleware'      => LoginHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'path'            => '/login',
                'middleware'      => AuthenticationMiddleware::class,
                'allowed_methods' => ['POST'],
            ],
            [
                'path'            => '/register',
                'middleware'      => RegisterUserHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'path'            => '/register',
                'middleware'      => RegisterUserHandler::class,
                'allowed_methods' => ['POST'],
            ],
            [
                'path'            => '/user/profile',
                'middleware'      => UserProfileHandler::class,
                'allowed_methods' => ['GET'],
            ],
        ];
    }
}
