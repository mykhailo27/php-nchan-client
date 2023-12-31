<?php

declare(strict_types=1);

namespace Marein\Nchan\Tests\Unit\Http;

use Marein\Nchan\Http\Request;
use Marein\Nchan\Http\Url;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithItsValues(): void
    {
        $expectedUrl = new Url('http://localhost/my-url');
        $expectedHeaders = ['Accept' => 'application/json'];
        $expectedBody = 'my body';

        $request = new Request($expectedUrl, $expectedHeaders, $expectedBody);

        $this->assertEquals($expectedUrl, $request->url());
        $this->assertEquals($expectedHeaders, $request->headers());
        $this->assertEquals($expectedBody, $request->body());
    }

    /**
     * @test
     */
    public function itShouldBeCreatedWithEmptyBody(): void
    {
        $expectedUrl = new Url('http://localhost/my-url');
        $expectedHeaders = ['Accept' => 'application/json'];
        $expectedBody = '';

        $request = new Request($expectedUrl, $expectedHeaders);

        $this->assertEquals($expectedUrl, $request->url());
        $this->assertEquals($expectedHeaders, $request->headers());
        $this->assertEquals($expectedBody, $request->body());
    }
}
