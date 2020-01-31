<?php declare(strict_types=1);

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * You know, for search.
 *
 * @Route("/search", name="search_user")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/user", name="search_user")
     */
    public function searchUser(Request $request, SessionInterface $session, TransformedFinder $usersFinder): Response
    {
        $q = (string) $request->query->get('q', '');
        $results = !empty($q) ? $usersFinder->findHybrid($q) : [];
        $session->set('q', $q);

        return $this->render('search/search.html.twig', compact('results', 'q'));
    }

     /**
     * @Route("/post", name="search_post")
     */
    public function searchPost(Request $request, SessionInterface $session, TransformedFinder $postsFinder): Response
    {
        $q = (string) $request->query->get('q', '');
        $results = !empty($q) ? $postsFinder->findHybrid($q) : [];
        $session->set('q', $q);

        return $this->render('search/search.html.twig', compact('results', 'q'));
    }
}