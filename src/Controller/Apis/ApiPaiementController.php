<?php


namespace App\Controller\Apis;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Apis\Config\ApiInterface;
use App\Entity\TempEtablissement;
use App\Entity\TempProfessionnel;
use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\PaiementService;
use DateTimeImmutable;
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
        $transaction = $transactionRepository->findOneBy(['reference' => $trxReference]);

            return $this->json(
                [
                    "data" => $transaction->getState() == 1 ? true : false
                ]
            );
        
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
    public function webHook(Request $request, TransactionRepository $transactionRepository, SessionInterface $session, PaiementService $paiementService): Response
    {
        $response = $paiementService->methodeWebHook($request);
        return  $this->responseData($response, 'group1', ['Content-Type' => 'application/json']);
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
        $createTransactionData = $paiementService->traiterPaiement($request);
    /* 
        if (!isset($createTransactionData['type'])) {
            return [
                'code' => 400,
                'message' => 'Type de paiement manquant'
            ];
        }
     */
        if ($createTransactionData['type'] == "professionnel") {
            $resultat = $this->createProfessionnelTemp($request, $createTransactionData);
        } else {
            $resultat = $this->createEtablissemntTemp($request, $createTransactionData);
        }
    
        return $resultat;
    }


    public function createProfessionnelTemp(Request $request, $data)
    {

        $names = 'document_' . '01';
        $filePrefix  = str_slug($names);
        $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);
        $professionnel = new TempProfessionnel();

        //etape 1

        $professionnel->setPassword($request->get('password'));
        $professionnel->setEmail($request->get('email'));
        $professionnel->setUsername($request->get('nom') . " " . $this->numero());

        // etatpe 2
        $professionnel->setNumber($request->get('numero'));
        $professionnel->setNom($request->get('nom'));
        $professionnel->setPrenoms($request->get('prenoms'));
        $professionnel->setSpecialite($request->get('specialite'));
        $professionnel->setProfessionnel($request->get('professionnel'));
        $professionnel->setAddress($request->get('address'));
        $professionnel->setEmailPro($request->get('emailPro'));

        // etatpe 3

        $professionnel->setProfession($request->get('profession'));
        $professionnel->setGenre($request->get('genre'));
        $professionnel->setCivilite($request->get('civilite'));
        $professionnel->setVille($request->get('ville'));
        $professionnel->setEmailPro($request->get('adresseEmail'));
        $professionnel->setDateDiplome(new DateTimeImmutable($request->get('dateDiplome')));
        $professionnel->setDateNaissance(new DateTimeImmutable($request->get('dateNaissance')));
        $professionnel->setContactPro($request->get('contactPro'));
        $professionnel->setLieuDiplome($request->get('lieuDiplome'));
        $professionnel->setNationate($request->get('nationate'));
        $professionnel->setSituation($request->get('situation'));
        $professionnel->setDateEmploi(new DateTimeImmutable($request->get('dateEmploi')));
        $professionnel->setLieuResidence($request->get('lieuResidence'));
        $professionnel->setDiplome($request->get('diplome'));
        $professionnel->setSituationPro($request->get('situationPro'));
        $professionnel->setReference($data['reference']);
        $professionnel->setTypeUser(User::TYPE['PROFESSIONNEL']);

        // etatpe 4

        $uploadedPhoto = $request->files->get('photo');
        $uploadedCasier = $request->files->get('casier');
        $uploadedCni = $request->files->get('cni');
        $uploadedDiplome = $request->files->get('diplomeFile');
        $uploadedCertificat = $request->files->get('certificat');
        $uploadedCv = $request->files->get('cv');


        if ($uploadedPhoto) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedPhoto, self::UPLOAD_PATH);
            if ($fichier) {
                $professionnel->setPhoto($fichier);
            }
        }
        if ($uploadedCasier) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCasier, self::UPLOAD_PATH);
            if ($fichier) {
                $professionnel->setCasier($fichier);
            }
        }
        if ($uploadedCni) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCni, self::UPLOAD_PATH);
            if ($fichier) {
                $professionnel->setCni($fichier);
            }
        }
        if ($uploadedDiplome) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedDiplome, self::UPLOAD_PATH);
            if ($fichier) {
                $professionnel->setDiplomeFile($fichier);
            }
        }
        if ($uploadedCertificat) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCertificat, self::UPLOAD_PATH);
            if ($fichier) {
                $professionnel->setCertificat($fichier);
            }
        }
        if ($uploadedCv) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCv, self::UPLOAD_PATH);
            if ($fichier) {
                $professionnel->setCv($fichier);
            }
        }

        // etatpe 5

        $professionnel->setAppartenirOrganisation($request->get('appartenirOrganisation'));
        $professionnel->setNom($request->get('organisationNom'));
        $professionnel->setAnnee($request->get('organisationAnnee'));
        $professionnel->setNumero($request->get('organisationNumero'));

        $errorResponse = $this->errorResponse($professionnel);
        if ($errorResponse !== null) {
            return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
        } else {

            $this->em->persist($professionnel);
            $this->em->flush();
        }


        return  $this->json([
            'message' => 'Professionnel bien enregistré',
            'data' => $data
        ]);
    }
    public function createEtablissemntTemp(Request $request, $data)
    {

        $names = 'document_' . '01';
        $filePrefix  = str_slug($names);
        $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);
        $etablissement = new TempEtablissement();


     


        //etape 1

        $etablissement->setPassword($request->get('password'));
        $etablissement->setEmail($request->get('email'));
        $etablissement->setUsername($request->get('nomEntreprise') . " " . $this->numero());

        $etablissement->setTypePersonne($request->get('typePersonne'));
        $etablissement->setNatureEntreprise($request->get('natureEntreprise'));
        $etablissement->setTypeEntreprise($request->get('typeEntreprise'));
        $etablissement->setGpsEntreprise($request->get('gpsEntreprise'));
        $etablissement->setNatureEntreprise($request->get('niveauEntreprise'));
        $etablissement->setContactEntreprise($request->get('contactEntreprise'));
        $etablissement->setNomEntreprise($request->get('nomEntreprise'));
        $etablissement->setEmailEntreprise($request->get('emailEntreprise'));
        $etablissement->setSpaceEntreprise($request->get('spaceEntreprise'));

        $etablissement->setGenre($request->get('genre'));
        $etablissement->setNomCompletPromoteur($request->get('nomCompletPromoteur'));
        $etablissement->setEmailPro($request->get('emailPro'));
        $etablissement->setProfession($request->get('profession'));
        $etablissement->setContactsPromoteur($request->get('contactsPromoteur'));
        $etablissement->setLieuResidence($request->get('lieuResidence'));
        $etablissement->setNumeroCni($request->get('numeroCni'));


        $etablissement->setNomCompletTechnique($request->get('nomCompletTechnique'));
        $etablissement->setEmailProTechnique($request->get('emailProTechnique'));
        $etablissement->setProfessionTechnique($request->get('professionTechnique'));
        $etablissement->setContactProTechnique($request->get('contactProTechnique'));
        $etablissement->setLieuResidenceTechnique($request->get('lieuResidenceTechnique'));
        $etablissement->setNumeroOrdreTechnique($request->get('numeroOrdreTechnique'));
        $etablissement->setReference($data['reference']);
        $etablissement->setTypeUser(User::TYPE['ETABLISSEMENT']);

        $uploadedPhoto = $request->files->get('photo');
        $uploadedCni = $request->files->get('cni');
        $uploadedCv = $request->files->get('cv');
        $uploadedDiplome = $request->files->get('diplomeFile');
        $uploadeOrdinal = $request->files->get('ordreNational');
        $uploadedDfe = $request->files->get('dfe');


        if ($uploadedPhoto) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedPhoto, self::UPLOAD_PATH);
            if ($fichier) {
                $etablissement->setPhoto($fichier);
            }
        }
        if ($uploadedCni) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCni, self::UPLOAD_PATH);
            if ($fichier) {
                $etablissement->setCni($fichier);
            }
        }
        if ($uploadedCv) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCv, self::UPLOAD_PATH);
            if ($fichier) {
                $etablissement->setCv($fichier);
            }
        }
        if ($uploadedDiplome) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedDiplome, self::UPLOAD_PATH);
            if ($fichier) {
                $etablissement->setDiplomeFile($fichier);
            }
        }
        if ($uploadeOrdinal) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadeOrdinal, self::UPLOAD_PATH);
            if ($fichier) {
                $etablissement->setOrdreNational($fichier);
            }
        }
        if ($uploadedDfe) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedDfe, self::UPLOAD_PATH);
            if ($fichier) {
                $etablissement->setDfe($fichier);
            }
        }

        $errorResponse = $this->errorResponse($etablissement);
        if ($errorResponse !== null) {
            return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
        } else {

            $this->em->persist($etablissement);
            $this->em->flush();
        }


        return  $this->json([
            'message' => 'Professionnel bien enregistré',
            'data' => $data
        ]);
    }
}
