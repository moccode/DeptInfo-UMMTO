<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
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
    private $titre;

    /**
     * @ORM\OneToMany(targetEntity=ClasseDeCours::class, mappedBy="promotion")
     */
    private $classeDeCours;

    public function __construct()
    {
        $this->classeDeCours = new ArrayCollection();
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

    /**
     * @return Collection|ClasseDeCours[]
     */
    public function getClasseDeCours(): Collection
    {
        return $this->classeDeCours;
    }

    public function addClasseDeCour(ClasseDeCours $classeDeCour): self
    {
        if (!$this->classeDeCours->contains($classeDeCour)) {
            $this->classeDeCours[] = $classeDeCour;
            $classeDeCour->setPromotion($this);
        }

        return $this;
    }

    public function removeClasseDeCour(ClasseDeCours $classeDeCour): self
    {
        if ($this->classeDeCours->removeElement($classeDeCour)) {
            // set the owning side to null (unless already changed)
            if ($classeDeCour->getPromotion() === $this) {
                $classeDeCour->setPromotion(null);
            }
        }

        return $this;
    }
}
