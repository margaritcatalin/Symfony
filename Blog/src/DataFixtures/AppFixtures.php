<?php
namespace App\DataFixtures;
use App\Entity\User;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\UserPreferences;
class AppFixtures extends Fixture
{
  private const USERS = [
    [
      'username' => 'user1',
      'email' => 'user1@test.ro',
      'password' => '12341234',
      'fullName' => 'Michael Karl',
      'roles' => [User::ROLE_USER]
    ],
    [
      'username' => 'user2',
      'email' => 'user2@test.ro',
      'password' => '12341234',
      'fullName' => 'Gabriel Doe',
      'roles' => [User::ROLE_USER]
    ],
    [
      'username' => 'user3',
      'email' => 'user3@test.ro',
      'password' => '12341234',
      'fullName' => 'May Lorens',
      'roles' => [User::ROLE_USER]
    ],
    [
      'username' => 'admin',
      'email' => 'admin@test.ro',
      'password' => '12341234',
      'fullName' => 'Blog Admin',
      'roles' => [User::ROLE_ADMIN]
    ]
  ];
  private const POST_TEXT = [
    'Hello, how are you?',
    'It\'s nice sunny weather today',
    'I need to buy some ice cream!',
    'I wanna buy a new car',
    'There\'s a problem with my phone',
    'I need to go to the doctor',
    'What are you up to today?',
    'Did you watch the game yesterday?',
    'How was your day?'
  ];
  private const LANGUAGES = [
    'en',
    'ro'
  ];
  /**
   * @var UserPasswordEncoderInterface
   */
  private $passwordEncoder;
  public function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
    $this->passwordEncoder = $passwordEncoder;
  }
  public function load(ObjectManager $manager)
  {
    $this->loadUsers($manager);
    $this->loadPosts($manager);
  }
  public function loadPosts(ObjectManager $manager)
  {
    for ($i = 0; $i < 30; $i++) {
        $post = new Post();
        $post->setText(
          self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1)]
        );
        $date = new \DateTime();
        $date->modify('- ' . rand(0, 10) . ' days');
        $post->setTime($date);
        $post->setUser($this->getReference(
          self::USERS[rand(0, count(self::USERS) - 1)]['username']
        ));
        $manager->persist($post);
      }
    $manager->flush();
  }
  public function loadUsers(ObjectManager $manager)
  {
    foreach (self::USERS as $userData) {
      $user = new User();
      $user->setUsername($userData['username']);
      $user->setFullName($userData['fullName']);
      $user->setEmail($userData['email']);
      $user->setPassword(
        $this
          ->passwordEncoder->encodePassword(
            $user,
            $userData['password']
          )
      );
      $user->setRoles($userData['roles']);
      $user->setEnabled(true);
      $this->addReference(
        $userData['username'],
        $user
      );
      $preferences = new UserPreferences();
      $preferences->setLocale(self::LANGUAGES[rand(0,1)]);
      $user->setPreferences($preferences);
      $manager->persist($user);
    }
    $manager->flush();
  }
}