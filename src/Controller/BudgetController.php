<?php

namespace App\Controller;

use App\DTO\GetBudgetsResponseDTO;
use App\Service\BudgetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    /**
     * @Route("/budget/get/{email}", name="get_all")
     * @param $email string
     * @param $budgetService BudgetService
     * @return Response
     */
    public function getAll(string $email = null, BudgetService $budgetService)
    {
        $budgets = $budgetService->getAllPaginated($email);

        return GetBudgetsResponseDTO::toDTO($budgets);
    }
}