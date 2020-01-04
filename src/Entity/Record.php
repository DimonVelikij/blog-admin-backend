<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordRepository")
 * @ORM\Table(name="records")
 */
class Record
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
    private $ruTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $enTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $ruDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $enDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecordCategory")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRuTitle(): ?string
    {
        return $this->ruTitle;
    }

    public function setRuTitle(string $ruTitle): self
    {
        $this->ruTitle = $ruTitle;

        return $this;
    }

    public function getEnTitle(): ?string
    {
        return $this->enTitle;
    }

    public function setEnTitle(string $enTitle): self
    {
        $this->enTitle = $enTitle;

        return $this;
    }

    public function getRuDescription(): ?string
    {
        return $this->ruDescription;
    }

    public function setRuDescription(string $ruDescription): self
    {
        $this->ruDescription = $ruDescription;

        return $this;
    }

    public function getEnDescription(): ?string
    {
        return $this->enDescription;
    }

    public function setEnDescription(string $enDescription): self
    {
        $this->enDescription = $enDescription;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?RecordCategory
    {
        return $this->category;
    }

    public function setCategory(?RecordCategory $category): self
    {
        $this->category = $category;

        return $this;
    }
}