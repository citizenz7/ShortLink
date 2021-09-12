<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkController extends AbstractController
{
    /**
     * @Route("/", name="add")
     */
    public function add(
        Request $request,
        EntityManagerInterface $EntityManager,
        LinkRepository $linkRepository
    ): Response {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link->setShort(uniqid());
            $EntityManager->persist($link);
            $EntityManager->flush();

            $this->addFlash('success', $link);

            return $this->redirectToRoute('add');
        }

        return $this->render('link/add.html.twig', [
                'controller_name' => 'LinkController',
                'form' => $form->createView(),
                'links' => count($linkRepository->findAll()),
            ]);
    }

    /**
     * @Route("/{short}", name="link")
     */
    public function index(Link $link): Response
    {
        return $this->redirect($link->getLink());
    }
}
