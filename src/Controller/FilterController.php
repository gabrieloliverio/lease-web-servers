<?php

namespace App\Controller;

use App\Service\FilterTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/filters/')]
class FilterController extends AbstractController
{
    private $service;

    public function __construct(FilterTypeService $service)
    {
        $this->service = $service;
    }

    #[Route('storage')]
    public function getStorage(): Response
    {
        $storageOptions = $this->service->getStorage();
        return $this->json($storageOptions);
    }

    #[Route('ram')]
    public function getRam(): Response
    {
        $ramOptions = $this->service->getRam();
        return $this->json($ramOptions);
    }

    #[Route('hard-disk-type')]
    public function getHardDiskType(): Response
    {
        $hardDiskTypeOptions = $this->service->getHardDiskType();
        return $this->json($hardDiskTypeOptions);
    }

    #[Route('location')]
    public function getLocation(): Response
    {
        $locations = $this->service->getLocation();
        return $this->json($locations);
    }
}
