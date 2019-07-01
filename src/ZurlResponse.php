<?php

namespace Zengine\Zurl;

class ZurlResponse
{
    protected $ch;

    protected $curlResult;

    public function __construct($ch)
    {
        $this->ch = $ch;
        $this->curlResult = curl_exec($this->ch);
    }

    public function getResponse()
    {
        $header_len = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $header = substr($this->curlResult, 0, $header_len);
        $headerLines = explode("\r\n", $header);
        $body = substr($this->curlResult, $header_len);
        $headers = [];
        foreach ($headerLines as $i => $line) {
            $headerLine = explode(':', $line);
            if ($i === 0) {
                $temp = explode(' ', $headerLine[0]);
                $headers['Http_protocol'] = $temp[0];
                $headers['Http_code'] = $temp[1];
                $headers['Http_message'] = $temp[2];
            }
            if (count($headerLine) >= 2) {
                $headers[$headerLine[0]] = $headerLine[1];
            }
        }

        return [
            'http_code'       => curl_getinfo($this->ch, CURLINFO_HTTP_CODE),
            'connection_time' => curl_getinfo($this->ch, CURLINFO_CONNECT_TIME),
            'total_time'      => curl_getinfo($this->ch, CURLINFO_TOTAL_TIME),
            'headers'         => $headers,
            'body'            => $body,
        ];
    }

    /**
     * @return bool
     */
    public function failed()
    {
        if (curl_errno($this->ch)) {
            return true;
        }

        if (curl_getinfo($this->ch, CURLINFO_HTTP_CODE) === 0) {
            return true;
        }

        if (! $this->curlHasResult()) {
            return false;
        }

        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE) >= 400;
    }

    public function errors()
    {
        return curl_error($this->ch);
    }

    /**
     * @return bool
     */
    protected function curlHasResult()
    {
        return (bool) $this->curlResult;
    }

    /**
     * @param $string
     *
     * @return bool
     */
    protected function isJson($string)
    {
        json_decode($string);

        return json_last_error() == JSON_ERROR_NONE;
    }

    /**
     * @param $string
     *
     * @return bool
     */
    protected function isHTML($string)
    {
        return $string != strip_tags($string) ? true : false;
    }
}
