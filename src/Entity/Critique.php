<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CritiqueRepository")
 */
class Critique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(
     *      message = "Le Titre ne peut pas être vide",
     * )
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "Le Titre ne peut pas être inférieur à 1 caractères",
     *      maxMessage = "Le Titre ne peut pas être supérieur à 255 caractères",
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank(
     *      message = "La critique ne peut pas être vide",
     * )
     * @Assert\Length(
     *      min = 50,
     *      max = 1500,
     *      minMessage = "La critique ne peut pas être inférieur à 50 caractères",
     *      maxMessage = "La critique ne peut pas être supérieur à 1500 caractères",
     * )
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Film", inversedBy="critiques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_film;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="critiques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_utilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\NotBlank(
     *      message = "Un note doit être soumise",
     * )
     */
    private $note;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publication;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getIdFilm(): ?Film
    {
        return $this->id_film;
    }

    public function setIdFilm(?Film $id_film): self
    {
        $this->id_film = $id_film;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getPublication(): ?bool
    {
        return $this->publication;
    }

    public function setPublication(bool $publication): self
    {
        $this->publication = $publication;

        return $this;
    }
}
