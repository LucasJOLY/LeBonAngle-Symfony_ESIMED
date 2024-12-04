<?php

namespace App\Command;

use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delete-published-adverts',
    description: 'Supprime toutes les annonces publiées il y a X jours.',
)]
class DeletePublishedAdvertsCommand extends Command
{
    private AdvertRepository $advertRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(AdvertRepository $advertRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->advertRepository = $advertRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->addArgument('days', InputArgument::REQUIRED, 'Nombre de jours permettant de supprimer les annonces publiées il y a X jours.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $days = (int) $input->getArgument('days');
        $dateThreshold = new \DateTimeImmutable("-{$days} days");

        $adverts = $this->advertRepository->findPublishedBefore($dateThreshold);

        foreach ($adverts as $advert) {
            $this->entityManager->remove($advert);
        }

        $this->entityManager->flush();

        $output->writeln(sprintf('%d annonces publiées supprimées.', count($adverts)));

        return Command::SUCCESS;
    }
}