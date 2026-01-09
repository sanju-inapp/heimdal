<?php

namespace Optimus\Heimdal\Tests;

use Orchestra\Testbench\TestCase;
use Optimus\Heimdal\ExceptionHandler;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ExceptionHandlerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [\Optimus\Heimdal\Provider\LaravelServiceProvider::class];
    }

    public function testRenderReturnsJsonResponse()
    {
        $handler = $this->app->make(ExceptionHandler::class);
        $request = Request::create('/test', 'GET');
        $exception = new Exception('Test exception');

        $response = $handler->render($request, $exception);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testRenderHttpException()
    {
        $handler = $this->app->make(ExceptionHandler::class);
        $request = Request::create('/test', 'GET');
        $exception = new HttpException(404, 'Not found');

        $response = $handler->render($request, $exception);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testRenderUnprocessableEntityHttpException()
    {
        $handler = $this->app->make(ExceptionHandler::class);
        $request = Request::create('/test', 'POST');
        $exception = new UnprocessableEntityHttpException('Validation failed');

        $response = $handler->render($request, $exception);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testReportDoesNotThrowException()
    {
        $handler = $this->app->make(ExceptionHandler::class);
        $exception = new Exception('Test exception');

        // Should not throw an exception
        $handler->report($exception);

        $this->assertTrue(true);
    }
}
