<?php


namespace App\Controller\Apis;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Apis\Config\ApiInterface;
use App\Entity\Etablissement;
use App\Entity\Transaction;
use App\Repository\CiviliteRepository;
use App\Repository\EtablissementRepository;
use App\Repository\ProfessionnelRepository;
use App\Repository\SpecialiteRepository;

#[Route('/api/statistique')]
class ApiStatistiqueController extends ApiInterface
{


    #[Route('/info-dashboard', methods: ['GET'])]
    /**
     * Retourne les stats du dashboard.
     * 
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Transaction::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'statistiques')]
    // #[Security(name: 'Bearer')]
    public function index(EtablissementRepository $etablissementRepository, ProfessionnelRepository $professionnelRepository): Response
    {
        try {


            $tab = [
                'countEtablissement' => count($etablissementRepository->findAll()),
                 'countProfessionnel' =>count($professionnelRepository->findAll()), 
                'professionnelAjour' => count($professionnelRepository->allProfAjour()) 
            ];

            $response = $this->responseData($tab, 'group_user', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }

    #[Route('/civilite', methods: ['GET'])]
    /**
     * Retourne les stats du dashboard.
     * 
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Transaction::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'statistiques')]
    // #[Security(name: 'Bearer')]
    public function indexCivilite(EtablissementRepository $etablissementRepository, ProfessionnelRepository $professionnelRepository, CiviliteRepository $civiliteRepository): Response
    {
        try {
            $stats = $professionnelRepository->countProByCivilite();

            $formattedStats = [];
            $isFirst = true; // Pour le premier élément sélectionné dans le Pie Chart

            foreach ($stats as $index => $stat) {
                $formattedStats[] = [
                    'name' => $stat['civilite'],
                    'y' => (int) $stat['nombre'],
                    'sliced' => $isFirst,
                    'selected' => $isFirst
                ];
                $isFirst = false; // Désactiver la sélection après le premier élément
            }

            $formattedStats = array_reverse($formattedStats);

            $result = [
                'nombre' => $stats,
                'pieChart' => $formattedStats
            ];


            $response = $this->responseData($result, 'group_user', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }
    #[Route('/ville', methods: ['GET'])]
    /**
     * Retourne les stats du dashboard.
     * 
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Transaction::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'statistiques')]
    // #[Security(name: 'Bearer')]
    public function indexGeolocalisation(EtablissementRepository $etablissementRepository, ProfessionnelRepository $professionnelRepository, CiviliteRepository $civiliteRepository): Response
    {
        try {
            $stats = $professionnelRepository->countProByVille();

            $formattedStats = [];
            $isFirst = true; // Pour le premier élément sélectionné dans le Pie Chart

            foreach ($stats as $index => $stat) {
                $formattedStats[] = [
                    'name' => $stat['civilite'],
                    'y' => (int) $stat['nombre'],
                    'sliced' => $isFirst,
                    'selected' => $isFirst
                ];
                $isFirst = false; // Désactiver la sélection après le premier élément
            }

            $formattedStats = array_reverse($formattedStats);

            $result = [
                'nombre' => $stats,
                'pieChart' => $formattedStats
            ];
            
            
                        $response = $this->responseData($result, 'group_user', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }
    #[Route('/specialite/{genre}', methods: ['GET'])]
    /**
     * Retourne les stats du dashboard.
     * 
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Transaction::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'statistiques')]
    // #[Security(name: 'Bearer')]
    public function indexSpecialite($genre, EtablissementRepository $etablissementRepository, ProfessionnelRepository $professionnelRepository, SpecialiteRepository $specialiteRepository): Response
    {
        try {
            $stats = $specialiteRepository->countSpecialiteProfByGenre($genre);


            $formattedStats = [];
            $isFirst = true; // Pour le premier élément sélectionné dans le Pie Chart

            foreach ($stats as $index => $stat) {
                $formattedStats[] = [
                    'name' => $stat['civilite'],
                    'y' => (int) $stat['nombre'],
                    'sliced' => $isFirst,
                    'selected' => $isFirst
                ];
                $isFirst = false; // Désactiver la sélection après le premier élément
            }

            $formattedStats = array_reverse($formattedStats);

            $result = [
                'nombre' => $stats,
                'pieChart' => $formattedStats
            ];
            
            
                        $response = $this->responseData($result, 'group_user', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }
}
