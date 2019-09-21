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
        if ($content = $request->getContent()) {
            $data = json_decode($content, true);
            $this->id = $id;
            $this->title = isset($data['title']) ? $data['title'] : null;
            $this->description = isset($data['description']) ? $data['description'] : null;
            $this->category = isset($data['category']) ? $data['category'] : null;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function toBudget(): ?Budget
    {
        $budget = new Budget();
        $id = $this->getId();
        if (isset($id)) {
            $budget->setId($id);
        }
        $budget->setTitle($this->getTitle());
        $budget->setDescription($this->getDescription());
        $budget->setCategory($this->getCategory());

        return $budget;
    }
}