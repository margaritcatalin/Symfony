<?php
namespace App\Controller;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @var NotificationRepository; 
     */
    private $notificationRepository;
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }
    /**
     * @Route("/unread-count", name="notification_unread")
     */
    public function unreadCount()
    {
        // dump($this->notificationRepository->findUnseenByUser($this->getUser()));die;
        return new JsonResponse([
            'count' => $this->notificationRepository->findUnseenByUser($this->getUser())
        ]);
    }
    /**
     * @Route("/all", name="notification_all")
     */
    public function nofifications(){
        return $this->render('notification/notifications.html.twig', [
            'notifications' => $this->notificationRepository->findBy([
                    'seen' => false,
                    'user' => $this->getUser()
                ])
        ]);
    }
    
    /**
     * @Route("/acknowladge/{id}", name="notification_acknowladge")
     */
    public function acknowladge(Notification $notification)
    {
        $notification->setSeen(true);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('notification_all');
    }  
    /**
     * @Route("/acknowladge-all", name="notification_acknowladge_all")
     */
    public function acknowladgeAll()
    {
        $this->notificationRepository->markAllAsReadByUser($this->getUser());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('notification_all');
    }
}