<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={
 *                      "groups"={"get-offer-with-author"}
 *               }
 *           },
 *          "put"={
 *              "access_control" = " is_granted('ROLE_ADMIN') or (is_granted('ROLE_PUBLISHER') and object.getAuthor() === user)"
 *           }
 *      },
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "access_control"="is_granted('ROLE_PUBLISHER')"
 *           },
 *           "api_projects_offers_get_subresource"={
 *               "normalization_context"={
 *                      "groups"={"get-offer-with-author"}
 *               }
 *           }
 *     },
 *     denormalizationContext={
 *          "groups" = {"post"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer implements AuthoredEntityInterface,PublishedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-offer-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=4)
     * @Groups({"post","get-offer-with-author"})
     */
    private $period;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post","get-offer-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min=10,max=3000)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     * @Groups({"post","get-offer-with-author"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=4)
     * @Groups({"post","get-offer-with-author"})
     */
    private $filename;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post","get-offer-with-author"})
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-offer-with-author"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

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


    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function __toString() : string
    {
        return substr($this->description, 0,20). '...';
    }
}
