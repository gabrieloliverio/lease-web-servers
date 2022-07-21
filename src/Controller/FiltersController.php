<?php

namespace App\Controller;

use App\Service\FilterTypes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/filters/')]
class FiltersController extends AbstractController
{
    private $filterTypes;

    public function __construct(FilterTypes $filterTypes)
    {
        $this->filterTypes = $filterTypes;
    }

    #[Route('storage')]
    public function getStorage(): Response
    {
        $storageOptions = $this->filterTypes->getStorage();
        return $this->json($storageOptions);
    }

    #[Route('ram')]
    public function getRam(): Response
    {
        $ramOptions = $this->filterTypes->getRam();
        return $this->json($ramOptions);
    }

    #[Route('hard-disk-type')]
    public function getHardDiskType(): Response
    {
        $hardDiskTypeOptions = $this->filterTypes->getHardDiskType();
        return $this->json($hardDiskTypeOptions);
    }

    #[Route('location')]
    public function getLocation(): Response
    {
        $locations = $this->filterTypes->getLocation();
        return $this->json($locations);
    }
}