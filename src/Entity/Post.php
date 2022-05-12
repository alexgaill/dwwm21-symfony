<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 60)]
    #[Assert\NotBlank(message: "Le champs {{ label }} ne peut pas être vide")]
    #[Assert\Length(
        min:5,
        minMessage: "Le nom de la catégorie doit faire au minimum {{ limit }} caractères",
        max:60,
        maxMessage: "Le nom de la catégorie doit faire au maximum {{ limit }} caractères"
    )]
    #[Assert\Type('string')]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Le champs {{ label }} ne peut pas être vide")]
    #[Assert\Length(
        min:5,
        minMessage: "Le nom de la catégorie doit faire au minimum {{ limit }} caractères"
    )]
    #[Assert\Type('string')]
    private $content;

    #[ORM\Column(type: 'datetime')]
    #[Assert\DateTime()]
    private $createdAt;

    private string $subContent;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Type(Category::class)]
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getSubContent (): ?string
    {
        $this->subContent = substr($this->content, 0, strpos($this->content, " ", 100)) . " ...";
        return $this->subContent;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
