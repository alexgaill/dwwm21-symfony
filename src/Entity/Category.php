<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity('name')]
class Category {

    /**
     * Id de la catégorie
     *
     * @var integer
     * Avec les attributs on a précisé à doctrine que l'id est un id de table, qu'il est auto increment (generatedValue)
     * et que c'est une colonne présente dans la table ayant pour type un integer.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    // #[ORM\Column(name:"categorie_id", type:"integer")]
    #[ORM\Column(type:"integer")]
    private int $id;

    /**
     * Nom de la catégorie
     *
     * @var string
     * Avec les attributs, on précise à doctrine que le name est une colonne de la table catégorie
     * que son type est une chaine de caractère d'une longueur de 60 caractères
     * et qu'on ne peut pas avoir plusieurs catégories avec un nom identique.
     */
    #[ORM\Column(type:"string", unique:true, length:60)]
    #[Assert\NotBlank(message: "Le champs {{ label }} ne peut pas être vide")]
    #[Assert\Length(
        min:5,
        minMessage: "Le nom de la catégorie doit faire au minimum {{ limit }} caractères",
        max:60,
        maxMessage: "Le nom de la catégorie doit faire au maximum {{ limit }} caractères"
    )]
    #[Assert\Type('string')]
    #[Assert\Regex("/[\w]*[a-zA-Z]+[\w]*/")] // Le nom de la catégorie doit comporter au moins une lettre et aucun caractère spécial
    private string $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Post::class, orphanRemoval: true)]
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Retourne l'id de la catégorie
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de la catégorie
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Définit le nom de la catégorie
     *
     * @param string $name
     * @return self
     */
    public function setName (string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}