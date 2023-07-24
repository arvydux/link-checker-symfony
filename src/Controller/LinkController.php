<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkType;
use App\Form\ListUploadType;
use App\Repository\LinkRepository;
use App\Service\CheckLinkService;
use App\Service\UrlListParserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/link')]
class LinkController extends AbstractController
{
    public function __construct(
        private readonly CheckLinkService $checkLinkService,
        private readonly UrlListParserService $urlListParserService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'app_link_index', methods: ['GET'])]
    public function index(LinkRepository $linkRepository): Response
    {
        return $this->render('link/index.html.twig', [
            'links' => $linkRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_link_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request
    ): Response {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->checkLinkService->checkLink($link);
            $this->entityManager->persist($link);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('link/new.html.twig', [
            'link' => $link,
            'form' => $form,
        ]);
    }

    #[Route('/upload', name: 'app_link_upload', methods: ['GET', 'POST'])]
    public function upload(
        Request $request
    ): Response {
        $form = $this->createForm(ListUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileData = $form->get('brochure')->getData();

            $urlList = [];

            if (($textFile = fopen($fileData->getPathname(), "r")) !== false) {
                $urlList = $this->urlListParserService->getUrlListFromTextFile($textFile);
            }

            foreach ($urlList as $url) {
                $link = new Link();
                $link->setUrl($url);
                $this->checkLinkService->checkLink($link);
                $this->entityManager->persist($link);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('link/upload.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_link_show', methods: ['GET'])]
    public function show(Link $link): Response
    {
        return $this->render('link/show.html.twig', [
            'link' => $link,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_link_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Link $link): Response
    {
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->checkLinkService->checkLink($link);
            $this->entityManager->persist($link);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('link/edit.html.twig', [
            'link' => $link,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_link_delete', methods: ['POST'])]
    public function delete(Request $request, Link $link): Response
    {
        if ($this->isCsrfTokenValid('delete' . $link->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($link);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
    }
}
