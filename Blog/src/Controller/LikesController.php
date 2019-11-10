<?php
namespace App\Controller;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/likes")
 */
class LikesController extends AbstractController
{
    /**
     * @Route("/like/{id}", name="likes_like")
     */
    public function like(Post $post)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $post->like($currentUser);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([
            'count' => $post->getLikedBy()->count()
        ]);
    }
    /**
     * @Route("/unlike/{id}", name="likes_unlike")
     */
    public function unlike(Post $post)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $post->getLikedBy()->removeElement($currentUser);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([
            'count' => $post->getLikedBy()->count()
        ]);
    }
}