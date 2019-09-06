<?php

namespace App\Entity\Budget;

use App\Entity\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Budget\BudgetRepository")
 * @ORM\Table(name="budget")
 */
class Budget
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=64, nullable="true")
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=512)
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=64, nullable="true")
     */
    protected $category;
    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $status;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="budgets")
     */
    protected $user;

    public function getId(): integer
    {
        return $this->id;
    }

    public function setId(integer $id)
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
}