<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
final class Category {

    /**
     * Id de la catégorie
     *
     * @var integer
     * Avec les attributs on a précisé à doctrine que l'id est un id de table, qu'il est auto increment (generatedValue)
     * et que c'est une colonne présente dans la table ayant pour type un integer.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(name:"categorie_id", type:"integer")]
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
    private string $name;

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
}