<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get",
 *         "put"={
 *             "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user)"
 *         }
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "access_control"="is_granted('ROLE_COMMENTATOR')",
 *             "normalization_context"={
 *                 "groups"={"get-comment-with-author"}
 *             }
 *         },
 *         "api_blog_posts_comments_get_subresource"={
 *             "normalization_context"={
 *                 "groups"={"get-comment-with-author"}
 *             }
 *         }
 *     },
 *     denormalizationContext={
 *         "groups"={"post"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment implements AuthorisedEntityInterface, PublishedDateInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"get-comment-with-author"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @Groups({"get-comment-with-author"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BlogPost", inversedBy="comment")
     */
    private $blogPost;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateInterface
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */ 
    public function setAuthor(UserInterface $author): AuthorisedEntityInterface
    {
        $this->author = $author;

        return $this;
    }
    /**
     * Get the value of blogPost
     */ 
    public function getBlogPost()
    {
        return $this->blogPost;
    }

    /**
     * Set the value of blogPost
     *
     * @return  self
     */ 
    public function setBlogPost($blogPost)
    {
        $this->blogPost = $blogPost;

        return $this;
    }
}
