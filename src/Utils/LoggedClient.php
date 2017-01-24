<?php

namespace Utils;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Listener\BasicAuthListener;

class LoggedClient
{
    private $logger;
    private $timeout;

    public function __construct(Logger $l, $timeout = 5, $sslVerify = false)
    {
        $this->sslVerify = $sslVerify;
        $this->logger = $l;
        $this->timeout = $timeout;
    }

    public function get($url = null, $options = array())
    {
        return $this->safeRequest('get', $url, $options);
    }

    public function getJson($url = null, $options = array())
    {
        $response = $this->get($url, $options);
        return json_decode($response->getContent());
    }

    public function post($url = null, array $options = array())
    {
        return $this->safeRequest('post', $url, $options);
    }

    public function put($url = null, array $options = array())
    {
        return $this->safeRequest('put', $url, $options);
    }

    public function delete($url = null, array $options = array())
    {
        return $this->safeRequest('delete', $url, $options);
    }

    private function safeRequest($method, $url = null, array $options = array())
    {
        $response = $this->buzzProxy($method, $url, $options);
        $loggedData = array(
            // request
            $method,
            $url,
            json_encode($options),
            // response
            $response->getStatusCode(),
            $response->getContent()
        );
        $this->logger->httpCommunication($loggedData);
        return $response;
    }

    private function buzzProxy($method, $url = null, array $options = array())
    {
        try {
            $content = '';
            $curl = new Curl();
            $curl->setVerifyPeer($this->sslVerify);
            $curl->setVerifyHost($this->sslVerify);
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 0);
            $curl->setOption(CURLOPT_TIMEOUT, $this->timeout);
            $browser = new Browser($curl);
            if (isset($options['auth'])) {
                $browser->setListener(new BasicAuthListener($options['auth'][0], $options['auth'][1]));
                unset($options['auth']);
            }
            if (isset($options['body'])) {
                $content = $options['body'];
                unset($options['body']);
            }
            $headers = isset($options['headers']) ? $options['headers'] : array();
            return $browser->call($url, $method, $headers, $content);
        } catch (\Exception $e) {
            return new UnknownResponse($e);
        }
    }
}
