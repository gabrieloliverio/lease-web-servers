<?php

namespace App\Controller;

use App\Service\FilterTypeService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api/filters/')]
class FilterController extends AbstractController
{
    private $service;

    public function __construct(FilterTypeService $service)
    {
        $this->service = $service;
    }

    #[Route('storage', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns all the available storage filters',
        content: new OA\JsonContent(
            type: 'array', 
            items: new OA\Items(type: 'string')
        )
    )]
    public function getStorage(): Response
    {
        $storageOptions = $this->service->getStorage();
        return $this->json($storageOptions);
    }

    #[Route('ram', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns all the available RAMs filters',
        content: new OA\JsonContent(
            type: 'array', 
            items: new OA\Items(type: 'string')
        )
    )]
    public function getRam(): Response
    {
        $ramOptions = $this->service->getRam();
        return $this->json($ramOptions);
    }

    #[Route('hard-disk-type', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns all the available hard disk type filters',
        content: new OA\JsonContent(
            type: 'array', 
            items: new OA\Items(type: 'string')
        )
    )]
    public function getHardDiskType(): Response
    {
        $hardDiskTypeOptions = $this->service->getHardDiskType();
        return $this->json($hardDiskTypeOptions);
    }

    #[Route('location', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns all the available location filters',
        content: new OA\JsonContent(
            type: 'array', 
            items: new OA\Items(type: 'string')
        )
    )]
    public function getLocation(): Response
    {
        $locations = $this->service->getLocation();
        return $this->json($locations);
    }
}
