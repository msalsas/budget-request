<?php

namespace App\Entity\User;

use App\DTO\CreateBudgetRequestDTOInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=64)
     */
    protected $email;
    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank
     * @Assert\Length(max=32)
     */
    protected $telephone;
    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     * @Assert\Length(max=128)
     */
    protected $address;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Budget\Budget", mappedBy="user")
     */
    protected $budgets;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email = null)
    {
        $this->email = $email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone = null)
    {
        $this->telephone = $telephone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address = null)
    {
        $this->address = $address;
    }

    public function getBudgets(): ?Collection
    {
        return $this->budgets;
    }

    function setBudgets(Collection $budgets)
    {
        $this->budgets = $budgets;
    }
}