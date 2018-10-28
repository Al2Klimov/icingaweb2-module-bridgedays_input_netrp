<?php

namespace Icinga\Module\Bridgedays_input_netrp;

class Curl
{
    protected $handle;

    public function __construct()
    {
        $this->handle = curl_init();

        curl_setopt_array($this->handle, [
            CURLOPT_FAILONERROR    => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_RETURNTRANSFER => true
        ]);
    }

    public function __destruct()
    {
        curl_close($this->handle);
    }

    public function request($method, $url, array $headers, $body = '')
    {
        curl_setopt_array($this->handle, [
            CURLOPT_CUSTOMREQUEST     => $method,
            CURLOPT_URL               => $url,
            CURLOPT_HTTPHEADER        => $headers,
            CURLOPT_POSTFIELDS        => $body,
            CURLOPT_COOKIEFILE        => ''
        ]);

        $response = curl_exec($this->handle);

        if ($response === false) {
            throw new CurlError(curl_error($this->handle));
        }

        return $response;
    }
}
