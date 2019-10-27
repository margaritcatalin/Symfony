<?php
namespace App\Controller;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
/**
 * @Route("/post")
 */
class PostController
{
  /**
   * @var \Twig_Environment
   */
  private $twig;
  /**
   * @var PostRepository
   */
  private $PostRepository;
  /**
   * @var FormFactoryInterface
   */
  private $formFactory;
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;
  /**
   * @var RouterInterface
   */
  private $router;
  function __construct(
    \Twig_Environment $twig,
    PostRepository $postRepository,
    FormFactoryInterface $formFactory,
    EntityManagerInterface $entityManager,
    RouterInterface $router)
  {
    $this->twig = $twig;
    $this->postRepository = $postRepository;
    $this->formFactory = $formFactory;
    $this->entityManager = $entityManager;
    $this->router = $router;
  }
  /**
   * @Route("/", name="post_index")
   */
  public function index()
  {
    $html = $this->twig->render('post/index.html.twig', [
      'posts' => $this->postRepository->findAll()
    ]);
    return new Response($html);
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
}