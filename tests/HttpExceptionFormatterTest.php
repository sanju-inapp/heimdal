<?php

namespace Optimus\Heimdal\Tests;

use PHPUnit\Framework\TestCase;
use Optimus\Heimdal\Formatters\HttpExceptionFormatter;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HttpExceptionFormatterTest extends TestCase
{
    public function testFormatSetsCorrectStatusCode()
    {
        $config = ['server_error_production' => 'An error occurred.'];
        $formatter = new HttpExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new HttpException(404, 'Not found');
        
        $formatter->format($response, $exception, []);
        
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFormatWith403StatusCode()
    {
        $config = ['server_error_production' => 'An error occurred.'];
        $formatter = new HttpExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new HttpException(403, 'Forbidden');
        
        $formatter->format($response, $exception, []);
        
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testFormatAddsHeaders()
    {
        $config = ['server_error_production' => 'An error occurred.'];
        $formatter = new HttpExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new HttpException(401, 'Unauthorized', null, ['WWW-Authenticate' => 'Bearer']);
        
        $formatter->format($response, $exception, []);
        
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertTrue($response->headers->has('WWW-Authenticate'));
        $this->assertEquals('Bearer', $response->headers->get('WWW-Authenticate'));
    }

    public function testFormatWithNotFoundHttpException()
    {
        $config = ['server_error_production' => 'An error occurred.'];
        $formatter = new HttpExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new NotFoundHttpException('Resource not found');
        
        $formatter->format($response, $exception, []);
        
        $this->assertEquals(404, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertArrayHasKey('status', $data);
    }
}
