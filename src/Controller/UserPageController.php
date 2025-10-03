<?php

namespace App\Controller;

use App\Entity\Bag;
use App\Form\BagType;
use App\Repository\BagRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserPageController extends AbstractController
{
    #[Route('/user/page', name: 'app_user_page')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('user_page/index.html.twig', [
        ]);
    }

    #[Route('user/page/mybags', name: 'app_user_page_mybags')]
    public function mybags(BagRepository $bagRepository)
    {

        $bags = $bagRepository->findBy(['owner' => $this->getUser()->getId()]);

        return $this->render('user_page/mybags.html.twig', [
            'bags' => $bags
        ]);
        //bouton forcer le rendu
    }


    #[Route('user/page/allBorrows', name: 'app_user_allborrows')]
    public function allBorrows(BagRepository $bagRepository)
    {
        $bags = $bagRepository->findBy(['user' => $this->getUser()->getId()]);

        return $this->render('user_page/borrows.html.twig', [
            'bags' => $bags
        ]);
    }

}

