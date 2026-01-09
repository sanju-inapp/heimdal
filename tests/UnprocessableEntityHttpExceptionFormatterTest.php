<?php

namespace Optimus\Heimdal\Tests;

use PHPUnit\Framework\TestCase;
use Optimus\Heimdal\Formatters\UnprocessableEntityHttpExceptionFormatter;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UnprocessableEntityHttpExceptionFormatterTest extends TestCase
{
    public function testFormatSetsStatusCode422()
    {
        $config = [];
        $formatter = new UnprocessableEntityHttpExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new UnprocessableEntityHttpException('Invalid data');
        
        $formatter->format($response, $exception, []);
        
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testFormatWithSimpleMessage()
    {
        $config = [];
        $formatter = new UnprocessableEntityHttpExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new UnprocessableEntityHttpException('Invalid email format');
        
        $formatter->format($response, $exception, []);
        $data = $response->getData(true);
        
        $this->assertArrayHasKey('errors', $data);
        $this->assertIsArray($data['errors']);
        $this->assertNotEmpty($data['errors']);
        $this->assertEquals('422', $data['errors'][0]['status']);
        $this->assertEquals('Validation error', $data['errors'][0]['title']);
        $this->assertEquals('Invalid email format', $data['errors'][0]['detail']);
    }

    public function testFormatWithJsonMessage()
    {
        $config = [];
        $formatter = new UnprocessableEntityHttpExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $validationErrors = json_encode([
            'email' => ['The email field is required.'],
            'name' => ['The name field is required.']
        ]);
        $exception = new UnprocessableEntityHttpException($validationErrors);
        
        $formatter->format($response, $exception, []);
        $data = $response->getData(true);
        
        $this->assertArrayHasKey('errors', $data);
        $this->assertIsArray($data['errors']);
        $this->assertCount(2, $data['errors']);
        $this->assertEquals('422', $data['errors'][0]['status']);
        $this->assertEquals('Validation error', $data['errors'][0]['title']);
    }

    public function testFormatStructure()
    {
        $config = [];
        $formatter = new UnprocessableEntityHttpExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new UnprocessableEntityHttpException('Field required', null, 1001);
        
        $formatter->format($response, $exception, []);
        $data = $response->getData(true);
        
        $this->assertArrayHasKey('status', $data['errors'][0]);
        $this->assertArrayHasKey('code', $data['errors'][0]);
        $this->assertArrayHasKey('title', $data['errors'][0]);
        $this->assertArrayHasKey('detail', $data['errors'][0]);
        $this->assertEquals(1001, $data['errors'][0]['code']);
    }
}
