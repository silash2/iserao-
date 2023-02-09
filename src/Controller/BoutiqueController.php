<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Repository\PinRepository;
use App\Repository\BoutiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/boutique")
 */
class BoutiqueController extends AbstractController
{
    /**
     * @Route("/", name="app_boutique_index", methods={"GET"})
     */
    public function index(BoutiqueRepository $boutiqueRepository): Response
    {
        

        return $this->render('boutique/index.html.twig', [
            'boutiques' => $boutiqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_boutique_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BoutiqueRepository $boutiqueRepository): Response
    {
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutiqueRepository->add($boutique, true);

            return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boutique/new.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_boutique_show", methods={"GET"})
     */
    public function show(Boutique $boutique, PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([],['createdAt'=>'DESC']);

        return $this->render('boutique/show.html.twig', [
            'boutique' => $boutique,
            'pins'=>$pins
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_boutique_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutiqueRepository->add($boutique, true);

            return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boutique/edit.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_boutique_delete", methods={"POST"})
     */
    public function delete(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boutique->getId(), $request->request->get('_token'))) {
            $boutiqueRepository->remove($boutique, true);
        }

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }
}
