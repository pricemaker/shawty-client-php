<?php

namespace Shawty\Client\Http;
use Shawty\Client\Exception\RemoteErrorException;

/**
 * cURLAdapter
 *
 * @author Patrick Hindmarsh <patrick@pricemaker.co.nz>
 */
class CurlAdapter implements AdapterInterface {

    private $response_headers = array();

    /**
     * {@inheritdoc}
     */
    public function get($path, array $headers = array()) {
        $ch = curl_init();
        $this->response_headers = array();

        curl_setopt_array($ch, array(
            CURLOPT_URL            => $path,
            CURLOPT_HTTPHEADER     => $this->formatHeaders($headers),
            CURLOPT_HEADER         => 1,
            CURLOPT_RETURNTRANSFER => 1,
        ));

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        if(!curl_errno($ch)){
            $info = curl_getinfo($ch);

            return new Response($info['http_code'], $body, $this->parseHeaders($header));
        } else {
            throw new RemoteErrorException(curl_error($ch));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, $body = null, array $headers = array()) {
        $ch = curl_init();
        $this->response_headers = array();

        curl_setopt_array($ch, array(
            CURLOPT_URL            => $path,
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $this->formatHeaders($headers),
            CURLOPT_HEADER         => 1,
            CURLOPT_RETURNTRANSFER => 1,
        ));

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        if(!curl_errno($ch)){
            $info = curl_getinfo($ch);

            return new Response($info['http_code'], $body, $this->parseHeaders($header));
        } else {
            throw new RemoteErrorException(curl_error($ch));
        }
    }

    /**
     * formatHeaders for CURLOPT_HTTPHEADER
     *
     * @param array $headers headers
     *
     * @return array
     */
    private function formatHeaders(array $headers) {
        $h = array();
        foreach ($headers as $key => $header) {
            $h[] = sprintf('%s: %s', $key, $header);
        }

        return $h;
    }

    private function parseHeaders($headerContent) {
        $headers = array();

        // Split the string on every "double" new line.
        $arrRequests = explode("\r\n\r\n", trim($headerContent));

        foreach (explode("\r\n", array_pop($arrRequests)) as $i => $line) {
            if ($i === 0) // discard the protocol version
                continue;
            else {
                list ($key, $value) = explode(': ', $line);
                $elements = explode(';', $value);
                $headers[$key] = $elements;
            }
        }


        return $headers;
    }

}
