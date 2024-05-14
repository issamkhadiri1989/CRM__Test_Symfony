<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Timeline\Timeline;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TimelineController extends AbstractController
{
    #[Route('/timeline', name: 'app_timeline')]
    public function index(Timeline $timeline): Response
    {
        $history = $timeline->renderTimeline();

        return $this->render('timeline/index.html.twig', [
            'history' => $history,
        ]);
    }
}
