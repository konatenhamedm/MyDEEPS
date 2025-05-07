<?php


namespace App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\ActiveProfessionnelRequest;
use App\Entity\Etablissement;
use App\Entity\Organisation;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EtablissementRepository;
use App\Entity\User;
use App\Repository\CiviliteRepository;
use App\Repository\GenreRepository;
use App\Repository\OrganisationRepository;
use App\Repository\PaysRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Repository\TypePersonneRepository;
use App\Service\SendMailService;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/etablissement')]
class ApiEtablissementController extends ApiInterface
{

    #[Route('/active/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Accepter ou refuser un etablissement",
        description: "Permet d'accepter ou de refuser un etablissement.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "raison", type: "string", nullable: true)
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 400, description: "Données invalides"),
            new OA\Response(response: 404, description: "Professionnel non trouvé"),
            new OA\Response(response: 200, description: "Mise à jour réussie")
        ]
    )]
    #[OA\Tag(name: 'etablissement')]
    public function active(
        Request $request,
        Etablissement $etablissement,
        EtablissementRepository $etablissementlRepository,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        Registry $workflowRegistry,
        SendMailService $sendMailService  // Injecter le Registry
    ): Response {
        try {


            $data = json_decode($request->getContent(), true);

            $user = $userRepository->find($etablissement->getId());

            $dto = new ActiveProfessionnelRequest();
            $dto->status = $data['status'] ?? null;
            $dto->raison = $data['raison'] ?? null;

            $errors = $validator->validate($dto);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $validationCompteWorkflow = $workflowRegistry->get($etablissement);

            // Vérifier la transition du workflow
            if (!$validationCompteWorkflow->can($etablissement, $dto->status)) {
                return new JsonResponse([
                    'error' => "Transition non valide depuis l'état actuel"
                ], Response::HTTP_BAD_REQUEST);
            }

            $validationCompteWorkflow->apply($etablissement, $dto->status);

            $etablissement->setReason($dto->raison);
            $etablissementlRepository->add($etablissement, true);

            $info_user = [
                'user' => $userRepository->find($data['userUpdate'])->getUsername(),
                'etape' => $dto->status,
            ];

            $context = compact('info_user');

            // TO DO
            $sendMailService->send(
                'tester@myonmci.ci',
                $data['email'],
                'Validaton du dossier',
                'content_validation',
                $context
            );


            return $this->responseData($etablissement, 'group_pro', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            return $this->json(["message" => "Une erreur est survenue"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    #[Route('/create',  methods: ['POST'])]
    /**
     * Permet de créer un(e) etablissement.
     */
    #[OA\Post(
        summary: "Creation de etablissement",
        description: "Permet de crtéer d'un etablissement.",

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [

                        // Informations utilisateur
                        new OA\Property(property: "password", type: "string"),
                        new OA\Property(property: "confirmPassword", type: "string"),
                        new OA\Property(property: "email", type: "string"),

                        // Informations sur l'entreprise
                        new OA\Property(property: "typePersonne", type: "string"),
                        new OA\Property(property: "natureEntreprise", type: "string"),
                        new OA\Property(property: "typeEntreprise", type: "string"),
                        new OA\Property(property: "gpsEntreprise", type: "string"),
                        new OA\Property(property: "niveauEntreprise", type: "string"),
                        new OA\Property(property: "contactEntreprise", type: "string"),
                        new OA\Property(property: "nomEntreprise", type: "string"),
                        new OA\Property(property: "emailEntreprise", type: "string"),
                        new OA\Property(property: "spaceEntreprise", type: "string"),

                        // Informations du promoteur
                        new OA\Property(property: "genre", type: "string"),
                        new OA\Property(property: "nomCompletPromoteur", type: "string"),
                        new OA\Property(property: "emailPro", type: "string"),
                        new OA\Property(property: "profession", type: "string"),
                        new OA\Property(property: "contactsPromoteur", type: "string"),
                        new OA\Property(property: "lieuResidence", type: "string"),
                        new OA\Property(property: "numeroCni", type: "string"),

                        // Informations du responsable technique
                        new OA\Property(property: "nomCompletTechnique", type: "string"),
                        new OA\Property(property: "emailProTechnique", type: "string"),
                        new OA\Property(property: "professionTechnique", type: "string"),
                        new OA\Property(property: "contactProTechnique", type: "string"),
                        new OA\Property(property: "lieuResidenceTechnique", type: "string"),
                        new OA\Property(property: "numeroOrdreTechnique", type: "string"),
                        new OA\Property(property: "reference", type: "string"),
                        // Documents (fichiers en binaire)
                        new OA\Property(property: "photo", type: "string", format: "binary"),
                        new OA\Property(property: "cni", type: "string", format: "binary"),
                        new OA\Property(property: "dfe", type: "string", format: "binary"),
                        new OA\Property(property: "diplomeFile", type: "string", format: "binary"),
                        new OA\Property(property: "ordreNational", type: "string", format: "binary"),
                        new OA\Property(property: "cv", type: "string", format: "binary"),


                    ],
                    type: "object"
                )
            )

        ),


        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'etablissement')]
    #[Security(name: 'Bearer')]
    public function create(UserPasswordHasherInterface $hasher, Request $request, SessionInterface $session, SendMailService $sendMailService, TransactionRepository $transactionRepository, GenreRepository $genreRepository, EtablissementRepository $etablissementRepository, TypePersonneRepository $typePersonneRepository): Response
    {

        $names = 'document_' . '01';
        $filePrefix  = str_slug($names);
        $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);

        //dd($request->get('dateDiplome'));

        $transaction = $transactionRepository->findOneBy(['reference' =>  $request->get('reference'), 'user' => null]);

        if (!$transaction) {
            return $this->response("Transaction introuvable");
        } else {


            $user = new User();
            $user->setUsername($request->get('nomEntreprise') . " " . $this->numero());
            $user->setEmail($request->get('email'));
            $plainPassword = $request->get('password');


            $user->setPassword($hasher->hashPassword($user, $plainPassword));
            // $user->setPassword("test");
            $user->setRoles(['ROLE_MEMBRE']);
            $user->setTypeUser(User::TYPE['ETABLISSEMENT']);
            $user->setPayement(User::PAYEMENT['init_payement']);


            $errorResponse1 = $request->get('password') !== $request->get('confirmPassword') ?  $this->errorResponse($user, "Les mots de passe ne sont pas identiques") :  $this->errorResponse($user);
            if ($errorResponse1 !== null) {
                return $errorResponse1; // Retourne la réponse d'erreur si des erreurs sont présentes
            } else {


                $etablissement = new Etablissement();


                // Informations générales
                /*  $etablissement->setTypePersonne($typePersonneRepository->find($request->get('typePersonne'))); 
            $etablissement->setNatureEntreprise($request->get('natureEntreprise'));
            $etablissement->setTypeEntreprise($request->get('typeEntreprise'));
            $etablissement->setGpsEntreprise($request->get('gpsEntreprise'));
            $etablissement->setNiveauEntreprise($request->get('niveauEntreprise'));
            $etablissement->setContactEntreprise($request->get('contactEntreprise'));
            $etablissement->setNomEntreprise($request->get('nomEntreprise'));
            $etablissement->setEmailEntreprise($request->get('emailEntreprise'));
            $etablissement->setSpaceEntreprise($request->get('spaceEntreprise'));
            $etablissement->setAppartenirOrganisation('non');
            $etablissement->setStatus('attente');

            // Promoteur
            $etablissement->setGenre($genreRepository->find($request->get('genre')));
            $etablissement->setNomCompletPromoteur($request->get('nomCompletPromoteur'));
            $etablissement->setEmailPro($request->get('emailPro'));
            $etablissement->setProfession($request->get('profession'));
            $etablissement->setContactsPromoteur($request->get('contactsPromoteur'));
            $etablissement->setLieuResidence($request->get('lieuResidence'));
            $etablissement->setNumeroCni($request->get('numeroCni'));

            // Technicien
            $etablissement->setNomCompletTechnique($request->get('nomCompletTechnique'));
            $etablissement->setEmailProTechnique($request->get('emailProTechnique'));
            $etablissement->setProfessionTechnique($request->get('professionTechnique'));
            $etablissement->setContactProTechnique($request->get('contactProTechnique'));
            $etablissement->setLieuResidenceTechnique($request->get('lieuResidenceTechnique'));
            $etablissement->setNumeroOrdreTechnique($request->get('numeroOrdreTechnique')); */

                // Documents
                $uploadedPhoto = $request->files->get('photo'); // 'photoRespo' correspond à 'photo'
                $uploadedOrdreNational = $request->files->get('ordreNational');
                $uploadedCni = $request->files->get('cni');
                $uploadedDiplome = $request->files->get('diplomeFile');
                $uploadedCv = $request->files->get('cv');
                $uploadedDfe = $request->files->get('dfe');



                /* if ($uploadedPhoto) {
                $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedPhoto, self::UPLOAD_PATH);
                if ($fichier) {
                    $etablissement->setPhoto($fichier);
                }
            }
            if ($uploadedOrdreNational) {
                $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedOrdreNational, self::UPLOAD_PATH);
                if ($fichier) {
                    $etablissement->setOrdreNational($fichier);
                }
            }
            if ($uploadedCni) {
                $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCni, self::UPLOAD_PATH);
                if ($fichier) {
                    $etablissement->setCni($fichier);
                }
            }
            if ($uploadedDiplome) {
                $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedDiplome, self::UPLOAD_PATH);
                if ($fichier) {
                    $etablissement->setDiplomeFile($fichier);
                }
            }
            if ($uploadedDfe) {
                $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedDfe, self::UPLOAD_PATH);
                if ($fichier) {
                    $etablissement->setDfe($fichier);
                }
            }
            if ($uploadedCv) {
                $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCv, self::UPLOAD_PATH);
                if ($fichier) {
                    $etablissement->setCv($fichier);
                }
            } */

                // $etablissement->setUser($user);


                $etablissement->setCreatedBy($user);
                $etablissement->setUpdatedBy($user);

                $errorResponse = $this->errorResponse($etablissement);
                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {

                    $this->userRepository->add($user, true);

                    $etablissementRepository->add($etablissement, true);


                    $info_user = [
                        'login' => $request->get('email'),
                        'password' => $request->get('confirmPassword')
                    ];

                    $context = compact('info_user');

                    // TO DO
                    $sendMailService->send(
                        'tester@myonmci.ci',
                        $request->get('email'),
                        'Informations',
                        'content_mail',
                        $context
                    );


                    if ($transaction) {
                        $transaction->setUser($user);
                        $transaction->setCreatedBy($user);
                        $transaction->setUpdatedBy($user);
                        $transactionRepository->add($transaction, true);


                        $user->setPayement(User::PAYEMENT['payed']);
                        $this->userRepository->add($user, true);
                    }
                }
            }
        }

        return $this->responseData($etablissement, 'group_pro', ['Content-Type' => 'application/json']);
    }


    #[Route('/', methods: ['GET'])]
    /**
     * Retourne la liste des etablissements.
     * 
     */
    #[OA\Response(
        response: 200,
        description: ' Retourne la liste des etablissements',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Etablissement::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'etablissement')]
    // #[Security(name: 'Bearer')]
    public function index(EtablissementRepository $etablissementRepository, UserRepository $userRepository): Response
    {

        try {
            /* $etablissements = $etablissementRepository->findAll(); */

            $etablissements = $userRepository->findBy(['typeUser' => 'ETABLISSEMENT']);

            $response = $this->responseData($etablissements, 'group_pro', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }






    #[Route('/get/one/{id}', methods: ['GET'])]
    /**
     * Affiche un(e) Etablissement en offrant un identifiant.
     */
    #[OA\Response(
        response: 200,
        description: 'Affiche un etablissement en offrant un identifiant',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Etablissement::class, groups: ['full']))
        )
    )]
    #[OA\Parameter(
        name: 'code',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'etablissement')]
    //#[Security(name: 'Bearer')]
    public function getOne(EtablissementRepository $etablissementRepository, Etablissement $etablissement)
    {
        try {


            if ($etablissement) {
                $response = $this->responseData($etablissement, 'group_pro', ['Content-Type' => 'application/json']);
            } else {
                $this->setMessage('Cette ressource est inexistante');
                $this->setStatusCode(300);
            }
        } catch (\Exception $exception) {
            $this->setMessage($exception->getMessage());
            $response = $this->response('[]');
        }


        return $response;
    }


    private function numero()
    {

        $query = $this->em->createQueryBuilder();
        $query->select("count(a.id)")
            ->from(User::class, 'a');

        $nb = $query->getQuery()->getSingleScalarResult();
        if ($nb == 0) {
            $nb = 1;
        } else {
            $nb = $nb + 1;
        }
        return str_pad($nb, 3, '0', STR_PAD_LEFT);
    }


    #[Route('/update/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Update de etablissement",
        description: "update d'un etablissement.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [



                        new OA\Property(property: "typePersonne", type: "string"),
                        new OA\Property(property: "natureEntreprise", type: "string"),
                        new OA\Property(property: "typeEntreprise", type: "string"),
                        new OA\Property(property: "gpsEntreprise", type: "string"),
                        new OA\Property(property: "niveauEntreprise", type: "string"),
                        new OA\Property(property: "contactEntreprise", type: "string"),
                        new OA\Property(property: "nomEntreprise", type: "string"),
                        new OA\Property(property: "emailEntreprise", type: "string"),
                        new OA\Property(property: "spaceEntreprise", type: "string"),

                        // Informations du promoteur
                        new OA\Property(property: "genre", type: "string"),
                        new OA\Property(property: "nomCompletPromoteur", type: "string"),
                        new OA\Property(property: "emailPro", type: "string"),
                        new OA\Property(property: "profession", type: "string"),
                        new OA\Property(property: "contactsPromoteur", type: "string"),
                        new OA\Property(property: "lieuResidence", type: "string"),
                        new OA\Property(property: "numeroCni", type: "string"),

                        // Informations du responsable technique
                        new OA\Property(property: "nomCompletTechnique", type: "string"),
                        new OA\Property(property: "emailProTechnique", type: "string"),
                        new OA\Property(property: "professionTechnique", type: "string"),
                        new OA\Property(property: "contactProTechnique", type: "string"),
                        new OA\Property(property: "lieuResidenceTechnique", type: "string"),
                        new OA\Property(property: "numeroOrdreTechnique", type: "string"),

                        // Documents (fichiers en binaire)
                        new OA\Property(property: "photo", type: "string", format: "binary"),
                        new OA\Property(property: "cni", type: "string", format: "binary"),
                        new OA\Property(property: "dfe", type: "string", format: "binary"),
                        new OA\Property(property: "diplomeFile", type: "string", format: "binary"),
                        new OA\Property(property: "ordreNational", type: "string", format: "binary"),
                        new OA\Property(property: "cv", type: "string", format: "binary"),







                    ],
                    type: "object"
                )
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'etablissement')]
    #[Security(name: 'Bearer')]
    public function update(Request $request, Etablissement $etablissement, GenreRepository $genreRepository, EtablissementRepository $etablissementlRepository): Response
    {
        try {
            $names = 'document_' . '01';
            $filePrefix  = str_slug($names);
            $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);


            /* if ($etablissement) {

                $etablissement->setTypePersonne($request->get('typePersonne'));
                $etablissement->setNatureEntreprise($request->get('natureEntreprise'));
                $etablissement->setTypeEntreprise($request->get('typeEntreprise'));
                $etablissement->setGpsEntreprise($request->get('gpsEntreprise'));
                $etablissement->setNiveauEntreprise($request->get('niveauEntreprise'));
                $etablissement->setContactEntreprise($request->get('contactEntreprise'));
                $etablissement->setNomEntreprise($request->get('nomEntreprise'));
                $etablissement->setEmailEntreprise($request->get('emailEntreprise'));
                $etablissement->setSpaceEntreprise($request->get('spaceEntreprise'));

                // Promoteur
                $etablissement->setGenre($genreRepository->find($request->get('genre')));
                $etablissement->setNomCompletPromoteur($request->get('nomCompletPromoteur'));
                $etablissement->setEmailPro($request->get('emailPro'));
                $etablissement->setProfession($request->get('profession'));
                $etablissement->setContactsPromoteur($request->get('contactsPromoteur'));
                $etablissement->setLieuResidence($request->get('lieuResidence'));
                $etablissement->setNumeroCni($request->get('numeroCni'));

                // Technicien
                $etablissement->setNomCompletTechnique($request->get('nomCompletTechnique'));
                $etablissement->setEmailProTechnique($request->get('emailProTechnique'));
                $etablissement->setProfessionTechnique($request->get('professionTechnique'));
                $etablissement->setContactProTechnique($request->get('contactProTechnique'));
                $etablissement->setLieuResidenceTechnique($request->get('lieuResidenceTechnique'));
                $etablissement->setNumeroOrdreTechnique($request->get('numeroOrdreTechnique'));

                // Documents
                $uploadedPhoto = $request->files->get('photo'); // 'photoRespo' correspond à 'photo'
                $uploadedOrdreNational = $request->files->get('ordreNational');
                $uploadedCni = $request->files->get('cni');
                $uploadedDiplome = $request->files->get('diplomeFile');
                $uploadedCv = $request->files->get('cv');
                $uploadedDfe = $request->files->get('dfe');


                if ($uploadedPhoto) {
                    $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedPhoto, self::UPLOAD_PATH);
                    if ($fichier) {
                        $etablissement->setPhoto($fichier);
                    }
                }
                if ($uploadedOrdreNational) {
                    $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedOrdreNational, self::UPLOAD_PATH);
                    if ($fichier) {
                        $etablissement->setOrdreNational($fichier);
                    }
                }
                if ($uploadedCni) {
                    $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCni, self::UPLOAD_PATH);
                    if ($fichier) {
                        $etablissement->setCni($fichier);
                    }
                }
                if ($uploadedDiplome) {
                    $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedDiplome, self::UPLOAD_PATH);
                    if ($fichier) {
                        $etablissement->setDiplomeFile($fichier);
                    }
                }
                if ($uploadedDfe) {
                    $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedDfe, self::UPLOAD_PATH);
                    if ($fichier) {
                        $etablissement->setDfe($fichier);
                    }
                }
                if ($uploadedCv) {
                    $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedCv, self::UPLOAD_PATH);
                    if ($fichier) {
                        $etablissement->setCv($fichier);
                    }
                }

               /*  $user = $this->userRepository->find($request->get('user'));
                $etablissement->setUser($user);


                $etablissement->setCreatedBy($this->userRepository->find($request->get('userUpdate')));
                $etablissement->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));

                $errorResponse = $this->errorResponse($etablissement);




                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {
                    $etablissementlRepository->add($etablissement, true);
                } 
                $response = $this->responseData($etablissement, 'group_pro', ['Content-Type' => 'application/json']);
            } */
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }
        return $response;
    }

    #[Route('/delete/{id}',  methods: ['DELETE'])]
    /**
     * permet de supprimer un(e) etablissement.
     */
    #[OA\Response(
        response: 200,
        description: 'permet de supprimer un(e) etablissement',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Etablissement::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'etablissement')]
    //#[Security(name: 'Bearer')]
    public function delete(Request $request, Etablissement $etablissement, EtablissementRepository $etablissementRepository): Response
    {
        try {

            if ($etablissement != null) {

                $etablissementRepository->remove($etablissement, true);

                // On retourne la confirmation
                $this->setMessage("Operation effectuées avec success");
                $response = $this->response($etablissement);
            } else {
                $this->setMessage("Cette ressource est inexistante");
                $this->setStatusCode(300);
                $response = $this->response('[]');
            }
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }
        return $response;
    }
}
