<?php

namespace Test\Controller;

use App\DTO\GetBudgetsResponseDTO;
use App\Helper\EntityCreationHelper;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BudgetControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

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

    public function testGetAllFilledArray()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->client->request('GET', '/budget/get');
        $content = $this->client->getResponse()->getContent();
        $budgets = $this->toArray($content);
        $this->assertIsArray($budgets);
        $this->assertEquals(2, count($budgets));

        $this->assertEquals(EntityCreationHelper::TITLE_A, $budgets[0][GetBudgetsResponseDTO::TITLE]);
        $this->assertEquals(EntityCreationHelper::DESCRIPTION_A, $budgets[0][GetBudgetsResponseDTO::DESCRIPTION]);
        $this->assertEquals(EntityCreationHelper::CATEGORY_A, $budgets[0][GetBudgetsResponseDTO::CATEGORY]);

        $this->assertEquals(EntityCreationHelper::TITLE_B, $budgets[1][GetBudgetsResponseDTO::TITLE]);
        $this->assertEquals(EntityCreationHelper::DESCRIPTION_B, $budgets[1][GetBudgetsResponseDTO::DESCRIPTION]);
        $this->assertEquals(EntityCreationHelper::CATEGORY_B, $budgets[1][GetBudgetsResponseDTO::CATEGORY]);
    }

    public function testGetAllByEmailFilledArray()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->client->request('GET', '/budget/get/' . EntityCreationHelper::EMAIL_A);
        $content = $this->client->getResponse()->getContent();
        $budgets = $this->toArray($content);
        $this->assertIsArray($budgets);

        $this->assertEquals(1, count($budgets));

        $this->assertEquals(EntityCreationHelper::TITLE_A, $budgets[0][GetBudgetsResponseDTO::TITLE]);
        $this->assertEquals(EntityCreationHelper::DESCRIPTION_A, $budgets[0][GetBudgetsResponseDTO::DESCRIPTION]);
        $this->assertEquals(EntityCreationHelper::CATEGORY_A, $budgets[0][GetBudgetsResponseDTO::CATEGORY]);
    }

    public function testGetAllByWrongEmailThrowsException()
    {
        EntityCreationHelper::createUsersAndBudgets($this->entityManager);

        $this->expectException(\Exception::class);

        $this->client->request('GET', '/budget/get/' . EntityCreationHelper::WRONG_EMAIL);
    }

    protected function toJson(array $array)
    {
        return json_encode($array);
    }

    protected function toArray(string $json)
    {
        return json_decode($json, true);
    }
}