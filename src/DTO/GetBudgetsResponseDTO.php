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

    public static function toDTO($budgets): Response
    {
        $content = array();
        /** @var Budget $budget */
        foreach ($budgets as $budget) {
            $content[] = array(
                self::ID => $budget->getId(),
                self::TITLE => $budget->getTitle(),
                self::DESCRIPTION => $budget->getDescription(),
                self::CATEGORY => $budget->getCategory(),
                self::STATUS => $budget->getStatus(),
            );
        }

        return new Response(json_encode($content));
    }
}