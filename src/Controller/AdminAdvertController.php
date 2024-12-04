<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\Debug\TraceableWorkflow;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Workflow\Registry;
#[Route('/admin/advert')]
final class AdminAdvertController extends AbstractController{
    #[Route(name: 'app_admin_advert_index', methods: ['GET'])]
    public function index(AdvertRepository $advertRepository, PaginatorInterface $paginator, Request $request): Response
    {


        $query = $advertRepository->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->getQuery();

        $adverts = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            30
        );
        return $this->render('admin/admin_advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }


    #[Route('/{id}', name: 'app_admin_advert_show', methods: ['GET'])]
    public function show(Advert $advert): Response
    {
        return $this->render('admin/admin_advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }


    #[Route('/{id}/publish', name: 'app_admin_advert_publish', methods: ['POST'])]
    public function publish(
        Advert $advert,
        #[Target('advert.state_machine')]
        WorkflowInterface $advertWorkflow,
        EntityManagerInterface $entityManager
    ): Response {
        if ($advertWorkflow->can($advert, 'publish')) {
            $advertWorkflow->apply($advert, 'publish');
            $advert->setPublishedAt(new \DateTimeImmutable());
            $entityManager->flush();
            $this->addFlash('success', 'L\'annonce a été publiée.');
        } else {
            $this->addFlash('error', 'Impossible de publier cette annonce.');
        }

        return $this->redirectToRoute('app_admin_advert_index');
    }

    #[Route('/{id}/reject', name: 'app_admin_advert_reject', methods: ['POST'])]
    public function reject(
        Advert $advert,
        #[Target('advert.state_machine')]
        WorkflowInterface $advertWorkflow,
        EntityManagerInterface $entityManager
    ): Response {
        if ($advertWorkflow->can($advert, 'reject_from_draft') || $advertWorkflow->can($advert, 'reject_from_published')) {
            $transition = $advert->getState() === 'draft' ? 'reject_from_draft' : 'reject_from_published';
            $advertWorkflow->apply($advert, $transition);
            $entityManager->flush();
            $this->addFlash('success', 'L\'annonce a été rejetée.');
        } else {
            $this->addFlash('error', 'Impossible de rejeter cette annonce.');
        }

        return $this->redirectToRoute('app_admin_advert_index');
    }






}
