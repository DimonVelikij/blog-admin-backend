<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordCategoryRepository")
 * @ORM\Table(name="record_categories")
 */
class RecordCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"admin"})
     */
    private $id;

    /**
     * @ORM\Column(name="ru_title", type="string", length=255)
     * @Assert\NotBlank(message="Укажите название ru")
     * @Groups({"admin"})
     */
    private $ruTitle;

    /**
     * @ORM\Column(name="en_title", type="string", length=255)
     * @Assert\NotBlank(message="Укажите название en")
     * @Groups({"admin"})
     */
    private $enTitle;

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
}
