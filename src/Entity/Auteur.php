<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 */
class Auteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siecle;

    /**
     * @ORM\OneToMany(targetEntity=Citation::class, mappedBy="auteur", orphanRemoval=true)
     */
    private $citation;

    public function __construct()
    {
        $this->citation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSiecle(): ?string
    {
        return $this->siecle;
    }

    public function setSiecle(string $siecle): self
    {
        $this->siecle = $siecle;

        return $this;
    }

    /**
     * @return Collection|Citation[]
     */
    public function getCitation(): Collection
    {
        return $this->citation;
    }

    public function addCitation(Citation $citation): self
    {
        if (!$this->citation->contains($citation)) {
            $this->citation[] = $citation;
            $citation->setAuteur($this);
        }

        return $this;
    }

    public function removeCitation(Citation $citation): self
    {
        if ($this->citation->removeElement($citation)) {
            // set the owning side to null (unless already changed)
            if ($citation->getAuteur() === $this) {
                $citation->setAuteur(null);
            }
        }

        return $this;
    }
    public function __toString() {

        return "$this->prenom $this->nom";
    }
}
