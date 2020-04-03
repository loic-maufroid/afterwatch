<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilmRepository")
 */
class Film
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
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $affiche;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nationalite;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="films")
     */
    private $genre_film;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Realisateur", inversedBy="films")
     */
    private $realise;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Acteur", inversedBy="films")
     */
    private $acteur_joue;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Scenariste", inversedBy="films")
     */
    private $scenario;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Status", mappedBy="id_utilisateur", orphanRemoval=true)
     */
    private $statuses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Status", mappedBy="id_film", orphanRemoval=true)
     */
    private $statues_film;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="id_film", orphanRemoval=true)
     */
    private $commentaires;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Critique", mappedBy="id_film", orphanRemoval=true)
     */
    private $critiques;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $legislation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trailer;


    public function __construct()
    {
        $this->genre_film = new ArrayCollection();
        $this->realise = new ArrayCollection();
        $this->acteur_joue = new ArrayCollection();
        $this->scenario = new ArrayCollection();
        $this->statues_film = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        
        $this->critiques = new ArrayCollection();
    }

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

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getAffiche(): ?string
    {
        return $this->affiche;
    }

    public function setAffiche(?string $affiche): self
    {
        $this->affiche = $affiche;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenreFilm(): Collection
    {
        return $this->genre_film;
    }

    public function addGenreFilm(Genre $genreFilm): self
    {
        if (!$this->genre_film->contains($genreFilm)) {
            $this->genre_film[] = $genreFilm;
        }

        return $this;
    }

    public function removeGenreFilm(Genre $genreFilm): self
    {
        if ($this->genre_film->contains($genreFilm)) {
            $this->genre_film->removeElement($genreFilm);
        }

        return $this;
    }

    /**
     * @return Collection|Realisateur[]
     */
    public function getRealise(): Collection
    {
        return $this->realise;
    }

    public function addRealise(Realisateur $realise): self
    {
        if (!$this->realise->contains($realise)) {
            $this->realise[] = $realise;
        }

        return $this;
    }

    public function removeRealise(Realisateur $realise): self
    {
        if ($this->realise->contains($realise)) {
            $this->realise->removeElement($realise);
        }

        return $this;
    }

    /**
     * @return Collection|Acteur[]
     */
    public function getActeurJoue(): Collection
    {
        return $this->acteur_joue;
    }

    public function addActeurJoue(Acteur $acteurJoue): self
    {
        if (!$this->acteur_joue->contains($acteurJoue)) {
            $this->acteur_joue[] = $acteurJoue;
        }

        return $this;
    }

    public function removeActeurJoue(Acteur $acteurJoue): self
    {
        if ($this->acteur_joue->contains($acteurJoue)) {
            $this->acteur_joue->removeElement($acteurJoue);
        }

        return $this;
    }

    /**
     * @return Collection|Scenariste[]
     */
    public function getScenario(): Collection
    {
        return $this->scenario;
    }

    public function addScenario(Scenariste $scenario): self
    {
        if (!$this->scenario->contains($scenario)) {
            $this->scenario[] = $scenario;
        }

        return $this;
    }

    public function removeScenario(Scenariste $scenario): self
    {
        if ($this->scenario->contains($scenario)) {
            $this->scenario->removeElement($scenario);
        }

        return $this;
    }

    /**
     * @return Collection|Status[]
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    /**
     * @return Collection|Status[]
     */
    public function getStatuesFilm(): Collection
    {
        return $this->statues_film;
    }

    public function addStatuesFilm(Status $statuesFilm): self
    {
        if (!$this->statues_film->contains($statuesFilm)) {
            $this->statues_film[] = $statuesFilm;
            $statuesFilm->setIdFilm($this);
        }

        return $this;
    }

    public function removeStatuesFilm(Status $statuesFilm): self
    {
        if ($this->statues_film->contains($statuesFilm)) {
            $this->statues_film->removeElement($statuesFilm);
            // set the owning side to null (unless already changed)
            if ($statuesFilm->getIdFilm() === $this) {
                $statuesFilm->setIdFilm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtlisateurCommente(): Collection
    {
        return $this->utlisateur_commente;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdFilm($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdFilm() === $this) {
                $commentaire->setIdFilm(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Critique[]
     */
    public function getCritiques(): Collection
    {
        return $this->critiques;
    }

    public function addCritique(Critique $critique): self
    {
        if (!$this->critiques->contains($critique)) {
            $this->critiques[] = $critique;
            $critique->setIdFilm($this);
        }

        return $this;
    }

    public function removeCritique(Critique $critique): self
    {
        if ($this->critiques->contains($critique)) {
            $this->critiques->removeElement($critique);
            // set the owning side to null (unless already changed)
            if ($critique->getIdFilm() === $this) {
                $critique->setIdFilm(null);
            }
        }

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

    public function getLegislation(): ?int
    {
        return $this->legislation;
    }

    public function setLegislation(?int $legislation): self
    {
        $this->legislation = $legislation;

        return $this;
    }

    public function getTrailer(): ?string
    {
        return $this->trailer;
    }

    public function setTrailer(?string $trailer): self
    {
        $this->trailer = $trailer;

        return $this;
    }

   

}
