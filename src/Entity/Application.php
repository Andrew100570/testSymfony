<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $discription = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $time_of_creation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lst_edit_time = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $username = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $filename = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscription(): ?string
    {
        return $this->discription;
    }

    public function setDiscription(string $discription): self
    {
        $this->discription = $discription;

        return $this;
    }

    public function getTimeOfCreation(): ?\DateTimeInterface
    {
        return $this->time_of_creation;
    }

    public function setTimeOfCreation(\DateTimeInterface $time_of_creation): self
    {
        $this->time_of_creation = $time_of_creation;

        return $this;
    }

    public function getLstEditTime(): ?\DateTimeInterface
    {
        return $this->lst_edit_time;
    }

    public function setLstEditTime(?\DateTimeInterface $lst_edit_time): self
    {
        $this->lst_edit_time = $lst_edit_time;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
