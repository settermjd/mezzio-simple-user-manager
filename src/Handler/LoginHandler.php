<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class provides a form for the user to enter their credentials and
 * validates those credentials. After that, it effectively delegates to
 * https://docs.mezzio.dev/mezzio-authentication/ to handle the rest of the
 * process.
 */
final readonly class LoginHandler implements RequestHandlerInterface
{
    public function __construct(private TemplateRendererInterface $renderer)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render(
            "app::login",
            [
                "layout" => "layout::minimal-default",
            ]
        ));
    }
}
