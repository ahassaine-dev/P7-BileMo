<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Exception\PhoneNotFoundException;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PhoneController extends AbstractController
{
    private PhoneRepository $phoneRepository;

    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }

    #[Route('/phones', name: 'index_phone', methods: ['GET'])]
    public function index(): JsonResponse
    {
        try {
            return $this->json($this->phoneRepository->findAll(), 200, ['groups' => 'phone:read']);
        } catch (NotFoundHttpException $exception) {
            return $this->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    #[Route('/phones/{id<\d+>}', name: 'get_one_phone')]
    public function getOne(int $id, Phone $phone = null): JsonResponse
    {
        if ($phone) {
            $data = [
                'Produit' => $phone,
                'Lister' => [
                    'Method:' => 'GET',
                    'Link' => $this->generateUrl('index_phone', [], UrlGeneratorInterface::ABSOLUTE_URL)
                ]
            ];
            return $this->json($data, 200, [], ['groups' => 'phone:read']);
        } else {
            throw new PhoneNotFoundException($id);
        }
    }
}
