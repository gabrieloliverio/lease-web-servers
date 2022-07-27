<?php

namespace App\Controller;

use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Domain\Filter\LocationDTO;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use App\Form\ServerSearchType;
use App\Service\ServerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/servers')]
class ServerController extends AbstractController
{
    private $service;
    private $validator;

    public function __construct(ServerService $service, ValidatorInterface $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    #[Route('/')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ServerSearchType::class);
        $form->submit($request->query->all());

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->json(["error" => "Invalid parameters"], 422);
        }

        $storage = $request->query->get('storage', '');
        $ramMemory = $request->query->get('ram', '');
        $hardDiskType = $request->query->get('hard_disk_type', '');
        $location = $request->query->get('location', '');

        $storageDTO = null;
        $locationDTO = null;
        $hardDiskTypeEnum = null;
        $ramDTOList = [];

        if ($ramMemory) {
            $rams = explode(',', $ramMemory);
            foreach ($rams as $ram) {
                $memoryMatches = [];
                preg_match('/(\d+)(\w{2})/', $ram, $memoryMatches);
                $ramDTOList[] = new RamMemoryDTO($memoryMatches[1], DataUnitEnum::from($memoryMatches[2]));
            }
        }
        if ($storage) {
            $storageMatches = [];
            preg_match('/(\d+)(\w{2})/', $storage, $storageMatches);
            $storageDTO = new StorageDTO($storageMatches[1], DataUnitEnum::from($storageMatches[2]));
        }

        if ($location) {
            $locationDTO = new LocationDTO($location);
        }

        if ($hardDiskType) {
            $hardDiskTypeEnum = HardDiskTypeEnum::from($hardDiskType);
        }
        
        $results = $this->service->search(
            $storageDTO, 
            $ramDTOList, 
            $hardDiskTypeEnum,
            $locationDTO
        );

        return $this->json(['results' => $results]);
    }
}