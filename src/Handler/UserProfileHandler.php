<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class displays a form to let a user update their profile, displaying
 * errors and success confirmations, where appropriate.
 */
final readonly class UserProfileHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private AuthenticationServiceInterface $authService,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render(
            'sum-app::user-profile',
            [
                'user' => $this->authService->getIdentity(),
            ]
        ));
    }
}
