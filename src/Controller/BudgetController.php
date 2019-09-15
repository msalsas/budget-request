<?php

namespace App\Controller;

use App\DTO\CreateBudgetRequestDTO;
use App\DTO\GetBudgetsResponseDTO;
use App\Entity\Budget\Budget;
use App\Entity\User\User;
use App\Service\BudgetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    /**
     * @Route("/budget/{email}", name="get_all", methods={"GET"})
     * @param $email string
     * @param $budgetService BudgetService
     * @return Response
     */
    public function getAll(string $email = null, BudgetService $budgetService)
    {
        $budgets = $budgetService->getAllPaginated($email);

        return GetBudgetsResponseDTO::toDTO($budgets);
    }

    /**
     * @Route("/budget", name="create", methods={"POST"})
     * @param $request Request
     * @param $budgetService BudgetService
     * @return Response
     */
    public function create(Request $request, BudgetService $budgetService)
    {
        $createBudgetRequestDTO = new CreateBudgetRequestDTO($request);
        $user = User::fromDTO($createBudgetRequestDTO);
        $budget = Budget::fromDTO($createBudgetRequestDTO);

        try {
            $budgetService->create($budget, $user);
        } catch (\Exception $e) {
            throw new HttpException(403, $e->getMessage());
        }

        return new Response("Success", 201);
    }
}