<?php

namespace App\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Coduo\PHPMatcher\PHPMatcher;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RestContext implements Context
{
    private $client;
    private $response;
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = !empty(getenv('BASE_URL')) ? getenv('BASE_URL') : 'nginx';
        $this->client = new Client();
    }

    /**
     * @Given I send a GET request to :path
     */
    public function iSendAGetRequestTo($path): void
    {
        try {
            $url = $this->baseUrl . $path;
            $this->response = $this->client->get($url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
        }
    }

    /**
     * @Given I send a POST request to :path with body:
     */
    public function iSendAPostRequestToWithBody($path, PyStringNode $string): void
    {
        try {
            $url = $this->baseUrl . $path;
            $body = json_decode($string->getRaw(), true);
            $this->response = $this->client->post($url, [
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
        }
    }

    /**
     * @Then the response status code should be :code
     */
    public function theResponseStatusCodeShouldBe($code): void
    {
        if ($this->response === null) {
            throw new Exception("No response received");
        }

        $statusCode = $this->response->getStatusCode();
        if ($statusCode != $code) {
            throw new Exception("Expected status code $code, but got $statusCode");
        }
    }

    /**
     * @Then the response should contain JSON:
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString): void
    {
        $expectedJson = $jsonString->getRaw();
        $actualJson = $this->response->getBody()->getContents();

        $matcher = new PHPMatcher();
        if (!$matcher->match($actualJson, $expectedJson)) {
            print_r($matcher->error());
            throw new Exception("Response JSON does not match expected JSON");
        }
    }

    /**
     * @Then print the response
     */
    public function printTheResponse(): void
    {
        print_r($this->response->getBody()->getContents());
    }
}
