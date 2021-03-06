<?php

namespace App\Controller;

use App\DTO\CreateBudgetRequestDTO;
use App\DTO\GetBudgetsRequestDTO;
use App\DTO\GetBudgetsResponseDTO;
use App\DTO\UpdateBudgetRequestDTO;
use App\Service\BudgetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    // TODO: Handle custom exceptions
    // TODO: Handle authentication

    /**
     * Preflight action. Set required headers for non simple request.
     *
     * @Route("/{url}", name="pre_flight", methods={"OPTIONS"}, requirements={"url"=".+"})
     * @param Request $request
     * @return Response
     */
    public function preFlightAction(Request $request)
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Methods', $request->headers->get('Access-Control-Request-Method'));
        $response->headers->set('Access-Control-Allow-Headers', $request->headers->get('Access-Control-Request-Headers'));

        return $response;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return new Response("Welcome to budget-request API. Look at https://github.com/msalsas/budget-request for more info.");
    }

    /**
     * @Route("/budget", name="get_all", methods={"GET"})
     * @param $request Request
     * @param $budgetService BudgetService
     * @return Response
     */
    public function getAll(Request $request, BudgetService $budgetService)
    {
        $getBudgetRequestDTO = new GetBudgetsRequestDTO($request);
        // TODO: Validate DTO and handle exceptions
        $email = $getBudgetRequestDTO->getEmail() ?: null;
        $offset = $getBudgetRequestDTO->getOffset() ?: 0;
        $limit = $getBudgetRequestDTO->getLimit() ?: 20;

        try {
            $budgets = $budgetService->getAllPaginated($email, $offset, $limit);
        } catch (\Exception $e) {
            // TODO: Do not give info about the error
            throw new HttpException(403, $e->getMessage());
        }

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
        // TODO: Validate DTO and handle exceptions
        $budget = $createBudgetRequestDTO->toBudget();
        $user = $createBudgetRequestDTO->toUser();

        try {
            $budgetService->create($budget, $user);
        } catch (\Exception $e) {
            // TODO: Do not give SO MUCH info about the error
            throw new HttpException(403, $e->getMessage());
        }

        return new Response($budget->getId(), 201);
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
        // TODO: Validate DTO
        $budget = $updateBudgetRequestDTO->toBudget();

        try {
            $budgetService->update($budget);
        } catch (\Exception $e) {
            // TODO: Do not give SO MUCH info about the error
            throw new HttpException(403, $e->getMessage());
        }

        return new Response($budget->getId(), 204);
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
            // TODO: Do not give SO MUCH info about the error
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
            // TODO: Do not give SO MUCH info about the error
            throw new HttpException(403, $e->getMessage());
        }

        return new Response("Success", 204);
    }

    /**
     * @Route("/budget/suggest-category/{id}", name="suggest_category", methods={"GET"}, requirements={"id"="\d+"})
     * @param $id int
     * @param $budgetService BudgetService
     * @return Response
     */
    public function suggestCategory(int $id, BudgetService $budgetService)
    {
        try {
            $categoryText = $budgetService->suggestCategory($id);
        } catch (\Exception $e) {
            // TODO: Do not give SO MUCH info about the error
            throw new HttpException(403, $e->getMessage());
        }

        return new Response($categoryText, 200);
    }
}