<?php

namespace Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BudgetControllerTest extends WebTestCase
{
    public function testShowAllBudgets()
    {
        $client = static::createClient();

        $client->request('GET', '/budget/get');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}