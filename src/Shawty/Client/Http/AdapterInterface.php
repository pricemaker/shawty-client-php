<?php

namespace Shawty\Client\Http;

/**
 * AdapterInterface
 *
 * @author Patrick Hindmarsh <patrick@pricemaker.co.nz>
 */
interface AdapterInterface {

    /**
     * @param string $path    path
     * @param array  $headers headers
     *
     * @return Response
     */
    public function get($path, array $headers = array());

    /**
     * @param string $path    path
     * @param mixed  $body    body
     * @param array  $headers headers
     *
     * @return Response
     */
    public function post($path, $body = null, array $headers = array());

}
