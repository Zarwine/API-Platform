<?php

declare(strict_types=1);

namespace App\Entity;

trait ResourceId
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    
    public function getId(): ?int
    {
        return $this->id;
    }
}