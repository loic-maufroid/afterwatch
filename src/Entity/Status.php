<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatusRepository")
 */
class Status
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $a_vue;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $veut_voir;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAVue(): ?bool
    {
        return $this->a_vue;
    }

    public function setAVue(?bool $a_vue): self
    {
        $this->a_vue = $a_vue;

        return $this;
    }

    public function getVeutVoir(): ?bool
    {
        return $this->veut_voir;
    }

    public function setVeutVoir(?bool $veut_voir): self
    {
        $this->veut_voir = $veut_voir;

        return $this;
    }
}
