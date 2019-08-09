<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
 * @ApiResource(
 * attributes={
 *     "normalization_context"={"groups"={"get"}},
 *     "denormalization_context"={"groups"={"put"}}
 * })
 *      collectionOperations={
 *          "get"={"access_control"="is_granted('IS_AUTHENTICATED_FULLY')",           
 *          normalizationContext={"groups"={"get"}},
 *         "post"={"access_control"="is_granted('IS_AUTHENTICATED_FULLY')"},
 *         "put"={"access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() === user"}
 *     },
 *      itemOperations={
 *          "get"={"access_control"="is_granted('IS_AUTHENTICATED_FULLY')",           
 *          normalizationContext={"groups"={"get"}},
 *          "put"={"access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() === user",           
 *          denormalizationContext={"groups"={"put"}},}
 *     },
 * )
 */
class BlogPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get", "put"})
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("get")
     */
    private $published;

    /**
     * @ORM\Column(type="text")
     * @Groups("get")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="blogPost")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of comments
     */ 
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set the value of comments
     *
     * @return  self
     */ 
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }
}
