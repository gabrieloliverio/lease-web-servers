<?php

namespace App\Controller;

use App\DTO\StorageCapacityDTO;
use App\DTO\LocationDTO;
use App\DTO\RamMemoryCapacityDTO;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use App\Form\ServerSearchType;
use App\Service\ServerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

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

    #[Route('/', methods:['GET'])]
    #[OA\Parameter(
        name: 'storage',
        in: 'query',
        description: 'The storage capacity, e.g. 4TB',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'ram',
        in: 'query',
        description: 'The RAM capacities, e.g. ["2GB", "4GB"]',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string')
        )
    )]
    #[OA\Parameter(
        name: 'hard_disk_type',
        in: 'query',
        description: 'The hard disk type, e.g. "SSD" or "SATA"',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'location',
        in: 'query',
        description: 'The location, e.g. "AmsterdamAMS-01" or "FrankfurtFRA-10"',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the servers filtered by the user',
        content: new OA\JsonContent(
            type: 'array', 
            items: new OA\Items(type: 'string')
        )
    )]
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
                $ramDTOList[] = new RamMemoryCapacityDTO($memoryMatches[1], DataUnitEnum::from($memoryMatches[2]));
            }
        }
        if ($storage) {
            $storageMatches = [];
            preg_match('/(\d+)(\w{2})/', $storage, $storageMatches);
            $storageDTO = new StorageCapacityDTO($storageMatches[1], DataUnitEnum::from($storageMatches[2]));
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
