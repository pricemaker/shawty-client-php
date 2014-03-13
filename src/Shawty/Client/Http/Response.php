<?php

namespace Shawty\Client\Http;

/**
 * Shawty API response wrapper
 *
 * @author Patrick Hindmarsh <patrick@pricemaker.co.nz>
 */
class Response {

    public $statusCode;
    public $body;

    /**
     * @param string $statusCode statusCode
     * @param string $body body
     * @param array $headers
     */
    public function __construct($statusCode, $body, $headers = array()) {
        $this->statusCode = $statusCode;
        $this->headers    = array_change_key_case($headers, CASE_LOWER);

        if(isset($this->headers['content-type']) && in_array('application/json', $this->headers['content-type'])){
            $this->body = json_decode($body, true);
        }
        else {
            $this->body = $body;
        }

    }

}
