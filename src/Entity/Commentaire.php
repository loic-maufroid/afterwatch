<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank(
     *      message = "Le commentaire ne peut pas être vide"
     * )
     * @Assert\Length(
     *      min = 20,
     *      max = 200,
     *      minMessage = "Le commentaire ne peut pas être inférieur à 20 caractères",
     *      maxMessage = "Le commentaire ne peut pas être supérieur à 200 caractères",
     * )
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Film", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_film;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_utilisateur;

    public function getId(): ?int
    {
        return $this->id;
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
}
