<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\NotBlank()
     * @assert\Length(min=10, minMessage="Enter more characters")
     */
    private $text;
    /**
     * @ORM\Column(type="datetime")
     */
    private $time;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     *  joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $likedBy;
    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }
    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }
    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }
    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }
    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist($time): void
    {
        $this->time = new \DateTime();
    }
    /**
     * Set the value of User
     *
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
    /**
     * Get the value of User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Undocumented function
     *
     * @return Collection
     */
    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }
    public function like(User $user)
    {
        if ($this->likedBy->contains($user)) {
            return;
        }
        $this->likedBy->add($user);
    }
}