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
        $content = $request->request->all();
        if ($content && isset($content[0])) {
            $data = json_decode($content[0], true);
            $this->title = $data['title'];
            $this->description = $data['description'];
            $this->category = $data['category'];
            $this->email = $data['email'];
            $this->telephone = $data['telephone'];
            $this->address = $data['address'];
        }
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}