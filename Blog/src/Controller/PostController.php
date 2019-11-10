<?php
namespace App\Controller;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
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
/**
 * @Route("/post")
 */
class PostController
{
  /** @var \Twig_Environment */
  private $twig;
  /** @var PostRepository */
  private $postRepository;
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
   * @param PostRepository           $postRepository  [description]
   * @param FormFactoryInterface          $formFactory          [description]
   * @param EntityManagerInterface        $entityManager        [description]
   * @param RouterInterface               $router               [description]
   * @param FlashBagInterface             $flashBag             [description]
   * @param AuthorizationCheckerInterface $authorizationChecker [description]
   */
  function __construct(
    \Twig_Environment $twig,
    PostRepository $postRepository,
    FormFactoryInterface $formFactory,
    EntityManagerInterface $entityManager,
    RouterInterface $router,
    FlashBagInterface $flashBag,
    AuthorizationCheckerInterface $authorizationChecker)
  {
    $this->twig = $twig;
    $this->postRepository = $postRepository;
    $this->formFactory = $formFactory;
    $this->entityManager = $entityManager;
    $this->router = $router;
    $this->flashBag = $flashBag;
    $this->authorizationChecker = $authorizationChecker;
  }
  /**
   * @Route("/", name="post_index")
   */
  public function index()
  {
    $html = $this->twig->render(
      'post/index.html.twig', [
        'posts' =>
        $this->postRepository->findBy([], ['time' => 'DESC'])
      // $this->postRepository->findAll()
      ]
    );
    return new Response($html);
  }
  /**
   * @Route("/edit/{id}", name="post_edit")
   * @Security("is_granted('edit', post)", message="Access id deny")
   */
  public function edit(Post $post, Request $request)
  {
    // $this->denyUnlessGranted('edit', $post);
    //
    // if(!$this->authorizationChecker->isGranted('edit', $post)){
    //   throw new UnauthorizedHttpException();
    // }
    //
    $form = $this->formFactory->create(
            PostType::class,
            $post
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
      'post/add.html.twig',
      ['form' => $form->createView()]
    ));
  }
  /**
   * @Route("/delete/{id}", name="post_delete")
   * @Security("is_granted('delete', post)", message="Access id deny")
   */
  public function delete(Post $post)
  {
    $this->entityManager->remove($post);
    $this->entityManager->flush();
    $this->flashBag->add('notice', 'Post was deleted');
    return new RedirectResponse(
      $this->router->generate('post_index')
    );
  }
  /**
   * @Route ("/add", name="post_add")
   */
  public function add(Request $request)
  {
    $post = new Post();
    $post->setTime(new \DateTime());
    $form = $this->formFactory->create(
            PostType::class,
            $post
        );
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $this->entityManager->persist($post);
      $this->entityManager->flush();
      return new RedirectResponse(
        $this->router->generate('post_index')
      );
    }
    return new Response($this->twig->render(
      'post/add.html.twig',
      ['form' => $form->createView()]
    ));
  }
  /**
   * @Route("/{id}", name="post_post")
   */
  public function post(Post $post)
  {
    return new Response(
      $this->twig->render(
        'post/post.html.twig',
        [
          'post' => $post
        ]
      )
    );
  }
}