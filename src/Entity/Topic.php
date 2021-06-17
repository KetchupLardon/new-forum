<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TopicRepository::class)
 */
class Topic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="topic", orphanRemoval=true)
     */
    private $comments;

    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="post")
     */
    private $Likes;

    /**
     * @ORM\OneToMany(targetEntity=PostDislike::class, mappedBy="post")
     */
    private $Dislikes;

    /**
     * @ORM\OneToMany(targetEntity=PostReports::class, mappedBy="post")
     */
    private $Reports;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->Likes = new ArrayCollection();
        $this->Dislikes = new ArrayCollection();
        $this->Reports = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTopic($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTopic() === $this) {
                $comment->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostLike[]
     */
    public function getLikes(): Collection
    {
        return $this->Likes;
    }

    public function addLike(PostLike $like): self
    {
        if (!$this->Likes->contains($like)) {
            $this->Likes[] = $like;
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->Likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostDislike[]
     */
    public function getDislikes(): Collection
    {
        return $this->Dislikes;
    }

    public function addDislike(PostDislike $dislike): self
    {
        if (!$this->Dislikes->contains($dislike)) {
            $this->Dislikes[] = $dislike;
            $dislike->setPost($this);
        }

        return $this;
    }

    public function removeDislike(PostDislike $dislike): self
    {
        if ($this->Dislikes->removeElement($dislike)) {
            // set the owning side to null (unless already changed)
            if ($dislike->getPost() === $this) {
                $dislike->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostReports[]
     */
    public function getReports(): Collection
    {
        return $this->Reports;
    }

    public function addReport(PostReports $report): self
    {
        if (!$this->Reports->contains($report)) {
            $this->Reports[] = $report;
            $report->setPost($this);
        }

        return $this;
    }

    public function removeReport(PostReports $report): self
    {
        if ($this->Reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getPost() === $this) {
                $report->setPost(null);
            }
        }

        return $this;
    }

    public function isLikedByUser(User $user) : bool {
        foreach($this->Likes as $like) {
            if($like->getUser() === $user) return true;
        }
        return false;
    }

    public function isDisLikedByUser(User $user) : bool {
        foreach($this->Dislikes as $dislike) {
            if($dislike->getUser() === $user) return true;
        }
        return false;
    }

    public function isReportedByUser(User $user) : bool {
        foreach($this->Reports as $reports) {
            if($reports->getUser() === $user) return true;
        }
        return false;
    }
}
