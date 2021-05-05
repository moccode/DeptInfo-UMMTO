<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\ClasseDeCoursRepository;
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
     *      message="Ce champ ne doit pas être vide !"
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "Le taille de champ ne doit pas être inférieure à {{ limit }} caractères !",
     *      maxMessage = "Le taille de champ ne doit pas dépasser {{ limit }} caractères !"
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
     *      message="Ce champ ne doit pas être vide !"
     * )
     * @Assert\Choice(
     *      callback={"App\Entity\Promotion", "getPromotion"},
     *      message = "Cette n'existe pas parmi les promotions disponibles !"
     * )
     */
    private $promotion;


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
}