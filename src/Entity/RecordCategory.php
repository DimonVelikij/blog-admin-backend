<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordCategoryRepository")
 * @ORM\Table(name="record_categories")
 */
class RecordCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ru_title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $en_title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRuTitle(): ?string
    {
        return $this->ru_title;
    }

    public function setRuTitle(string $ru_title): self
    {
        $this->ru_title = $ru_title;

        return $this;
    }

    public function getEnTitle(): ?string
    {
        return $this->en_title;
    }

    public function setEnTitle(string $en_title): self
    {
        $this->en_title = $en_title;

        return $this;
    }
}
