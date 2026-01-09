<?php

namespace Optimus\Heimdal\Tests;

use PHPUnit\Framework\TestCase;
use Optimus\Heimdal\ResponseFactory;
use Exception;

class ResponseFactoryTest extends TestCase
{
    public function testMakeReturnsJsonResponse()
    {
        $exception = new Exception('Test exception');
        $response = ResponseFactory::make($exception);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
    }

    public function testResponseContainsErrorStatus()
    {
        $exception = new Exception('Test exception');
        $response = ResponseFactory::make($exception);
        $data = $response->getData(true);

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('error', $data['status']);
    }
}
