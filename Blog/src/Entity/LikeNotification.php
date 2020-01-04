<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\LikeNotificationRepository")
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $post;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $likedBy;
    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }
    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }
    /**
     * @return mixed
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }
    /**
     * @param mixed $likedBy
     */
    public function setLikedBy($likedBy): void
    {
        $this->likedBy = $likedBy;
    }
}