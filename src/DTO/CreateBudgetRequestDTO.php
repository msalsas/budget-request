<?php

namespace App\DTO;

use App\Entity\Budget\Budget;
use App\Entity\User\User;
use Symfony\Component\HttpFoundation\Request;

class CreateBudgetRequestDTO implements CreateBudgetRequestDTOInterface
{
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
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=64)
     */
    protected $email;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=32)
     */
    protected $telephone;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=128)
     */
    protected $address;

    public function __construct(Request $request)
    {
        if ($content = $request->getContent()) {
            $data = json_decode($content, true);
            $this->title = isset($data['title']) ? $data['title'] : null;
            $this->description = isset($data['description']) ? $data['description'] : null;
            $this->category = isset($data['category']) ? $data['category'] : null;
            $this->email = isset($data['email']) ? $data['email'] : '';
            $this->telephone = isset($data['telephone']) ? $data['telephone'] : null;
            $this->address = isset($data['address']) ? $data['address'] : null;
        }
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function toBudget(): ?Budget
    {
        $budget = new Budget();
        $budget->setTitle($this->getTitle());
        $budget->setDescription($this->getDescription());
        $budget->setCategory($this->getCategory());

        return $budget;
    }

    public function toUser(): ?User
    {
        $user = new User();
        $user->setEmail($this->getEmail());
        $user->setTelephone($this->getTelephone());
        $user->setAddress($this->getAddress());

        return $user;
    }
}