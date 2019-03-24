<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *               "normalization_context"={
 *                      "groups"={"get","get-user-with-projects"}
 *               }
 *           },
 *           "put"={
 *              "access_control" = " is_granted('IS_AUTHENTICATED_FULLY') and ( object === user or user.getRoles() === ['ROLE_ADMIN'] )",
 *               "denormalization_context"={
 *                      "groups"={"put"}
 *               },
 *               "normalization_context"={
 *                      "groups"={"get"}
 *               }
 *           }
 *     },
 *     collectionOperations={
 *            "post"={
 *                 "denormalization_context"={
 *                      "groups"={"post"}
 *                 },
 *                 "normalization_context"={
 *                      "groups"={"get"}
 *               }
 *             }
 *     },
 *
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_PUBLISHER = 'ROLE_PUBLISHER';
    const ROLE_FREELANCER = 'ROLE_FREELANCER';

    const DEFAULT_ROLES = [self::ROLE_FREELANCER];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get","post","get-offer-with-author","get-project-with-author","get-user-with-projects"})
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=254)
     * @Groups({"post","put"})
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(min=6, max=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"get","post","put","get-offer-with-author","get-project-with-author","get-user-with-projects"})
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=8)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=15, options={"default": "TUNISIE"})
     * @Groups({"get","post","put","get-offer-with-author","get-project-with-author","get-user-with-projects"})
     * @Assert\Length(min=4, max=15)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post","put"})
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be 7 char long, contain at least 1 digit, one upper char and 1 lowwer case char"
     * )
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Groups({"post","put"})
     * @Assert\Expression(
     *     "this.getPassword() === this.getRetypedPassword()",
     *     message="Passwords does not match"
     * )
     */
    private $retypedPassword;

    /**
     * @ORM\Column(type="smallint", length=1)
     * @Groups({"get","post","put","get-offer-with-author","get-project-with-author","get-user-with-projects"})
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=1)
     */
    private $type;

    /**
     * @ORM\Column(type="smallint", length=2, options={"default": 0})
     * @Groups({"put","get","get-offer-with-author","get-project-with-author","get-user-with-projects"})
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=2)
     */
    private $note;

    /**
     * @ORM\Column(type="simple_array", length=200)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="author", orphanRemoval=true)
     * @ApiSubresource()
     * @Groups({"get","get-user-with-projects"})
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="author", orphanRemoval=true)
     */
    private $offers;






//
//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
//     * @Groups({"get"})
//     */
//    //private $comments;



    public function __construct()
    {


        $this->roles = self::DEFAULT_ROLES;
        $this->projects = new ArrayCollection();
        $this->offers = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param mixed $pays
     */
    public function setPays($pays): void
    {
        $this->pays = $pays;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRetypedPassword()
    {
        return $this->retypedPassword;
    }

    public function setRetypedPassword($retypedPassword): void
    {
        $this->retypedPassword = $retypedPassword;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note): void
    {
        $this->note = $note;
    }


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles() : array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }


    public function __toString() : string
    {
        return $this->username;
    }


    /**
     * @return ArrayCollection|Collection
     */
    public function getOffers()
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setAuthor($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getAuthor() === $this) {
                $offer->setAuthor(null);
            }
        }

        return $this;
    }




    /**
     * @return ArrayCollection|Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setAuthor($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getAuthor() === $this) {
                $project->setAuthor(null);
            }
        }

        return $this;
    }


}
