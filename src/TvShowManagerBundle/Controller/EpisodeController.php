<?php

namespace TvShowManagerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TvShowManagerBundle\Entity\Episode;
use TvShowManagerBundle\Form\EpisodeType;

/**
 * Class EpisodeController
 *
 * @Route("/tvshow/{slug}/episode")
 * @package TvShowManagerBundle\Controller
 */
class EpisodeController extends Controller
{
    /**
     * @Route("/", name="list_episode")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param TvShow $tvShow
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $tvShow = $em->getRepository('TvShowManagerBundle:TvShow')->findOneBy([
            'slug' => $slug
        ]);
        $episodes = $em->getRepository('TvShowManagerBundle:Episode')->findBy([
            'tvShow' => $tvShow
        ]);

        return $this->render('TvShowManagerBundle:Episode:index.html.twig', [
            'episodes' => $episodes,
            'tvShow_name' => $tvShow->getName(),
            'tvShow' => $tvShow
        ]);
    }

    /**
     * @Route("/add", name="add_episode")
     * @param Request $request
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @internal param TvShow $tvShow
     */
    public function addAction(Request $request, $slug)
    {
        $episode = new Episode();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        $tvShow = $em->getRepository('TvShowManagerBundle:TvShow')->findOneBy([
            'slug' => $slug
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $episode->setTvShow($tvShow);
            $em->persist($episode);
            $em->flush();
            $this->addFlash('message', 'Episode : "'. $episode->getName() .'" is added');

            return $this->redirectToRoute('list_episode', [
                'slug' => $tvShow->getSlug()
            ]);
        }

        return $this->render('TvShowManagerBundle:Episode:add.html.twig', [
            'form' => $form->createView(),
            'episode'=> $episode,
            'tvShow' => $tvShow
        ]);
    }

    /**
     * @Route("/edit/{episode}", name="edit_episode")
     * @param Request $request
     * @param Episode $episode
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @internal param TvShow $tvShow
     */
    public function editAction(Request $request, Episode $episode, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        $tvShow = $em->getRepository('TvShowManagerBundle:TvShow')->findOneBy([
            'slug' => $slug
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('message', 'Episode  : "'. $episode->getName() .'" is modified !');

            return $this->redirectToRoute('list_episode', [
                'slug' => $tvShow->getSlug()
            ]);
        }

        return $this->render('TvShowManagerBundle:Episode:add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{episode}", name="delete_episode")
     * @param Episode $episode
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @internal param TvShow $tvShow
     */
    public function deleteAction(Episode $episode, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $tvShow = $em->getRepository('TvShowManagerBundle:TvShow')->findOneBy([
            'slug' => $slug
        ]);
        $em->remove($episode);
        $em->flush();
        $this->addFlash('message', 'Episode  : "'. $episode->getName() .'" is deleted !');

        return $this->redirectToRoute('list_episode', [
            'slug' => $tvShow->getSlug()
        ]);
    }
}
