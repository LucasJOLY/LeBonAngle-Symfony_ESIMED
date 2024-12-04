<?php

namespace App\Command;

use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delete-orphan-pictures',
    description: 'Supprime toutes les images non rattachées à une annonce créées il y a plus de X jours.',
)]
class DeletePictureNoAdvertCommand extends Command
{
    private PictureRepository $pictureRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PictureRepository $pictureRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->pictureRepository = $pictureRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->addArgument('days', InputArgument::REQUIRED, 'Nombre de jours pour supprimer les images sans annonces reliée.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $days = (int) $input->getArgument('days');
        $dateThreshold = new \DateTimeImmutable("-{$days} days");

        $pictures = $this->pictureRepository->findOrphanedBefore($dateThreshold);

        foreach ($pictures as $picture) {
            $this->entityManager->remove($picture);
        }

        $this->entityManager->flush();

        $output->writeln(sprintf('%d images sans annonce supprimées.', count($pictures)));

        return Command::SUCCESS;
    }
}
