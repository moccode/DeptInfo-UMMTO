<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Cours
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
     *      min = 2,
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
     * @ORM\ManyToOne(targetEntity=ClasseDeCours::class, inversedBy="cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classeDeCours;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomFichierCours;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="cours_file", fileNameProperty="nomFichierCours")
     * 
     * @Assert\File(
     *     maxSize = "20M",
     *     maxSizeMessage = "Le fichier ne doit pas dépasser 20MO !",
     *     uploadIniSizeErrorMessage = "Le fichier ne doit pas dépasser 20MO !",
     *     mimeTypes = {
     *          "application/pdf", 
     *          "application/x-pdf", 
     *          "application/msword", 
     *          "application/vnd.ms-excel", 
     *          "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *          "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
     *          "text/plain",
     *          "image/*"
     *      },
     *     mimeTypesMessage = "Veuillez sélectionner un fichier valide !"
     * )
     * @var File|null
     */
    private $fichierCours;

    /**
     * @ORM\ManyToOne(targetEntity=Enseignant::class, inversedBy="cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $enseignant;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="cours", orphanRemoval=true)
     */
    private $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
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

    public function getClasseDeCours(): ?ClasseDeCours
    {
        return $this->classeDeCours;
    }

    public function setClasseDeCours(?ClasseDeCours $classeDeCours): self
    {
        $this->classeDeCours = $classeDeCours;

        return $this;
    }

    public function getNomFichierCours(): ?string
    {
        return $this->nomFichierCours;
    }

    public function setNomFichierCours(?string $nomFichierCours): self
    {
        $this->nomFichierCours = $nomFichierCours;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $fichierCours
     */
    public function setFichierCours(?File $fichierCours = null): void
    {
        $this->fichierCours = $fichierCours;

        if (null !== $fichierCours) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setDateModification(new \DateTimeImmutable);
        }
    }

    public function getFichierCours(): ?File
    {
        return $this->fichierCours;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
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
            $commentaire->setCours($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCours() === $this) {
                $commentaire->setCours(null);
            }
        }

        return $this;
    }
}
