<?php


namespace App\Controller\Apis;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Apis\Config\ApiInterface;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\PaiementService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/api/paiement')]
class ApiPaiementController extends ApiInterface
{


    #[Route('/historique', methods: ['GET'])]
    /**
     * liste historique.
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
    #[OA\Tag(name: 'paiements')]
    // #[Security(name: 'Bearer')]
    public function index(TransactionRepository $transactionRepository): Response
    {
        try {

            $transactions = $transactionRepository->getAllTransaction();

            $response = $this->responseData($transactions, 'group_user', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }
    #[Route('/get/transaction/{trxReference}', methods: ['GET'])]
    /**
     * liste historique.
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
    #[OA\Tag(name: 'paiements')]
    // #[Security(name: 'Bearer')]
    public function getTransaction(TransactionRepository $transactionRepository, $trxReference): Response
    {
        $transaction = $transactionRepository->findOneBy(['reference' => $trxReference, 'state' => 1]);

        if ($transaction) {
            return $this->json(
              [
                "data"=> true
              ]
              );
        }else{
            return $this->json(
            [
              "data"=> false
            ]
            );
        }
    }


    public function numero()
    {

        $query = $this->em->createQueryBuilder();
        $query->select("count(a.id)")
            ->from(Transaction::class, 'a');

        $nb = $query->getQuery()->getSingleScalarResult();
        if ($nb == 0) {
            $nb = 1;
        } else {
            $nb = $nb + 1;
        }
        return ('DEPPS' . date("y", strtotime("now")) . date("m", strtotime("now")) . date("j", strtotime("now")) . str_pad($nb, 3, '0', STR_PAD_LEFT));
    }



    #[Route('/info-paiement', name: 'webhook_paiement',  methods: ['POST'])]
    /**
     * Il s'agit de la webhook pour les paiement.
     */
    #[OA\Post(
        summary: "Authentification admin",
        description: "Génère un token JWT pour les administrateurs.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "codePaiement", type: "string"),
                    new OA\Property(property: "referencePaiement", type: "string"),
                    new OA\Property(property: "code", type: "string"),
                    new OA\Property(property: "moyenPaiement", type: "string"),

                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'paiements')]
    #[Security(name: 'Bearer')]
    public function webHook(Request $request, TransactionRepository $transactionRepository, SessionInterface $session,): Response
    {

        $data = json_decode($request->getContent(), true);
        $transaction = $transactionRepository->findOneBy(['reference' => $data['codePaiement']]);

        $transaction->setReferenceChannel($data['referencePaiement']);
        if ($data['code'] == 200)
            $transaction->setState(1);

        $transaction->setChannel($data['moyenPaiement']);
        $transaction->setData(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $transactionRepository->add($transaction, true);
        /*   $user = $transaction->getUser();

        if ($data['code'] == 200)
            $user->setPayement("payed");

        $user->setData(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $this->userRepository->add($user, true); */

        $session->set('transactionId', $data['referencePaiement']);

        $response = $this->responseData($transaction, 'group_user', ['Content-Type' => 'application/json']);
        return $response;
    }


    #[Route('/initiation/transaction',  methods: ['POST'])]
    /**
     * Permet de créer une transaction et lui on prendra sa reference pour initier le paiement dans code paiement.
     */
    #[OA\Post(
        summary: "",
        description: "Génère un token JWT pour les administrateurs.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "user", type: "string"),
                    new OA\Property(property: "montant", type: "string"),

                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'paiements')]
    #[Security(name: 'Bearer')]
    public function create(Request $request, UserRepository $userRepository, TransactionRepository $transactionRepository): Response
    {

        $data = json_decode($request->getContent(), true);

        $transaction = new Transaction();

        $transaction->setChannel("");
        $transaction->setReference($this->numero());
        $transaction->setMontant($data['montant']);
        $transaction->setReferenceChannel("");
        $transaction->setUser($userRepository->find($data['user']));
        $transaction->setType("Renouvellement");
        $transaction->setState(1);
        $transaction->setCreatedBy($userRepository->find($data['user']));
        $transaction->setUpdatedBy($userRepository->find($data['user']));
        $transaction->setState(0);
        $transaction->setCreatedAtValue(new Date());
        $transaction->setUpdatedAt(new Date());
        $transactionRepository->add($transaction, true);



        $response = $this->responseData($transaction, 'group_user', ['Content-Type' => 'application/json']);

        return $response;
    }



    #[Route('/paiement', name: 'paiement', methods: ['POST'])]
    /**
     * Permet de faire le âiement
     */
    #[OA\Post(
        summary: "",
        description: "Génère un token JWT pour les administrateurs.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nom", type: "string"),
                    new OA\Property(property: "prenoms", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "numero", type: "string"),

                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'paiements')]
    #[Security(name: 'Bearer')]
    public function doPaiement(Request $request, PaiementService $paiementService)
    {
        $result = $paiementService->traiterPaiement($request);

        return $this->json($result);
    }
}
