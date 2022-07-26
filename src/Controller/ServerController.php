<?php

namespace App\Controller;

use App\DataTransferObject\HardDiskTypeDTO;
use App\DataTransferObject\LocationDTO;
use App\DataTransferObject\RamMemoryDTO;
use App\DataTransferObject\StorageDTO;
use App\Domain\Filter\RamMemoryDTO as DomainRamMemoryDTO;
use App\Domain\Filter\StorageDTO as DomainStorageDTO;
use App\Domain\Filter\LocationDTO as DomainLocationDTO;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use App\Service\ServerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/servers/')]
class ServerController extends AbstractController
{
    private $service;

    public function __construct(ServerService $service)
    {
        $this->service = $service;
    }

    #[Route('')]
    public function index(Request $request): Response
    {
        $storage = $request->query->get('storage', '');
        $ramMemory = $request->query->get('ram', '');
        $hardDiskType = $request->query->get('hard_disk_type', '');
        $location = $request->query->get('location', '');

        $memoryDTO = new RamMemoryDTO($ramMemory);
        $storageDTO = new StorageDTO($storage);
        $locationDTO = new LocationDTO($location);
        $hardDiskTypeDTO = new HardDiskTypeDTO($hardDiskType);

        $domainMemoryDTO = null;
        $domainStorageDTO = null;
        $domainLocationDTO = null;
        $hardDiskTypeEnum = null;

        if ($ramMemory) {
            $memoryMatches = [];
            preg_match('/(\d+)(\w{2})/', $memoryDTO->capacity, $memoryMatches);
            $domainMemoryDTO = new DomainRamMemoryDTO($memoryMatches[1], DataUnitEnum::from($memoryMatches[2]));
        }

        if ($storage) {
            $storageMatches = [];
            preg_match('/(\d+)(\w{2})/', $storageDTO->capacity, $storageMatches);
            $domainStorageDTO = new DomainStorageDTO($storageMatches[1], DataUnitEnum::from($storageMatches[2]));
        }

        if ($location) {
            $domainLocationDTO = new DomainLocationDTO($locationDTO->name);
        }

        if ($hardDiskType) {
            if (HardDiskTypeEnum::tryFrom($hardDiskTypeDTO->name)) {
                $hardDiskTypeEnum = HardDiskTypeEnum::from($hardDiskTypeDTO->name);
            }
        }
        
        $results = $this->service->search(
            $domainStorageDTO, 
            $domainMemoryDTO, 
            $hardDiskTypeEnum,
            $domainLocationDTO
        );

        return $this->json(['results' => $results]);
    }
}