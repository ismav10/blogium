<?php

namespace App\Domain\BlogPost;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Domain\User\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new Post(),
        new GetCollection()
    ],
    normalizationContext: [ 'groups' => ['blogpost:read'] ],
    denormalizationContext: [ 'groups' => ['blogpost:write'] ],
    paginationItemsPerPage: 10,
    order: ['created' => 'DESC'],
    shortName: 'blogposts'
)]
#[ApiFilter(
    SearchFilter::class, 
    properties: [
        'title' => 'partial',
        'author.fullname' => 'partial',
        'author.username' => 'partial'
    ] 
)]
class BlogPost
{
    private ?int $id = null;

    #[Groups(['blogpost:read', 'blogpost:write'])]
    private ?string $title = null;

    #[Groups(['blogpost:read', 'blogpost:write'])]
    private ?string $body = null;

    #[Groups(['blogpost:read'])]
    private ?\DateTimeInterface $created = null;

    private ?string $slug = null;

    #[Groups(['blogpost:read'])]
    private ?string $media = null;

    #[Groups(['blogpost:read'])]
    private ?User $author = null;

    /**
     * @codeCoverageIgnore
     */
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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): self
    {
        $this->media = $media;

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
}
