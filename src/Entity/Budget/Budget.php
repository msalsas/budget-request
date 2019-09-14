<?php

namespace App\Entity\Budget;

use App\DTO\BudgetRequestDTOInterface;
use App\Entity\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Budget\BudgetRepository")
 * @ORM\Table(name="budget")
 */
class Budget implements BudgetInterface
{
    const STATUS_PENDING = "PENDING";
    const STATUS_PUBLISHED = "PUBLISHED";
    const STATUS_DISCARDED = "DISCARDED";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\Length(max=128)
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=1024)
     * @Assert\NotBlank
     * @Assert\Length(max=1024)
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length(max=64)
     */
    protected $category;
    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank
     * @Assert\Choice(callback="getStatuses")
     */
    protected $status;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="budgets")
     * @Assert\NotBlank
     */
    protected $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category)
    {
        $this->category = $category;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PUBLISHED,
            self::STATUS_DISCARDED,
        ];
    }

    public static function fromDTO(BudgetRequestDTOInterface $budgetRequestDTO)
    {
        $budget = new self;
        $budget->setTitle($budgetRequestDTO->getTitle());
        $budget->setDescription($budgetRequestDTO->getDescription());
        $budget->setCategory($budgetRequestDTO->getCategory());

        return $budget;
    }
}