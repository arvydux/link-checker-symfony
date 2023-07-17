<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkRepository::class)]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4096)]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    private ?array $redirects = null;

    #[ORM\Column(nullable: true)]
    private ?int $redirect_amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $checked_at = null;

    #[ORM\Column(length: 4096, nullable: true)]
    private ?string $keywords = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getRedirects(): ?array
    {
        return $this->redirects;
    }

    public function setRedirects(?array $redirects): static
    {
        $this->redirects = $redirects;

        return $this;
    }

    public function getRedirectAmount(): ?int
    {
        return $this->redirect_amount;
    }

    public function setRedirectAmount(int $redirect_amount): static
    {
        $this->redirect_amount = $redirect_amount;

        return $this;
    }

    public function getCheckedAt(): ?\DateTimeInterface
    {
        return $this->checked_at;
    }

    public function setCheckedAt(?\DateTimeInterface $checked_at): static
    {
        $this->checked_at = $checked_at;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }
}
