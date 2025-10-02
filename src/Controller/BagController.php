<?php

namespace App\Controller;

use App\Entity\Bag;
use App\Form\BagType;
use App\Repository\BagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BagController extends AbstractController
{
    #[Route('/bag', name: 'app_bag')]
    public function index(BagRepository $bagRepository)
    {
        $bag = $bagRepository->findBy([]);

        return $this->render('bag/index.html.twig', [
            'bags' => $bag
        ]);
    }

    #[Route('/bag/add', name: 'app_bag_add')]
    public function add(Request $request, EntityManagerInterface $em)
    {
        $bag = new Bag();
        $bagForm = $this->createForm(BagType::class, $bag);
        $bagForm->handleRequest($request);

        if ($bagForm->isSubmitted() && $bagForm->isValid()) {
            $file = $bagForm->get('img')->getData();
            
            if ($file) {
                $newFileName = time() . '-' . $file->getClientOriginalName();
                $file->move($this->getParameter('bag_dir'), $newFileName);
                $bag->setImg($newFileName);

            }
            $bag->setOwner($this->getUser());
            $em->persist($bag);
            $em->flush();

            return $this->redirectToRoute('app_user_page_mybags');

        }

        return $this->render('bag/add.html.twig', [
            'bagForm' => $bagForm
        ]);

    }

    #[Route('bag/{id}/show', name:'app_bag_show')]
    public function show(BagRepository $bagRepository, int $id)
    {
        $bag = $bagRepository->findOneBy(['id' => $id]);

        return $this->render('bag/show.html.twig', [
            'bag' => $bag
        ]);
    }

    #[Route('bag/{id}/edit', name:'app_bag_edit')]
    public function edit(Request $request, EntityManagerInterface $em, Bag $bag)
    {

        $bagForm = $this->createForm(BagType::class, $bag);
        $bagForm->handleRequest($request);
        
        if($bagForm->isSubmitted() && $bagForm->isValid()){

            $file = $bagForm->get('img')->getData();

            if ($file) {

                $newFileName = time() . '-' . $file->getClientOriginalName();
                $file->move($this->getParameter('bag_dir'), $newFileName);
                $bag->setImg($newFileName);

            }

            $em->flush();
            $this->addFlash('Success', 'Votre article a été modifié');
            return $this->redirectToRoute('app_bag');
        }

        return $this->render('bag/edit.html.twig', [
            'bagForm' => $bagForm
        ]);

    }

    #[Route('bag/{id}/delete', name:'app_bag_delete', methods: ['POST'])]
    public function delete(Request $request, Bag $bag, EntityManagerInterface $em)
    {
        //dd('delete'. $bag->getId(), $request->request->get('_tokken'));
        if($this->isCsrfTokenValid('delete'. $bag->getId(), $request->request->get('_token'))) {

            $em->remove($bag);
            $em->flush();
            $this->addFlash('success', 'Bravo votre article a été supprimé');
            
            return $this->redirectToRoute('app_bag', []);

        } else {

            $this->addFlash('error', 'Echec de la suppression');
            return $this->redirectToRoute('app_bag', []);

        }

    }

}
