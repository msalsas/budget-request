<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

class CreateBudgetRequestDTO implements BudgetRequestDTOInterface
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
        $data = json_decode($request->getContent(), true);
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->category = $data['category'];
        $this->email = $data['email'];
        $this->telephone = $data['telephone'];
        $this->address = $data['address'];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setCategory(string $category)
    {
        $this->category = $category;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setTelephone(string $telephone)
    {
        $this->telephone = $telephone;
    }

    public function setAddress(string $address)
    {
        $this->address = $address;
    }
}