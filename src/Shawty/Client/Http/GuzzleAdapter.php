<?php

namespace Shawty\Client\Http;

use Guzzle\Http\Client as GuzzleClient;

use Shawty\Client\Exception\RemoteErrorException;

/**
 * GuzzleAdapter
 *
 * @author Patrick Hindmarsh <patrick@pricemaker.co.nz>
 */
class GuzzleAdapter implements AdapterInterface {

    /**
     * {@inheritdoc}
     */
    public function get($path, array $headers = array()) {
        $client   = new GuzzleClient();
        $request  = $client->get($path, $headers);

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            throw new RemoteErrorException($e->getMessage());
        }

        return new Response($response->getStatusCode(), $response->getBody(true), $response->getHeaders()->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, $body = null, array $headers = array()) {
        $client   = new GuzzleClient();
        $request  = $client->post($path, $headers, $body);

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            throw new RemoteErrorException($e->getMessage());
        }

        return new Response($response->getStatusCode(), $response->getBody(true), $response->getHeaders()->toArray());
    }
}
