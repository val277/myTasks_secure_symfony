<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('app_task_index');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
