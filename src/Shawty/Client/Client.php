<?php

namespace Shawty\Client;
use Shawty\Client\Exception\Exception;
use Shawty\Client\Http\AdapterInterface;


/**
 * Shawty URL Shortener API Client
 *
 * @author Patrick Hindmarsh <patrick@pricemaker.co.nz>
 */
class Client {


    /** @var string $endpoint*/
    protected $endpoint;

    /** @var string $accessToken*/
    protected $accessToken;

    /** @var AdapterInterface $httpAdapter */
    protected $httpAdapter;

    /**
     * @param $endpoint
     * @param string $accessToken accessToken
     * @throws \LogicException
     */
    public function __construct($endpoint, $accessToken) {
        if (empty($accessToken)) {
            throw new \InvalidArgumentException('A Shawty access token is required');
        }

        $this->endpoint = $endpoint;
        $this->accessToken = $accessToken;
    }

    /**
     * @param AdapterInterface $adapter adapter
     */
    public function setHttpAdapter(AdapterInterface $adapter) {
        $this->httpAdapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function shorten($url) {

        if(!($this->httpAdapter instanceof AdapterInterface))
            throw new Exception("HttpAdapter must be injected before calling API");

        $response = $this->httpAdapter->post($this->endpoint.'/shorten', array(
            'access_token' => $this->accessToken,
            'url' => $url
        ));

        if(isset($response->body['url']))
            return $response->body['url'];
        else
            return null;
    }

}
