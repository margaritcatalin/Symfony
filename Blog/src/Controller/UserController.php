<?php
namespace App\Controller;
use App\Entity\User;
use App\Form\UserProfile;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
/**
 * @Route("/user")
 */
class UserController
{
  /** @var \Twig_Environment */
  private $twig;
  /** @var UserRepository */
  private $userRepository;
  /** @var FormFactoryInterface */
  private $formFactory;
  /** @var EntityManagerInterface */
  private $entityManager;
  /** @var RouterInterface */
  private $router;
  /** @var FlashBagInterface */
  private $flashBag;
  /** @var AuthorizationCheckerInterface */
  private $authorizationChecker;

  /**
   * @param Twig_Environment              $twig                 [description]
   * @param UserRepository                $userRepository       [description]
   * @param FormFactoryInterface          $formFactory          [description]
   * @param EntityManagerInterface        $entityManager        [description]
   * @param RouterInterface               $router               [description]
   * @param FlashBagInterface             $flashBag             [description]
   * @param AuthorizationCheckerInterface $authorizationChecker [description]
   */
  function __construct(
    \Twig_Environment $twig,
    UserRepository $userRepository,
    FormFactoryInterface $formFactory,
    EntityManagerInterface $entityManager,
    RouterInterface $router,
    FlashBagInterface $flashBag,
    AuthorizationCheckerInterface $authorizationChecker)
  {
    $this->twig = $twig;
    $this->userRepository = $userRepository;
    $this->formFactory = $formFactory;
    $this->entityManager = $entityManager;
    $this->router = $router;
    $this->flashBag = $flashBag;
    $this->authorizationChecker = $authorizationChecker;
  }
  /**
   * @Route("/", name="user_index")
   */
  public function index(TokenStorageInterface $tokenStorage, UserRepository $userRepository)
  {
    $users = $this->userRepository->findBy([], ['time' => 'DESC']);
    $html = $this->twig->render(
      'user/index.html.twig', [
        'users' => $users,
      ]
    );
    return new Response($html);
  }

  /**
   * @Route("/changelanguage/{language}", name="user_changelanguage")
   */
  public function userChangeLanguage(TokenStorageInterface $tokenStorage, String $language, Request $request)
  {
    $currentUser = $tokenStorage->getToken()->getUser();
    $userPreference = $currentUser->getPreferences();
    $userPreference->setLocale($language);
    $request->getSession()
                ->set('_locale', $language);
    $this->entityManager->flush();
    return new RedirectResponse(
      
      $this->router->generate('post_index')
    );
  }


  /**
   * @Route("/updateprofile", name="user_updateprofile")
   */
  public function updateProfile(TokenStorageInterface $tokenStorage, Request $request)
  {
    $currentUser = $tokenStorage->getToken()->getUser();
    $form = $this->formFactory->create(
            UserProfile::class,
            $currentUser
        );
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $this->entityManager->flush();
      return new RedirectResponse(
        $this->router->generate('post_index')
      );
    }
    return new Response($this->twig->render(
      'user/add.html.twig',
      ['form' => $form->createView()]
    ));
  }

  /**
   * @Route("/edit/{id}", name="user_edit")
   * @Security("is_granted('edit', user)", message="Access id deny")
   */
  public function edit(User $user, Request $request)
  {
    $form = $this->formFactory->create(
            UserProfile::class,
            $user
        );
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $this->entityManager->flush();
      return new RedirectResponse(
        $this->router->generate('user_index')
      );
    }
    return new Response($this->twig->render(
      'user/add.html.twig',
      ['form' => $form->createView()]
    ));
  }
  /**
   * @Route("/delete/{id}", name="user_delete")
   * @Security("is_granted('delete', user)", message="Access id deny")
   */
  public function delete(User $user)
  {
    $this->entityManager->remove($user);
    $this->entityManager->flush();
    $this->flashBag->add('notice', 'User was deleted');
    return new RedirectResponse(
      $this->router->generate('user_index')
    );
  }
}