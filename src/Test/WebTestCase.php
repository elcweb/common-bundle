<?php

namespace Elcweb\CommonBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class WebTestCase extends BaseWebTestCase
{
    protected function assertJsonResponse(Response $response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    /**
     * Assert if output contains a JSON and if JSON contains specified properties.
     *
     * @param Response $response
     * @param array $JSON_keys Which JSON properties we are expecting to exist
     * @param bool $multiRowsArray
     * @return array
     */
    protected function assertJSONResult(Response $response, $JSON_keys = array(), $multiRowsArray = true)
    {
        $result = json_decode($response->getContent(), true);
        $this->assertNotNull($result);

        if ($multiRowsArray && isset($result[0]) && is_array($result[0])) {
            $result = $result[0];
        }

        if ($result) {

            foreach ($JSON_keys as $key) {
                $this->assertArrayHasKey($key, $result);
            }
        }

        return $result;
    }

}
