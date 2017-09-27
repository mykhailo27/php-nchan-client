<?php

namespace Marein\Nchan\Http;

use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    /**
     * @test
     * @dataProvider validUrlsProvider
     */
    public function itShouldBeCreatedWithValidUrls(string $url): void
    {
        $this->assertEquals(
            $url,
            (new Url($url))->toString()
        );
    }

    /**
     * @test
     * @dataProvider invalidUrlsProvider
     */
    public function itShouldThrowAnExceptionOnInvalidUrls(string $url): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Url($url);
    }

    /**
     * @test
     */
    public function itCanBeAppendedWithString(): void
    {
        $expectedUrl = 'http://localhost/foo/bar';

        $url = new Url('http://localhost');
        $urlCopy = clone $url;

        $newUrl = $url->append('/foo/bar');

        $this->assertEquals($expectedUrl, $newUrl->toString());
        # Test immutability
        $this->assertEquals($url, $urlCopy);
    }

    /**
     * @test
     */
    public function itCanBeTypeCastedToString(): void
    {
        $expectedUrl = 'http://localhost';

        $url = new Url($expectedUrl);

        $this->assertEquals($expectedUrl, (string)$url);
        $this->assertEquals($expectedUrl, $url->toString());
    }

    /**
     * Returns valid urls.
     *
     * @return array
     */
    public function validUrlsProvider(): array
    {
        return [
            ['http://localhost'],
            ['https://localhost'],
            ['http://localhost/'],
            ['https://localhost/'],
            ['http://localhost/?foo=bar'],
            ['https://localhost/?foo=bar'],
            ['http://localhost/foo/bar'],
            ['https://localhost/foo/bar']
        ];
    }

    /**
     * Returns invalid urls.
     *
     * @return array
     */
    public function invalidUrlsProvider(): array
    {
        return [
            ['/'],
            ['/?foo=bar'],
            ['/foo/bar'],
            ['malformed']
        ];
    }
}