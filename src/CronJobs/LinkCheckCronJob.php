<?php

namespace App\CronJobs;


use App\Repository\LinkRepository;
use App\Service\CheckLinkService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Rikudou\CronBundle\Cron\CronJobInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkCheckCronJob implements CronJobInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LinkRepository $linkRepository,
    ) {
    }
    /**
     * The cron expression for triggering this cron job
     * @return string
     */
    public function getCronExpression(): string
    {
        return "* */12 * * *";
    }

    /**
     * This method will be executed when cron job runs
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param LoggerInterface $logger
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output, LoggerInterface|null $logger) : void
    {
        $checkLinkService = new CheckLinkService();
        $links = $this->linkRepository->findAll();
        foreach ($links as $link){
            $checkLinkService->checkLink($link);
            $this->entityManager->persist($link);
        }
        $this->entityManager->flush();

        $logger->debug("The cron job was executed!");
    }
}