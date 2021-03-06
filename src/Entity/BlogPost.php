<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Image;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "title": "partial",
 *          "author.name": "partial"
 *      }
 * )
 * @ApiFilter(
 *      DateFilter::class,
 *      properties={
 *          "published"
 *      }
 * )
 * @ApiFilter(
 *      RangeFilter::class,
 *      properties={
 *          "id"
 *      }
 * )
 * @ApiFilter(
 *      OrderFilter::class,
 *      properties={
 *          "id",
 *          "title"
 *      }
 * )
 * @ApiFilter(
 *      PropertyFilter::class,
 *      arguments={
 *          "parameterName": "properties",
 *          "overrideDefaultProperties": false,
 *          "whitelist": {"allowed_property"}
 *      }
 * )
 * @ApiResource(
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={"get-blog-post-with-author"}
 *             },
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"
 *          },
 *         "put"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"
 *         },
 *     },
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={"get-blog-post-with-author", "get-blog-post-with-comment"}
 *             },
 *          "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"
 *          },
 *         "post"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"
 *         }
 *     },
 *     denormalizationContext={
 *         "groups"={"post"}
 *     },
 *      
 * )
 */
class BlogPost implements AuthorisedEntityInterface, PublishedDateInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get-blog-post-with-author","get", "post", "get-blog-post-with-comment"})
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post", "get-blog-post-with-author"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @Groups({"get-blog-post-with-author", "get"})
     * @Assert\NotBlank
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="blogPost")
     * @ApiSubresource
     * @Groups({"get-blog-post-with-author", "get-blog-post-with-comments"})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image")
     * @ORM\JoinTable()
     * @ApiSubresource
     * @Groups({"post", "get-blog-post-with-author"})
     */

    private $images;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    public function setPublished(\DateTimeInterface $published): PublishedDateInterface
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

    public function setAuthor(UserInterface $author): AuthorisedEntityInterface
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

    /**
     * Get the value of images
     */ 
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image){
        $this->images->add($image);
    }

    public function removeImage(Image $image){
        $this->images->removeElement($image);
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
