<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

class GetBudgetsRequestDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=64)
     */
    protected $email;
    /**
     * @Assert\Type("int)
     */
    protected $offset;
    /**
     * @Assert\Type("int)
     */
    protected $limit;

    public function __construct(Request $request)
    {
        $this->email = $request->get('email');
        $this->offset = $request->get('offset');
        $this->limit = $request->get('limit');
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getLimit()
    {
        return $this->limit;
    }
}