<?php


namespace App\Middlewares;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\RedirectResponse;

class AuthenticationMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri()->getPath();

        switch ($uri) {
            case '/login':
            case '/auth':
            case '/users/add':
                $protectedRoute = false;
                break;
            default:
                $protectedRoute = true;
        }

        $sessionUserId = $_SESSION['userId'] ?? null;
        if ($protectedRoute) {
            if (!$sessionUserId) {
                return new RedirectResponse('/login');
            }
        }

        return $handler->handle($request);
    }
}