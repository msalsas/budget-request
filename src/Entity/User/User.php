<?php

namespace App\Entity\User;

use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=64)
     */
    protected $email;
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $telephone;
    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $address;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Budget\Budget", mappedBy="user")
     */
    protected $budgets;

    public function getId(): integer
    {
        return $this->id;
    }

    public function setId(integer $id)
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone)
    {
        $this->telephone = $telephone;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    function setBudgets(Collection $budgets)
    {
        $this->budgets = $budgets;
    }


}