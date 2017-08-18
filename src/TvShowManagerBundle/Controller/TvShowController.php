<?php

namespace TvShowManagerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TvShowManagerBundle\Entity\TvShow;
use TvShowManagerBundle\Form\TvShowType;

/**
 * Class TvShowController
 *
 * @Route("/tvshow")
 * @package TvShowManagerBundle\Controller
 */
class TvShowController extends Controller
{
    /**
     * @Route("/", name="list_tvshow")
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tvShows = $em->getRepository('TvShowManagerBundle:TvShow')->findAll();

        return $this->render('TvShowManagerBundle:TvShow:index.html.twig', [
            'tvShows' => $tvShows
        ]);
    }

    /**
     * @Route("/add", name="add_tvshow")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $tvShow = new TvShow();

        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $slugify = $this->get('app.sluggifier');
            $tvShow->setSlug($slugify->slug($tvShow->getName()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($tvShow);
            $em->flush();
            $this->addFlash('message', 'TV Show : "'. $tvShow->getName() .'" is added !');

            return $this->redirectToRoute('list_tvshow');
        }

        return $this->render('TvShowManagerBundle:TvShow:add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="edit_tvshow")
     * @param Request $request
     * @param TvShow $tvShow
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, TvShow $tvShow)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'TV Show : "'. $tvShow->getName() .'" is modified !');
            return $this->redirectToRoute('list_tvshow');
        }

        return $this->render('TvShowManagerBundle:TvShow:add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="delete_tvshow")
     * @param TvShow $tvShow
     * @return RedirectResponse
     */
    public function deleteAction(TvShow $tvShow)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tvShow);
        $em->flush();
        $this->addFlash('message', 'TV Show : "'. $tvShow->getName() .'" is deleted !');

        return $this->redirectToRoute('list_tvshow');
    }

    /**
     * @Route("/rating", name="rating_tvshow")
     * @return string
     * @return Response
     */
    public function ratingAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tvShows = $em->getRepository('TvShowManagerBundle:TvShow')->findAll();
        $scores = [];
        foreach ($tvShows as $tvShow) {
            $totalNote = 0;
            $tvShowName = $tvShow->getName();
            $episodes = $em->getRepository('TvShowManagerBundle:Episode')->findBy([
                'tvShow' => $tvShow
            ]);
            foreach ($episodes as $episode){
                $totalNote += $episode->getNote();
            }
            if (count($episodes) == 0) {
                $scores[$tvShowName] = "This TV show is not yet evaluated !";
            } else {
                $scores[$tvShowName] = round($totalNote / count($episodes), 1);
                arsort($scores);
            }
        }

        return $this->render('TvShowManagerBundle:TvShow:rating.html.twig', [
            'scores' => $scores,
        ]);
    }
}
