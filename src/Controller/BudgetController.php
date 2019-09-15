<?php

namespace App\Controller;

use App\DTO\CreateBudgetRequestDTO;
use App\DTO\GetBudgetsResponseDTO;
use App\DTO\UpdateBudgetRequestDTO;
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
     * @Route("/budget/{email}", name="get_all", methods={"GET"}, requirements={"email"="(\w+@\w+\.\w+)?"})
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
        $budget = $createBudgetRequestDTO->toBudget();
        $user = $createBudgetRequestDTO->toUser();

        try {
            $budgetService->create($budget, $user);
        } catch (\Exception $e) {
            throw new HttpException(403, $e->getMessage());
        }

        return new Response("Success", 201);
    }

    /**
     * @Route("/budget/{id}", name="update", methods={"PUT"}, requirements={"id"="\d+"})
     * @param $id int
     * @param $request Request
     * @param $budgetService BudgetService
     * @return Response
     */
    public function update(int $id, Request $request, BudgetService $budgetService)
    {
        $updateBudgetRequestDTO = new UpdateBudgetRequestDTO($id, $request);
        $budget = $updateBudgetRequestDTO->toBudget();

        try {
            $budgetService->update($budget);
        } catch (\Exception $e) {
            throw new HttpException(403, $e->getMessage());
        }

        return new Response("Success", 204);
    }

    /**
     * @Route("/budget/publish/{id}", name="publish", methods={"PUT"}, requirements={"id"="\d+"})
     * @param $id int
     * @param $budgetService BudgetService
     * @return Response
     */
    public function publish(int $id, BudgetService $budgetService)
    {
        try {
            $budgetService->publish($id);
        } catch (\Exception $e) {
            throw new HttpException(403, $e->getMessage());
        }

        return new Response("Success", 204);
    }

    /**
     * @Route("/budget/discard/{id}", name="discard", methods={"PUT"}, requirements={"id"="\d+"})
     * @param $id int
     * @param $budgetService BudgetService
     * @return Response
     */
    public function discard(int $id, BudgetService $budgetService)
    {
        try {
            $budgetService->discard($id);
        } catch (\Exception $e) {
            throw new HttpException(403, $e->getMessage());
        }

        return new Response("Success", 204);
    }
}