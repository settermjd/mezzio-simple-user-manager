<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class handles the process of registering a user with the application
 *
 * Essentially, there are only a few steps that are carried out:
 *
 * 1. Provide the user with a simple form where they can provide their registration details
 * 2. On submission of the form, validate the details
 *   - If the details are invalid, redisplay the the form
 *   - If the details are valid, then create a new user in the underlying data source.
 *       If you want or need to require the user to click on a link to confirm
 *       the registration, use the RegisterUserTwoStepHandler instead.
 *       Otherwise, add the user to the current session so that they're
 *       automatically logged in. This will likely require another package. See
 *       README.md for suggestions.
 */
final readonly class RegisterUserHandler implements RequestHandlerInterface
{
    public function __construct(private TemplateRendererInterface $renderer)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render(
            "app::register-user",
            []
        ));
    }
}
