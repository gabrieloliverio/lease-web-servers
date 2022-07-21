<?php

namespace App\Controller;

use App\Service\Filters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/filters/')]
class FiltersController extends AbstractController
{
    private $filters;

    public function __construct(Filters $filters)
    {
        $this->filters = $filters;
    }

    #[Route('storage')]
    public function getStorage(): Response
    {
        $storageOptions = $this->filters->getStorage();
        return $this->json($storageOptions);
    }

    #[Route('ram')]
    public function getRam(): Response
    {
        $ramOptions = $this->filters->getRam();
        return $this->json($ramOptions);
    }

    #[Route('hard-disk-type')]
    public function getHardDiskType(): Response
    {
        $hardDiskTypeOptions = $this->filters->getHardDiskType();
        return $this->json($hardDiskTypeOptions);
    }

    #[Route('location')]
    public function getLocation(): Response
    {
        $locations = $this->filters->getLocation();
        return $this->json($locations);
    }
}