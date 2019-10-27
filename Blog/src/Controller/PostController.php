<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PostRepository;
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
  private $postRepository;
  function __construct(
    \Twig_Environment $twig,
    PostRepository $postRepository)
  {
    $this->twig = $twig;
    $this->postRepository = $postRepository;
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
}