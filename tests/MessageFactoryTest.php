<?php
namespace MMal\HttpToPsr\Test;

use MMal\HttpToPsr\MessageFactory;

/**
 * Class MessageFactoryTest
 * @package MMal\HttpToPsr\Test
 */
class MessageFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider mesasgeProvider
     * @param string $rawHttpMessage
     */
    public function testShouldRecognizeHTTPMethod($rawHttpMessage, $expectedMethod)
    {
        $sut = new MessageFactory();

        $message = $sut->requestFrom($rawHttpMessage);

        static::assertEquals($expectedMethod, $message->getMethod());
    }

    /**
     * @test
     * @dataProvider mesasgeProviderWithHeaders
     * @param array $expectedHeaders
     */
    public function testShouldRecognizeHeaders($rawHttpMessage, $expectedHeaders)
    {
        $sut = new MessageFactory();

        $message = $sut->requestFrom($rawHttpMessage);

        static::assertEquals($expectedHeaders, $message->getHeaders());
    }

    /**
     * @test
     * @dataProvider messageProviderWithBody
     * @param array $expectedBody
     */
    public function testShouldRecognizeBody($rawHttpMessage, $expectedBody)
    {
        $sut = new MessageFactory();

        $message = $sut->requestFrom($rawHttpMessage);

        static::assertEquals($expectedBody, $message->getBody()->getContents());
    }

    public function mesasgeProvider()
    {
        return [
            [
                'GET / HTTP/1.1' . "\r\n" .
                'Host: localhost:8084' . "\r\n" .
                'User-Agent: curl/7.51.0' . "\r\n" .
                'Accept: */*' . "\r\n",
                'GET'
            ],
            [
                'POST / HTTP/1.1' . "\r\n" .
                'Host: localhost:8084' . "\r\n" .
                'User-Agent: curl/7.51.0' . "\r\n" .
                'Accept: */*' . "\r\n",
                'POST'
            ],
            [
                'PUT / HTTP/1.1' . "\r\n" .
                'Host: localhost:8084' . "\r\n" .
                'User-Agent: curl/7.51.0' . "\r\n" .
                'Accept: */*' . "\r\n",
                'PUT'
            ],
            [
                'DELETE / HTTP/1.1' . "\r\n" .
                'Host: localhost:8084' . "\r\n" .
                'User-Agent: curl/7.51.0' . "\r\n" .
                'Accept: */*' . "\r\n",
                'DELETE'
            ],

        ];
    }

    public function mesasgeProviderWithHeaders()
    {
        return [
            [
                'GET / HTTP/1.1' . "\r\n" .
                 'Host: localhost:8084' . "\r\n" .
                 'User-Agent: curl/7.51.0' . "\r\n" .
                 'Accept: */*' . "\r\n"
                 ,
                [
                    'Host' => ['localhost:8084'],
                    'User-Agent' => ['curl/7.51.0'],
                    'Accept' => ['*/*'],
                ]
            ],

        ];
    }

    public function messageProviderWithBody()
    {
        return [[
            'POST / HTTP/1.1' . "\r\n" .
            'Host: localhost:8084' . "\r\n" .
            'User-Agent: curl/7.51.0' . "\r\n" .
            'Accept: */*' . "\r\n" .
            "\n" .
            'aaaa'. "\r\n" .
            'bbbb' . "\n" .
            'ccc',
            'aaaa'. "\r\n" .
            'bbbb' . "\n" .
            'ccc'
        ]];
    }
}