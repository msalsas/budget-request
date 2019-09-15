<?php

namespace App\DTO;

use App\Entity\Budget\Budget;
use Symfony\Component\HttpFoundation\Response;

class GetBudgetsResponseDTO implements BudgetResponseDTOInterface
{
    const ID = "id";
    const TITLE = "title";
    const DESCRIPTION = "description";
    const CATEGORY = "category";
    const STATUS = "status";
    const EMAIL = "email";
    const TELEPHONE = "telephone";
    const ADDRESS = "address";

    public static function toDTO($budgets): Response
    {
        $content = array();
        /** @var Budget $budget */
        foreach ($budgets as $budget) {
            $user = $budget->getUser();
            $content[] = array(
                self::ID => $budget->getId(),
                self::TITLE => $budget->getTitle(),
                self::DESCRIPTION => $budget->getDescription(),
                self::CATEGORY => $budget->getCategory(),
                self::STATUS => $budget->getStatus(),
                self::EMAIL => $user->getEmail(),
                self::TELEPHONE => $user->getTelephone(),
                self::ADDRESS => $user->getAddress(),
            );
        }

        return new Response(json_encode($content));
    }
}