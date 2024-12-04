<?php

namespace App\Controller;

use     App\Entity\AdminUser;
use App\Form\AdminUserType;
use App\Repository\AdminUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user')]
final class DashboardController extends AbstractController
{
    #[Route( '/admin/dashboard',
        name: 'admin_dashboard', methods: ['GET'])]
    public function index(): Response
    {

        $adminUser = $this->getUser();


        return $this->render('admin/dashboard/index.html.twig', [
            'admin_user' => $adminUser,
        ]);
    }

}
