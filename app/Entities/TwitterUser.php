<?php

namespace App\Entities;

class TwitterUser
{
    private $numberOfFollowers;

    public function __construct(int $numberOfFollowers)
    {
        $this->numberOfFollowers = $numberOfFollowers;
    }

    /**
     * @return int
     */
    public function getNumberOfFollowers(): int
    {
        return $this->numberOfFollowers;
    }
}