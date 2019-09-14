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
     * @Route("/budget/get", name="get_all")
     * @param $budgetService BudgetService
     * @return Response
     */
    public function getAll(BudgetService $budgetService)
    {
        $budgets = $budgetService->getAllPaginated();

        return GetBudgetsResponseDTO::toDTO($budgets);
    }
}