<?php

namespace App\DTO;

use App\Entity\Budget\Budget;
use Symfony\Component\HttpFoundation\Request;

class UpdateBudgetRequestDTO implements UpdateBudgetRequestDTOInterface
{
    /**
     * @Assert\Type("int")
     */
    protected $id;
    /**
     * @Assert\Length(max=128)
     */
    protected $title;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=1024)
     */
    protected $description;
    /**
     * @Assert\Length(max=64)
     */
    protected $category;

    public function __construct(int $id, Request $request)
    {
        $content = $request->request->all();
        if ($content && isset($content[0])) {
            $data = json_decode($content[0], true);
            $this->id = $id;
            $this->title = $data['title'];
            $this->description = $data['description'];
            $this->category = $data['category'];
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function toBudget(): ?Budget
    {
        $budget = new Budget();
        $budget->setId($this->getId());
        $budget->setTitle($this->getTitle());
        $budget->setDescription($this->getDescription());
        $budget->setCategory($this->getCategory());

        return $budget;
    }
}