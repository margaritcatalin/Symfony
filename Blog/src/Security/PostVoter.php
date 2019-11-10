<?php
namespace App\Security;
use App\Entity\User;
use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/**
 *
 */
class PostVoter extends Voter
{
  const EDIT = "edit";
  const DELETE = 'delete';

  /** @var AccessDecisionManagerInterface */
  private $decisionManager;

  public function __construct(AccessDecisionManagerInterface $decisionManager)
  {
    $this->decisionManager = $decisionManager;
  }

  protected function supports($attribute, $subject)
  {
    if(!in_array($attribute, [
        self::EDIT,
        self::DELETE
    ])) {
        return false;
    }
    if(!$subject instanceof Post){
      return false;
    }
    return true;
  }
  protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
  {
    if($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
        return true;
    }
    $authenticatedUser = $token->getUser();
    if (!$authenticatedUser instanceof User) {
        return false;
    }
    /** @var Post $post */
    $post = $subject;
    return $post->getUser()->getId() === $authenticatedUser->getId();
  }
}