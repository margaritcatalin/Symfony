<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\User;
/**
     * @Route("/following")
     * @Security("is_granted('ROLE_USER')")
    */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
    */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if($userToFollow->getId() !== $currentUser->getId()) {
            $currentUser->follow($userToFollow);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
        
        return $this->redirectToRoute('post_user', [
            'username' => $userToFollow->getUsername()
        ]);
    }
    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
    */
    public function unfollow(User $userToUnfollow)
    {
       /** @var User $currentUser */
       $currentUser = $this->getUser();
       $currentUser->getFollowing()->removeElement($userToUnfollow);
       $this->getDoctrine()->getManager()->flush();
       return $this->redirectToRoute('post_user', [
           'username' => $userToUnfollow->getUsername()
       ]);
    }
}