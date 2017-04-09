<?php
namespace MMal\HttpToPsr;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;

/**
 * Class MessageFactory
 * @package MMal\HttpToPsr
 */
class MessageFactory
{
    /**
     * @param string $rawHttpMessage
     * @return RequestInterface
     */
    public function requestFrom($rawHttpMessage)
    {

        $headerBodySplit = explode("\r\n\n", $rawHttpMessage);

        $headerPart = $headerBodySplit[0];

        $bodyPart = isset($headerBodySplit[1]) ? $headerBodySplit[1] : null;

        $method = $this->getMethod($headerPart);

        $exploded = explode("\r\n", $headerPart);

        array_shift($exploded);

        $headers = [];


        foreach($exploded as $line){
            if($line){
                $regexForSplittingHeader = '%(^.+?(?=:)):(.*)%';
                $groups = [];
                preg_match($regexForSplittingHeader, $line, $groups);
                $headers[$groups[1]] = explode(',', trim($groups[2]));
            }
        }

        return new ServerRequest($method, $headers['Host'][0], $headers, $bodyPart);
    }

    /**
     * @param $rawHttpMessage
     * @return mixed
     */
    protected function getMethod($rawHttpMessage)
    {
        $matches = [];
        preg_match('?^(GET|POST|PUT|DELETE) \/?', $rawHttpMessage, $matches);
        $method = array_pop($matches);

        return $method;
    }
}