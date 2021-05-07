<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\ClasseDeCoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClasseDeCoursRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class ClasseDeCours
{

    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Ce champ est requis."
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "Ce champ ne doit pas être inférieure à {{ limit }} caractères.",
     *      maxMessage = "Ce champ ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="classeDeCours")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(
     *      message="Ce champ est requis."
     * )
     */
    private $promotion;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="classeDeCours", orphanRemoval=true)
     */
    private $cours;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setClasseDeCours($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getClasseDeCours() === $this) {
                $cour->setClasseDeCours(null);
            }
        }

        return $this;
    }
}
