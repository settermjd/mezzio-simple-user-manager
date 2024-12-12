<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class handles starting the process to reset a user's password.
 *
 * Basically, this class:
 *
 * - Renders the form where a user can enter some details, such as their email
 *   address or phone number
 * - Processes the form
 * - Marks the user's account as being able to reset the password, such as a
 *   hash entry in a reset password table
 *
 * I don't want to force the use of a given credential or a given data source.
 * Rather this handler would use an adapter to allow for all manner of data
 * sources, and a class implementing a credential interface to allow for any
 * manner of different credentials. I also figure a middleware class would be
 * part of the bundle to help with processing.
 */
final readonly class ForgotPasswordHandler implements RequestHandlerInterface
{
    public function __construct(private TemplateRendererInterface $renderer)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render(
            "sum-app::forgot-password",
            []
        ));
    }
}
