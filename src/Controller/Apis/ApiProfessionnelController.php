<?php


namespace App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\ActiveProfessionnelRequest;
use App\DTO\ProfessionnelDTO;
use App\Entity\Civilite;
use App\Entity\Etablissement;
use App\Entity\Organisation;
use App\Entity\Profession;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Professionnel;
use App\Entity\SituationProfessionnelle;
use App\Entity\User;
use App\Entity\ValidationWorkflow;
use App\Repository\CiviliteRepository;
use App\Repository\CommuneRepository;
use App\Repository\DistrictRepository;
use App\Repository\GenreRepository;
use App\Repository\OrganisationRepository;
use App\Repository\PaysRepository;
use App\Repository\ProfessionnelRepository;
use App\Repository\ProfessionRepository;
use App\Repository\RacineSequenceRepository;
use App\Repository\RegionRepository;
use App\Repository\SituationProfessionnelleRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use App\Service\PaiementService;
use App\Service\SendMailService;
use DateTime;
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

#[Route('/api/professionnel')]
class ApiProfessionnelController extends ApiInterface
{


    #[Route('/', methods: ['GET'])]
    /**
     * Retourne la liste des professionnels.
     * 
     */
    #[OA\Response(
        response: 200,
        description: ' Retourne la liste des professionnels',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Professionnel::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'professionnel')]
    // #[Security(name: 'Bearer')]
    public function index(ProfessionnelRepository $professionnelRepository, UserRepository $userRepository, ProfessionRepository $professionRepository): Response
    {

        try {
            $professionnels = $userRepository->findBy(['typeUser' => 'PROFESSIONNEL'], ['id' => 'DESC']);
            $professionnels = $userRepository->findBy(['typeUser' => 'PROFESSIONNEL'], ['id' => 'DESC']);

            $formattedProfessionnels = array_map(function ($professionnel) use ($professionRepository) {
                return [
                    'username' => $professionnel->getUsername(),
                    'id' => $professionnel->getId(),
                    'email' => $professionnel->getEmail(),
                    'typeUser' => $professionnel->getTypeUser(),
                    'personne' => [
                        'profession' => $professionnel->getPersonne()->getProfession() ? [
                            'libelle' =>  $professionRepository->findOneByCode($professionnel->getPersonne()->getProfession())->getLibelle() ??"",
                            'id' => $professionRepository->findOneByCode($professionnel->getPersonne()->getProfession())->getId(),
                            'montantNouvelleDemande' => $professionRepository->findOneByCode($professionnel->getPersonne()->getProfession())->getMontantNouvelleDemande(),
                            'montantRenouvellement'=> $professionRepository->findOneByCode($professionnel->getPersonne()->getProfession())->getMontantRenouvellement()

                        ] : null,
                        'nom' => $professionnel->getPersonne()->getNom(),
                        'lieuDiplome' => $professionnel->getPersonne()->getLieuDiplome(),
                        'code' => $professionnel->getPersonne()->getCode(),
                        'prenoms' => $professionnel->getPersonne()->getPrenoms(),
                        'number' => $professionnel->getPersonne()->getNumber(),
                        'email' => $professionnel->getPersonne()->getEmail(),
                        'type' => "professionnel",
                        'status' => $professionnel->getPersonne()->getStatus(),

                        'reason' => $professionnel->getPersonne()->getReason() ?? "",
                        'professionnel' => $professionnel->getPersonne()->getProfessionnel() ?? "",
                        'civilite' => $professionnel->getPersonne()->getCivilite() ? $professionnel->getPersonne()->getCivilite()->getLibelle() : "",
                        'nationate' => $professionnel->getPersonne()->getNationate() ? $professionnel->getPersonne()->getNationate()->getLibelle() : "",
                        'dateNaissance' => $professionnel->getPersonne()->getDateNaissance() ? $professionnel->getPersonne()->getDateNaissance()->format('Y-m-d') : "",
                        'dateDiplome' => $professionnel->getPersonne()->getDateDiplome() ? $professionnel->getPersonne()->getDateDiplome()->format('Y-m-d') : "",
                        'diplome' => $professionnel->getPersonne()->getDiplome() ?? "",
                        'poleSanitaire' => $professionnel->getPersonne()->getPoleSanitaire() ?? "",
                        'organisationNom' => $professionnel->getPersonne()->getOrganisationNom() ?? "",
                        'poleSanitairePro' => $professionnel->getPersonne()->getPoleSanitairePro() ?? "",
                        'lieuExercicePro' => $professionnel->getPersonne()->getLieuExercicePro() ?? "",
                        'datePremierDiplome' => $professionnel->getPersonne()->getDatePremierDiplome() ? $professionnel->getPersonne()->getDatePremierDiplome()->format('Y-m-d') : "",
                        'situationPro' => $professionnel->getPersonne()->getSituationPro() ? $professionnel->getPersonne()->getSituationPro()->getLibelle() : "",
                        'situation' => $professionnel->getPersonne()->getSituation() ?? "",
                        'appartenirOrganisation' => $professionnel->getPersonne()->getAppartenirOrganisation() ?? "",

                        'photo' => $professionnel->getPersonne()->getPhoto() ? [
                            'path' => $professionnel->getPersonne()->getPhoto()->getPath(),
                            'alt' => $professionnel->getPersonne()->getPhoto()->getAlt()
                        ] : null,

                        'cv' => $professionnel->getPersonne()->getCv() ? [
                            'path' => $professionnel->getPersonne()->getCv()->getPath(),
                            'alt' => $professionnel->getPersonne()->getCv()->getAlt()
                        ] : null,

                        'casier' => $professionnel->getPersonne()->getCasier() ? [
                            'path' => $professionnel->getPersonne()->getCasier()->getPath(),
                            'alt' => $professionnel->getPersonne()->getCasier()->getAlt()
                        ] : null,

                        'certificat' => $professionnel->getPersonne()->getCertificat() ? [
                            'path' => $professionnel->getPersonne()->getCertificat()->getPath(),
                            'alt' => $professionnel->getPersonne()->getCertificat()->getAlt()
                        ] : null,

                        'diplomeFile' => $professionnel->getPersonne()->getDiplomeFile() ? [
                            'path' => $professionnel->getPersonne()->getDiplomeFile()->getPath(),
                            'alt' => $professionnel->getPersonne()->getDiplomeFile()->getAlt()
                        ] : null,

                        'cni' => $professionnel->getPersonne()->getCni() ? [
                            'path' => $professionnel->getPersonne()->getCni()->getPath(),
                            'alt' => $professionnel->getPersonne()->getCni()->getAlt()
                        ] : null,
                    ]

                ];
            }, $professionnels);

            // Pour retourner en JSON (dans un contrôleur Symfony par exemple)

            $response = $this->responseData($formattedProfessionnels, 'group_pro', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }


    #[Route('/{status}', methods: ['GET'])]
    /**
     * Retourne la liste des professionnels par status.
     * 
     */
    #[OA\Response(
        response: 200,
        description: ' Retourne la liste des professionnels',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Professionnel::class, groups: ['full']))
        )
    )]

    #[OA\Tag(name: 'professionnel')]
    // #[Security(name: 'Bearer')]
    public function indexEtat(ProfessionnelRepository $professionnelRepository, $status): Response
    {
        try {

            $professionnels = $professionnelRepository->getProfessionnelByetat($status);
            $response = $this->responseData($professionnels, 'group_pro', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }

    public function numeroGeneration($professionnel, $professionCode, $racine)
    {

        // MS2025APDENT2978.0045
        // MS1025APDENT2025.0035

        $civilite = $professionnel->getCivilite()->getCodeGeneration();
        $anneeInscription = $professionnel->getCreatedAt()->format('y');
        $jour = $professionnel->getDateNaissance()->format('d');
        $annee = $professionnel->getDateNaissance()->format('y');

        $query = $this->em->createQueryBuilder();
        $query->select("count(a.id)")
            ->from(Professionnel::class, 'a');

        $nb = $query->getQuery()->getSingleScalarResult();
        return ($racine . $civilite . "0" . $anneeInscription . $professionCode . $jour . $annee . "." . str_pad($nb + 1, 4, '0', STR_PAD_LEFT));
    }



    #[Route('/active/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Accepter ou refuser un professionnel",
        description: "Permet d'accepter ou de refuser un professionnel.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "raison", type: "string", nullable: true),
                    new OA\Property(property: "email", type: "string", nullable: true),
                    new OA\Property(property: "userUpdate", type: "string", nullable: true)
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
    #[OA\Tag(name: 'professionnel')]
    public function active(
        Request $request,
        Professionnel $professionnel,
        ProfessionnelRepository $professionnelRepository,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        Registry $workflowRegistry,
        ProfessionRepository $professionRepository,
        RacineSequenceRepository $racineSequenceRepository,
        SendMailService $sendMailService // Injecter le Registry
    ): Response {
        try {


            $data = json_decode($request->getContent(), true);

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

            $validationCompteWorkflow = $workflowRegistry->get($professionnel);

            // Vérifier la transition du workflow
            if (!$validationCompteWorkflow->can($professionnel, $dto->status)) {
                return new JsonResponse([
                    'error' => "Transition non valide depuis l'état actuel"
                ], Response::HTTP_BAD_REQUEST);
            }

            $validationCompteWorkflow->apply($professionnel, $dto->status);
            if ($dto->status == "validation") {

                $professionCode = $professionRepository->findOneBy(['code' => $professionnel->getProfession()])->getCodeGeneration();

                $professionnel->setCode($this->numeroGeneration($professionnel, $professionCode, $racineSequenceRepository->findOneBySomeField()->getCode()));
            }
            $professionnel->setReason($dto->raison);
            $professionnelRepository->add($professionnel, true);
            $validationWorkflow = new ValidationWorkflow();
            $validationWorkflow->setEtape($dto->status);
            $validationWorkflow->setRaison($dto->raison);
            $validationWorkflow->setPersonne($professionnel);
            $validationWorkflow->setCreatedAtValue(new DateTime());
            $validationWorkflow->setUpdatedAt(new DateTime());
            $validationWorkflow->setCreatedBy($userRepository->find($data['userUpdate']));
            $validationWorkflow->setUpdatedBy($userRepository->find($data['userUpdate']));

            $this->em->persist($validationWorkflow);
            $this->em->flush();

            $info_user = [
                'user' => $userRepository->find($data['userUpdate'])->getUsername(),
                'etape' => $dto->status,
            ];

            $context = compact('info_user');

            // TO DO

            if ($data['email'] != "") {
                $sendMailService->send(
                    'test@myonmci.ci',
                    $data['email'],
                    'Validaton du dossier',
                    'content_validation',
                    $context
                );
            }


            $sendMailService->sendNotification("votre compte vient d'être valider pour l'etape " . $dto->status, $userRepository->findOneBy(['personne' => $professionnel->getId()]), $userRepository->find($data['userUpdate']));

            return $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            return $this->json(["message" => "Une erreur est survenue"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/get/one/{id}', methods: ['GET'])]
    /**
     * Affiche un(e) professionnel en offrant un identifiant.
     */
    #[OA\Response(
        response: 200,
        description: 'Affiche un(e) professionnel en offrant un identifiant',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Professionnel::class, groups: ['full']))
        )
    )]
    #[OA\Parameter(
        name: 'code',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'professionnel')]
    //#[Security(name: 'Bearer')]
    public function getOne(ProfessionnelRepository $professionnelRepository, Professionnel $professionnel)
    {
        try {

            if ($professionnel) {
                $response = $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
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


    #[Route('/create', name: 'create_professionnel', methods: ['POST'])]
    /**
     * Permet de créer un(e) professionnel.
     */
    #[OA\Post(
        summary: "Creation de professionnel",
        description: "Permet de crtéer un professionnel.",

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        // etatpe 1
                        new OA\Property(property: "password", type: "string"), // username
                        new OA\Property(property: "confirmPassword", type: "string"), // username
                        new OA\Property(property: "email", type: "string"),


                        // etatpe 2


                        new OA\Property(property: "poleSanitaire", type: "string"), //pole sanitaire
                        new OA\Property(property: "region", type: "string"), //pole sanitaire
                        new OA\Property(property: "district", type: "string"), //pole sanitaire
                        new OA\Property(property: "ville", type: "string"), //pole sanitaire
                        new OA\Property(property: "commune", type: "string"), //pole sanitaire
                        new OA\Property(property: "quartier", type: "string"), //pole sanitaire

                        new OA\Property(property: "nom", type: "string"), //first_name 
                        new OA\Property(property: "professionnel", type: "string"), //professionnel
                        new OA\Property(property: "prenoms", type: "string"),
                        new OA\Property(property: "lieuExercicePro", type: "string"), //lieu_exercice_pro
                        new OA\Property(property: "emailAutre", type: "string"), //email

                        // etatpe 3

                        new OA\Property(property: "profession", type: "string"), //profession bouton radio
                        new OA\Property(property: "civilite", type: "string"), //civilite select
                        new OA\Property(property: "emailPro", type: "string"), //email_pro
                        new OA\Property(property: "dateDiplome", type: "string"), //dateDiplome date
                        new OA\Property(property: "dateNaissance", type: "string"), //dateNaissance date
                        new OA\Property(property: "numero", type: "string"), //contact
                        new OA\Property(property: "lieuDiplome", type: "string"), //lieu au obtention premier diplome
                        new OA\Property(property: "nationalite", type: "string"), //nationalite select
                        new OA\Property(property: "situation", type: "string"), //situation matrimonial
                        new OA\Property(property: "datePremierDiplome", type: "string"), //datePremierDiplome
                        new OA\Property(property: "poleSanitairePro", type: "string"), //contactPerso
                        new OA\Property(property: "diplome", type: "string"), //diplome
                        new OA\Property(property: "situationPro", type: "string"), //situation_pro


                        // etatpe 4


                        new OA\Property(property: "photo", type: "string", format: "binary"), //photo
                        new OA\Property(property: "cni", type: "string", format: "binary"), //cni
                        new OA\Property(property: "casier", type: "string", format: "binary"), //casier
                        new OA\Property(property: "diplomeFile", type: "string", format: "binary"), //diplomeFile
                        new OA\Property(property: "certificat", type: "string", format: "binary"), //certificat
                        new OA\Property(property: "cv", type: "string", format: "binary"), //cv

                        // etatpe 5


                        new OA\Property(property: "appartenirOrganisation", type: "boolean"), // oui ou non
                        new OA\Property(property: "organisationNom", type: "string"),
                        /*  new OA\Property(property: "organisationNumero", type: "string"),
                        new OA\Property(property: "organisationAnnee", type: "string"), */
                        new OA\Property(property: "reference", type: "string"),


                    ],
                    type: "object"
                )
            )

        ),


        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'professionnel')]
    #[Security(name: 'Bearer')]
    public function create(
        Request $request,
        SessionInterface $session,
        SendMailService $sendMailService,
        TransactionRepository $transactionRepository,
        VilleRepository $villeRepository,
        CiviliteRepository $civiliteRepository,
        SpecialiteRepository $specialiteRepository,
        GenreRepository $genreRepository,
        ProfessionnelRepository $professionnelRepository,
        PaysRepository $paysRepository,
        OrganisationRepository $organisationRepository,
        PaiementService $paiementService,
        SituationProfessionnelleRepository $situationProfessionnelleRepository,
        RegionRepository $regionRepository,
        DistrictRepository $districtRepository,
        CommuneRepository $communeRepository
    ): Response {


        $names = 'document_' . '01';
        $filePrefix  = str_slug($names);
        $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);



        $user = new User();
        $user->setUsername($request->get('nom') . " " . $paiementService->genererNumero());
        $user->setEmail($request->get('email'));
        $user->setPassword($this->hasher->hashPassword($user, $request->get('password')));
        $user->setRoles(['ROLE_MEMBRE']);
        $user->setTypeUser(User::TYPE['PROFESSIONNEL']);
        $user->setPayement(User::PAYEMENT['payed']);
        $user->setUpdatedAt(new DateTime());
        $user->setCreatedAtValue(new DateTime());


        $errorResponse1 = $request->get('password') !== $request->get('confirmPassword') ?  $this->errorResponse($user, "Les mots de passe ne sont pas identiques") :  $this->errorResponse($user);
        if ($errorResponse1 !== null) {
            return $errorResponse1; // Retourne la réponse d'erreur si des erreurs sont présentes
        } else {

            $this->userRepository->add($user, true);

            $professionnel = new Professionnel();

            //ETAPE 2

            $professionnel->setPoleSanitaire($request->get('poleSanitaire'));
            $professionnel->setRegion($regionRepository->find($request->get('region')));
            $professionnel->setDistrict($districtRepository->find($request->get('district')));
            $professionnel->setVille($villeRepository->find($request->get('ville')));
            $professionnel->setCommune($communeRepository->find($request->get('commune')));
            $professionnel->setQuartier($request->get('quartier'));
            $professionnel->setNom($request->get('nom'));
            $professionnel->setStatus("attente");
            $professionnel->setProfessionnel($request->get('professionnel'));
            $professionnel->setPrenoms($request->get('prenoms'));
            $professionnel->setEmail($request->get('emailAutre'));
            $professionnel->setLieuExercicePro($request->get('lieuExercicePro'));

            //ETAPE 3

            $professionnel->setProfession($request->get('profession'));
            $professionnel->setCivilite($civiliteRepository->find($request->get('civilite')));
            $professionnel->setEmailPro($request->get('emailPro'));
            $professionnel->setDateDiplome(new DateTimeImmutable($request->get('dateDiplome')));
            $professionnel->setDateNaissance(new DateTimeImmutable($request->get('dateNaissance')));
            $professionnel->setNumber($request->get('numero'));
            $professionnel->setLieuDiplome($request->get('lieuDiplome'));
            $professionnel->setNationate($paysRepository->find($request->get('nationalite')));
            $professionnel->setSituation($request->get('situation'));
            $professionnel->setDatePremierDiplome(new DateTimeImmutable($request->get('datePremierDiplome')));
            $professionnel->setPoleSanitairePro($request->get('poleSanitairePro'));
            $professionnel->setDiplome($request->get('diplome'));
            $professionnel->setSituationPro($situationProfessionnelleRepository->find($request->get('situationPro')));
            $professionnel->setAppartenirOrganisation($request->get('appartenirOrganisation'));
            if ($request->get('appartenirOrganisation') == "oui") {


                $professionnel->setOrganisationNom($request->get('organisationNom'));
            }


            $professionnel->setUpdatedAt(new DateTime());
            $professionnel->setCreatedAtValue(new DateTime());


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


            /* $professionnel->setUser($user); */


            $professionnel->setCreatedBy($user);
            $professionnel->setUpdatedBy($user);

            $errorResponse = $this->errorResponse($professionnel);
            $errorResponseUser = $this->errorResponse($user);
            if ($errorResponse !== null) {
                return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
            } else {

                if ($errorResponseUser !== null) {
                    return $errorResponseUser;
                } else {
                    $professionnelRepository->add($professionnel, true);
                    $user->setPersonne($professionnel);
                    $this->userRepository->add($user, true);
                }
                $info_user = [
                    'login' => $request->get('email'),

                ];

                $context = compact('info_user');

                // TO DO
                $sendMailService->send(
                    'test@myonmci.ci',
                    $request->get('email'),
                    'Informations',
                    'content_mail',
                    $context
                );
            }
        }


        return $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
    }


    #[Route('/update/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Update de professionnel",
        description: "Permet de créer un professionnel.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        // etatpe 1
                        new OA\Property(property: "password", type: "string"), // username
                        new OA\Property(property: "confirmPassword", type: "string"), // username
                        new OA\Property(property: "email", type: "string"),


                        // etatpe 2


                        new OA\Property(property: "poleSanitaire", type: "string"), //pole sanitaire
                        new OA\Property(property: "region", type: "string"), //pole sanitaire
                        new OA\Property(property: "district", type: "string"), //pole sanitaire
                        new OA\Property(property: "ville", type: "string"), //pole sanitaire
                        new OA\Property(property: "commune", type: "string"), //pole sanitaire
                        new OA\Property(property: "quartier", type: "string"), //pole sanitaire
                        new OA\Property(property: "nom", type: "string"), //first_name 
                        new OA\Property(property: "professionnel", type: "string"), //professionnel
                        new OA\Property(property: "prenoms", type: "string"),
                        new OA\Property(property: "lieuExercicePro", type: "string"), //lieu_exercice_pro
                        new OA\Property(property: "emailAutre", type: "string"), //email

                        // etatpe 3

                        new OA\Property(property: "profession", type: "string"), //profession bouton radio
                        new OA\Property(property: "civilite", type: "string"), //civilite select
                        new OA\Property(property: "emailPro", type: "string"), //email_pro
                        new OA\Property(property: "dateDiplome", type: "string"), //dateDiplome date
                        new OA\Property(property: "dateNaissance", type: "string"), //dateNaissance date
                        new OA\Property(property: "numero", type: "string"), //contact
                        new OA\Property(property: "lieuDiplome", type: "string"), //lieu au obtention premier diplome
                        new OA\Property(property: "nationalite", type: "string"), //nationalite select
                        new OA\Property(property: "situation", type: "string"), //situation matrimonial
                        new OA\Property(property: "datePremierDiplome", type: "string"), //datePremierDiplome
                        new OA\Property(property: "poleSanitairePro", type: "string"), //contactPerso
                        new OA\Property(property: "diplome", type: "string"), //diplome
                        new OA\Property(property: "situationPro", type: "string"), //situation_pro


                        // etatpe 4


                        new OA\Property(property: "photo", type: "string", format: "binary"), //photo
                        new OA\Property(property: "cni", type: "string", format: "binary"), //cni
                        new OA\Property(property: "casier", type: "string", format: "binary"), //casier
                        new OA\Property(property: "diplomeFile", type: "string", format: "binary"), //diplomeFile
                        new OA\Property(property: "certificat", type: "string", format: "binary"), //certificat
                        new OA\Property(property: "cv", type: "string", format: "binary"), //cv

                        // etatpe 5


                        new OA\Property(property: "appartenirOrganisation", type: "boolean"), // oui ou non
                        new OA\Property(property: "organisationNom", type: "string"),

                        new OA\Property(property: "reference", type: "string"),




                    ],
                    type: "object"
                )
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'professionnel')]
    #[Security(name: 'Bearer')]
    public function update(
        Request $request,
        SituationProfessionnelleRepository $situationProfessionnelleRepository,
        RegionRepository $regionRepository,
        DistrictRepository $districtRepository,
        CommuneRepository $communeRepository,
        VilleRepository $villeRepository,
        PaysRepository $paysRepository,
        CiviliteRepository $civiliteRepository,
        Professionnel $professionnel,
        SpecialiteRepository $specialiteRepository,
        GenreRepository $genreRepository,
        ProfessionnelRepository $professionnelRepository,
        OrganisationRepository $organisationRepository
    ): Response {
        try {
            $names = 'document_' . '01';
            $filePrefix  = str_slug($names);
            $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);

            //return $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
            if ($professionnel) {
                //ETAPE 2
                /* $professionnel->setCode($request->get('code')); */
                $professionnel->setPoleSanitaire($request->get('poleSanitaire'));
                $professionnel->setRegion($regionRepository->find($request->get('region')));
                $professionnel->setDistrict($districtRepository->find($request->get('district')));
                $professionnel->setVille($villeRepository->find($request->get('ville')));
                $professionnel->setCommune($communeRepository->find($request->get('commune')));
                $professionnel->setQuartier($request->get('quartier'));
                $professionnel->setNom($request->get('nom'));
                $professionnel->setProfessionnel($request->get('professionnel'));
                $professionnel->setPrenoms($request->get('prenoms'));
                $professionnel->setEmail($request->get('emailAutre'));
                $professionnel->setLieuExercicePro($request->get('lieuExercicePro'));

                //ETAPE 3

                $professionnel->setProfession($request->get('profession'));
                $professionnel->setCivilite($civiliteRepository->find($request->get('civilite')));
                $professionnel->setEmailPro($request->get('emailPro'));
                $professionnel->setDateDiplome(new DateTimeImmutable($request->get('dateDiplome')));
                $professionnel->setDateNaissance(new DateTimeImmutable($request->get('dateNaissance')));
                $professionnel->setNumber($request->get('numero'));
                $professionnel->setLieuDiplome($request->get('lieuDiplome'));
                $professionnel->setNationate($paysRepository->find($request->get('nationalite')));
                $professionnel->setSituation($request->get('situation'));
                $professionnel->setDatePremierDiplome($request->get('datePremierDiplome'));
                $professionnel->setPoleSanitairePro($request->get('poleSanitairePro'));
                $professionnel->setDiplome($request->get('diplome'));
                $professionnel->setSituationPro($situationProfessionnelleRepository->find($request->get('situationPro')));



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

                $professionnel->setAppartenirOrganisation($request->get('appartenirOrganisation'));

                $professionnel->setCreatedBy($this->userRepository->find($request->get('userUpdate')));
                $professionnel->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));

                $errorResponse = $this->errorResponse($professionnel);

                if ($request->get('appartenirOrganisation') == "oui") {
                    $professionnel->setOrganisationNom($request->get('organisationNom'));
                } else {
                    $professionnel->setOrganisationNom("");
                }

                $professionnelRepository->add($professionnel, true);
                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {
                    $professionnelRepository->add($professionnel, true);
                }
                $response = $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
            }
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }
        return $response;
    }

    //const TAB_ID = 'parametre-tabs';

    #[Route('/delete/{id}',  methods: ['DELETE'])]
    /**
     * permet de supprimer un(e) professionnel.
     */
    #[OA\Response(
        response: 200,
        description: 'permet de supprimer un(e) professionnel',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Professionnel::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'professionnel')]
    //#[Security(name: 'Bearer')]
    public function delete(Request $request, Professionnel $professionnel, ProfessionnelRepository $villeRepository): Response
    {
        try {

            if ($professionnel != null) {

                $villeRepository->remove($professionnel, true);

                // On retourne la confirmation
                $this->setMessage("Operation effectuées avec success");
                $response = $this->response($professionnel);
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

    #[Route('/delete/all',  methods: ['DELETE'])]
    /**
     * Permet de supprimer plusieurs professionnel.
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Professionnel::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'professionnel')]
    #[Security(name: 'Bearer')]
    public function deleteAll(Request $request, ProfessionnelRepository $villeRepository): Response
    {
        try {
            $data = json_decode($request->getContent());

            foreach ($data->ids as $key => $value) {
                $professionnel = $villeRepository->find($value['id']);

                if ($professionnel != null) {
                    $villeRepository->remove($professionnel);
                }
            }
            $this->setMessage("Operation effectuées avec success");
            $response = $this->response('[]');
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }
        return $response;
    }
}
