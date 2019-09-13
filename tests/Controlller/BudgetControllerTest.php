<?php

namespace Test\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BudgetControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testGetAllStatusCode()
    {
        $this->client->request('GET', '/budget/get');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetAllEmptyArray()
    {
        $this->client->request('GET', '/budget/get');

        $this->assertEquals($this->toJson([]), $this->client->getResponse()->getContent());
    }

    protected function toJson(array $array)
    {
        return json_encode($array);
    }
}