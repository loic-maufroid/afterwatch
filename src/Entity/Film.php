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

    public function __construct()
    {
        $this->genre_film = new ArrayCollection();
        $this->realise = new ArrayCollection();
        $this->acteur_joue = new ArrayCollection();
        $this->scenario = new ArrayCollection();
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

}
