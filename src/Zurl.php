<?php

namespace Zengine\Zurl;

/**
 * Class Zurl.
 */
class Zurl
{
    protected $ch;

    protected $headers = [];

    protected $defaultOptions = [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT        => 25,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT      => 'Zurl',
    ];

    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt_array($this->ch, $this->defaultOptions);
    }

    public function get($url, $vars = [])
    {
        $completeUrl = $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($vars);
        $this->withOption(CURLOPT_URL, $completeUrl);

        return $this;
    }

    public function post($url, $payload = [])
    {
        $this->withOption(CURLOPT_URL, $url);
        $this->withOption(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->withJsonPayload($payload);

        return $this;
    }

    public function put($url, $payload = [])
    {
        $this->withOption(CURLOPT_URL, $url);
        $this->withOption(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->withJsonPayload($payload);

        return $this;
    }

    public function patch($url, $payload = [])
    {
        $this->withOption(CURLOPT_URL, $url);
        $this->withOption(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->withJsonPayload($payload);

        return $this;
    }

    public function delete($url)
    {
        $this->withOption(CURLOPT_URL, $url);
        $this->withOption(CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this;
    }

    public function withUserAgent($agentName)
    {
        curl_setopt($this->ch, CURLOPT_USERAGENT, $agentName);

        return $this;
    }

    public function withOption($option, $value)
    {
        curl_setopt($this->ch, $option, $value);
    }

    public function withAuthorization($token, $name = 'Bearer')
    {
        $this->withHeader('Authorisation', "$name $token");
    }

    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function withHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->withHeader($name, $value);
        }

        return $this;
    }

    public function withoutSSLVerification()
    {
        $this->withOption(CURLOPT_SSL_VERIFYHOST, false);
        $this->withOption(CURLOPT_SSL_VERIFYPEER, false);

        return $this;
    }

    public function withJsonPayload($payload)
    {
        $data_string = json_encode($payload);
        $this->withHeader('Content-Length', strlen($data_string));
        $this->withOption(CURLOPT_POSTFIELDS, $data_string);
        $this->withHeader('Content-Type', 'application/json');
        $this->withHeader('Accept', 'application/json');

        return $this;
    }

    public function execute()
    {
        $this->withOption(CURLOPT_HTTPHEADER, $this->headers);

        return new ZurlResponse($this->ch);
    }

    public function close()
    {
        curl_close($this->ch);
    }
}
