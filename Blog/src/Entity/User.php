<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="This email is already used")
 * @UniqueEntity(fields="username", message="This unername is already used")
 */
class User implements AdvancedUserInterface, \Serializable
{
  const ROLE_USER = 'ROLE_USER';
  const ROLE_ADMIN = 'ROLE_ADMIN';
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;
  /**
   * @ORM\Column(type="string", length=50, unique=true)
   * @Assert\NotBlank()
   * @Assert\Length(min=5, max=50)
   */
  private $username;
  /**
   * @Assert\NotBlank()
   * @Assert\Length(min=8, max=4095)
   */
  private $plainPassword;
  /**
   * @ORM\Column(type="string")
   */
  private $password;
  /**
   * @ORM\Column(type="string", length=254, unique=true)
   * @Assert\NotBlank()
   * @Assert\Email()
   */
  private $email;
  /**
   * @ORM\Column(type="string", length=50)
   * @Assert\NotBlank()
   * @Assert\Length(min=4, max=50)
   */
  private $fullName;
  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user")
   */
  private $posts;
  /**
   * @var array
   * @ORM\Column(type="simple_array")
   */
  private $roles;
  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
   */
  private $followers;
  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followers")
   * @ORM\JoinTable(name="following",
   *  joinColumns={
   *    @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   *  },
   *  inverseJoinColumns={
   *    @ORM\JoinColumn(name="following_user_id", referencedColumnName="id")
   *  }
   * )
   */
  private $following;
  /**
   * @var Collection
   * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="likedBy")
   */
  private $postsLiked;
  /**
   * @ORM\Column(type="string", length=30, nullable=true)
   */
  private $confirmationToken;
  /**
   * @ORM\Column(type="boolean")
   */
  private $enabled;
  /**
   *  @ORM\OneToOne(targetEntity="App\Entity\UserPreferences", cascade={"persist"})
   */
  private $preferences;
  public function __construct()
  {
    $this->posts = new ArrayCollection();
    $this->followers = new ArrayCollection();
    $this->following = new ArrayCollection();
    $this->postsLiked = new ArrayCollection();
    $this->roles = [self::ROLE_USER];
    $this->enabled = false;
  }
  public function getRoles()
  {
    return $this->roles;
  }
  /**
   * @param array $roles
   */
  public function setRoles(array $roles): void
  {
    $this->roles = $roles;
  }
  public function getPassword()
  {
    return $this->password;
  }
  public function getSalt()
  {
    return null;
  }
  public function getUsername()
  {
    return $this->username;
  }
  public function eraseCredentials()
  { }
  public function serialize()
  {
    return serialize([
      $this->id,
      $this->username,
      $this->password,
      $this->enabled
    ]);
  }
  public function unserialize($serialized)
  {
    list(
      $this->id,
      $this->username,
      $this->password,
      $this->enabled
    ) = unserialize($serialized);
  }
  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->email;
  }
  /**
   * @param mixed $email
   */
  public function setEmail($email): void
  {
    $this->email = $email;
  }
  /**
   * @return mixed
   */
  public function getFullName()
  {
    return $this->fullName;
  }
  /**
   * @param mixed $fullName
   */
  public function setFullName($fullName): void
  {
    $this->fullName = $fullName;
  }
  /**
   * @param mixed $username
   */
  public function setUsername($username): void
  {
    $this->username = $username;
  }
  /**
   * @param mixed $password
   */
  public function setPassword($password): void
  {
    $this->password = $password;
  }
  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * Set the value of Plain Password
   *
   * @param mixed plainPassword
   */
  public function setPlainPassword($plainPassword)
  {
    $this->plainPassword = $plainPassword;
  }
  /**
   * Get the value of Plain Password
   *
   * @return mixed
   */
  public function getPlainPassword()
  {
    return $this->plainPassword;
  }
  /**
   * Get the value of Posts
   *
   * @return mixed
   */
  public function getPosts()
  {
    return $this->posts;
  }
  /**
   * @return Collection
   */
  public function getFollowers()
  {
    return $this->followers;
  }
  /**
   * @return Collection
   */
  public function getFollowing()
  {
    return $this->following;
  }
  /**
   * Undocumented function
   *
   * @param User $userToFollow
   * @return void
   */
  public function follow(User $userToFollow)
  {
    if ($this->getFollowing()->contains($userToFollow)) {
      return;
    }
    $this->getFollowing()->add($userToFollow);
  }
  /**
   * Get the value of postsLiked
   *
   * @return  Collection
   */
  public function getPostsLiked()
  {
    return $this->postsLiked;
  }
  /** 
   * @return mixed
   */
  public function getConfirmationToken()
  {
    return $this->confirmationToken;
  }
  /**
   * @param mixed $confirmationToken
   */
  public function setConfirmationToken($confirmationToken): void
  {
    $this->confirmationToken = $confirmationToken;
  }
  /**
   * Get the value of enabled
   * @return mixed
   */
  public function getEnabled()
  {
    return $this->enabled;
  }
  /**
   * Set the value of enabled
   * @param mixed $enabled
   */
  public function setEnabled($enabled): void
  {
    $this->enabled = $enabled;
  }
  /**
   * Checks whether the user's account has expired.
   *
   * Internally, if this method returns false, the authentication system
   * will throw an AccountExpiredException and prevent login.
   *
   * @return bool true if the user's account is non expired, false otherwise
   *
   * @see AccountExpiredException
   */
  public function isAccountNonExpired()
  {
    return true;
  }
  /**
   * Checks whether the user is locked.
   *
   * Internally, if this method returns false, the authentication system
   * will throw a LockedException and prevent login.
   *
   * @return bool true if the user is not locked, false otherwise
   *
   * @see LockedException
   */
  public function isAccountNonLocked()
  {
    return true;
  }
  /**
   * Checks whether the user's credentials (password) has expired.
   *
   * Internally, if this method returns false, the authentication system
   * will throw a CredentialsExpiredException and prevent login.
   *
   * @return bool true if the user's credentials are non expired, false otherwise
   *
   * @see CredentialsExpiredException
   */
  public function isCredentialsNonExpired()
  {
    return true;
  }
  /**
   * Checks whether the user is enabled.
   *
   * Internally, if this method returns false, the authentication system
   * will throw a DisabledException and prevent login.
   *
   * @return bool true if the user is enabled, false otherwise
   *
   * @see DisabledException
   */
  public function isEnabled()
  {
    return $this->enabled;
  }
  /**
   * Get the value of preferences
   * @return UserPreferences|null $preferences
   */ 
  public function getPreferences()
  {
    return $this->preferences;
  }
  /**
   * @param mixed $preferences
   */ 
  public function setPreferences($preferences)
  {
    $this->preferences = $preferences;
  }
}