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
    public function index(BagRepository $bagRepository): Response
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
            $em->persist($bag);
            $em->flush();

            return $this->redirectToRoute('app_bag');

        }
        return $this->render('bag/add.html.twig', [
            'bagForm' => $bagForm


        ]);


    }

}
