<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class renders a form where the user can update/reset their password, as
 * the second stage in the "forgot password" process.
 */
final readonly class ResetPasswordHandler implements RequestHandlerInterface
{
    public function __construct(private TemplateRendererInterface $renderer)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render("sum-app::reset-password"));
    }
}
