<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 *  * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={
 *                      "groups"={"get-project-with-author"}
 *               }
 *           },
 *          "put"={
 *              "access_control" = " is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() === user)"
 *           }
 *      },
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "access_control"="is_granted('ROLE_WRITER')"
 *           }
 *     },
 *     denormalizationContext={
 *          "groups" = {"post"}
 *     }
 * )
 */
class Project implements AuthoredEntityInterface, PublishedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-project-with-author","get-user-with-projects","get-offer-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=15)
     * @Groups({"post","get-project-with-author","get-user-with-projects","get-offer-with-author"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=20)
     * @Groups({"post","get-project-with-author","get-user-with-projects","get-offer-with-author"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=20)
     * @Groups({"post","get-project-with-author","get-user-with-projects","get-offer-with-author"})
     */
    private $budget;

    /**
     * @ORM\Column(type="smallint", length=1)
     * @Assert\NotBlank()
     * @Assert\Length(max=1)
     * @Groups({"post","get-project-with-author","get-user-with-projects","get-offer-with-author"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-project-with-author","get-user-with-projects","get-offer-with-author"})
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-project-with-author","get-offer-with-author"})
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="project", orphanRemoval=true)
     * @ApiSubresource()
     * @Groups({"get-project-with-author","get-user-with-projects","get-offer-with-author"})
     */
    private $offers;







    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBudget(): ?string
    {
        return $this->budget;
    }

    public function setBudget(string $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOffers():Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setProject($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getProject() === $this) {
                $offer->setProject(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        return $this->title;
    }




}
