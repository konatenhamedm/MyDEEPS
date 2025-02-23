<?php


namespace App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\ActiveProfessionnelRequest;
use App\DTO\ProfessionnelDTO;
use App\Entity\Etablissement;
use App\Entity\Organisation;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Professionnel;
use App\Entity\User;
use App\Repository\CiviliteRepository;
use App\Repository\GenreRepository;
use App\Repository\OrganisationRepository;
use App\Repository\PaysRepository;
use App\Repository\ProfessionnelRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
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
    public function index(ProfessionnelRepository $professionnelRepository,UserRepository $userRepository): Response
    {

        try {
            $professionnels = $userRepository->findBy(['typeUser' => 'PROFESSIONNEL']);

            $response = $this->responseData($professionnels, 'group_pro', ['Content-Type' => 'application/json']);
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



    #[Route('/active/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Accepter ou refuser un professionnel",
        description: "Permet d'accepter ou de refuser un professionnel.",
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
    #[OA\Tag(name: 'professionnel')]
    public function active(
        Request $request,
        Professionnel $professionnel,
        ProfessionnelRepository $professionnelRepository,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        Registry $workflowRegistry  ,SendMailService $sendMailService// Injecter le Registry
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

            $professionnel->setReason($dto->raison);
            $professionnelRepository->add($professionnel, true);

            

            $info_user = [
                'user' => $userRepository->find($data['userUpdate'])->getUsername(),
                'etape' => $dto->status,
            ];

            $context = compact('info_user');

            // TO DO
            $sendMailService->send(
                'test@myonmci.ci',
                $data['email'],
                'Validaton du dossier',
                'content_validation',
                $context
            );


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


    #[Route('/create/{reference}', name: 'create_professionnel', methods: ['POST'])]
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

                        new OA\Property(property: "numero", type: "string"), // code_verification ..
                        new OA\Property(property: "address", type: "string"), //address
                        new OA\Property(property: "nom", type: "string"), //first_name 
                        new OA\Property(property: "professionnel", type: "string"), //professionnel
                        new OA\Property(property: "prenoms", type: "string"),
                        new OA\Property(property: "emailPro", type: "string"), //email_pro
                        new OA\Property(property: "specialite", type: "string"), //specialite select

                        // etatpe 3

                        new OA\Property(property: "profession", type: "string"), //profession bouton radio
                        new OA\Property(property: "civilite", type: "string"), //civilite select
                        new OA\Property(property: "genre", type: "string"), //genre select
                        new OA\Property(property: "ville", type: "string"), //specialite  select
                        new OA\Property(property: "adresseEmail", type: "string"), //adresseEmail
                        new OA\Property(property: "dateDiplome", type: "string"), //dateDiplome date
                        new OA\Property(property: "dateNaissance", type: "string"), //dateNaissance date
                        new OA\Property(property: "contactPro", type: "string"), //contactPerso
                        new OA\Property(property: "lieuDiplome", type: "string"), //lieu au obtention premier diplome
                        new OA\Property(property: "nationalite", type: "string"), //nationalite select
                        new OA\Property(property: "situation", type: "string"), //situation matrimonial
                        new OA\Property(property: "dateEmploi", type: "string"), //dateEmploi date
                        new OA\Property(property: "lieuResidence", type: "string"), //lieu au obtention premier diplome
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


                        new OA\Property(property: "appartenirOrganisation", type: "string"), // oui ou non
                        new OA\Property(property: "organisationNom", type: "string"),
                        new OA\Property(property: "organisationNumero", type: "string"),
                        new OA\Property(property: "organisationAnnee", type: "string"),
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
    public function create(Request $request, $reference, SessionInterface $session, SendMailService $sendMailService, TransactionRepository $transactionRepository, VilleRepository $villeRepository, CiviliteRepository $civiliteRepository, SpecialiteRepository $specialiteRepository, GenreRepository $genreRepository, ProfessionnelRepository $professionnelRepository, PaysRepository $paysRepository, OrganisationRepository $organisationRepository): Response
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
            $user->setUsername($request->get('nom') . " " . $this->numero());
            $user->setEmail($request->get('email'));
            $user->setPassword($this->hasher->hashPassword($user, $request->get('password')));
            $user->setRoles(['ROLE_MEMBRE']);
            $user->setTypeUser(User::TYPE['PROFESSIONNEL']);
            $user->setPayement(User::PAYEMENT['init_payement']);


            $errorResponse1 = $request->get('password') !== $request->get('confirmPassword') ?  $this->errorResponse($user, "Les mots de passe ne sont pas identiques") :  $this->errorResponse($user);
            if ($errorResponse1 !== null) {
                return $errorResponse1; // Retourne la réponse d'erreur si des erreurs sont présentes
            } else {

                $this->userRepository->add($user, true);

                $professionnel = new Professionnel();


                $professionnel->setNumber($request->get('numero'));
                $professionnel->setStatus('attente');
                $professionnel->setNom($request->get('nom'));
                $professionnel->setVille($villeRepository->findOneByCode($request->get('ville')));
                $professionnel->setPrenoms($request->get('prenoms'));
                $professionnel->setEmailPro($request->get('emailPro'));
                $professionnel->setAddress($request->get('address'));
                $professionnel->setProfessionnel($request->get('professionnel'));
                $professionnel->setAddressPro($request->get('adresseEmail'));
                $professionnel->setProfession($request->get('professionnel'));
                $professionnel->setLieuResidence($request->get('lieuResidence'));
                $professionnel->setLieuDiplome($request->get('lieuDiplome'));
                $professionnel->setCivilite($civiliteRepository->findOneByCode($request->get('civilite')));
                $professionnel->setAdresseEmail($request->get('emailPro'));
                $professionnel->setDateDiplome(new DateTimeImmutable($request->get('dateDiplome')));
                $professionnel->setDateNaissance(new DateTimeImmutable($request->get('dateNaissance')));
                $professionnel->setContactPro($request->get('contactPro'));

                $professionnel->setDateEmploi(new DateTimeImmutable($request->get('dateEmploi')));
                $professionnel->setNationate($paysRepository->find($request->get('nationate')));
                $professionnel->setDiplome($request->get('diplome'));
                $professionnel->setSituationPro($request->get('situationPro'));
                $professionnel->setSpecialite($specialiteRepository->find($request->get('specialite')));
                $professionnel->setGenre($genreRepository->find($request->get('genre')));


                $uploadedPhoto = $request->files->get('photo');
                $uploadedCasier = $request->files->get('caiser');
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
                /* $professionnel->setUser($user); */


                $professionnel->setCreatedBy($user);
                $professionnel->setUpdatedBy($user);

                $errorResponse = $this->errorResponse($professionnel);
                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {
                    $this->userRepository->add($user, true);
                    $professionnelRepository->add($professionnel, true);


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


                    if ($transaction) {
                        $transaction->setUser($user);
                        $transaction->setCreatedBy($user);
                        $transaction->setUpdatedBy($user);
                        $transactionRepository->add($transaction, true);

                        $user->setPayement(User::PAYEMENT['payed']);
                        $this->userRepository->add($user, true);
                    }

                    if ($professionnel->getAppartenirOrganisation() == "oui") {

                        $organisation = new Organisation();
                        $organisation->setNom($request->get('organisationNom'));
                        $organisation->setAnnee($request->get('organisationAnnee'));
                        $organisation->setNumero($request->get('organisationNumero'));
                        $organisation->setEntite($professionnel);
                        $organisation->setCreatedBy($user);
                        $organisation->setUpdatedBy($user);
                        $organisationRepository->add($organisation, true);
                    }
                }
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


                        new OA\Property(property: "numero", type: "string"), // code_verification ..
                        new OA\Property(property: "address", type: "string"), //address
                        new OA\Property(property: "nom", type: "string"), //first_name 
                        new OA\Property(property: "professionnel", type: "string"), //professionnel
                        new OA\Property(property: "prenoms", type: "string"),
                        new OA\Property(property: "emailPro", type: "string"), //email_pro
                        new OA\Property(property: "specialite", type: "string"), //specialite select


                        new OA\Property(property: "profession", type: "string"), //profession bouton radio
                        new OA\Property(property: "civilite", type: "string"), //civilite select
                        new OA\Property(property: "genre", type: "string"), //genre select
                        new OA\Property(property: "ville", type: "string"), //specialite  select
                        new OA\Property(property: "adresseEmail", type: "string"), //adresseEmail
                        new OA\Property(property: "dateDiplome", type: "string"), //dateDiplome date
                        new OA\Property(property: "dateNaissance", type: "string"), //dateNaissance date
                        new OA\Property(property: "contactPro", type: "string"), //contactPerso
                        new OA\Property(property: "lieuDiplome", type: "string"), //lieu au obtention premier diplome
                        new OA\Property(property: "nationalite", type: "string"), //nationalite select
                        new OA\Property(property: "situation", type: "string"), //situation matrimonial
                        new OA\Property(property: "dateEmploi", type: "string"), //dateEmploi date
                        new OA\Property(property: "lieuResidence", type: "string"), //lieu au obtention premier diplome
                        new OA\Property(property: "diplome", type: "string"), //diplome
                        new OA\Property(property: "situationPro", type: "string"), //situation_pro


                        new OA\Property(property: "photo", type: "string", format: "binary"), //photo
                        new OA\Property(property: "cni", type: "string", format: "binary"), //cni
                        new OA\Property(property: "casier", type: "string", format: "binary"), //casier
                        new OA\Property(property: "diplomeFile", type: "string", format: "binary"), //diplomeFile
                        new OA\Property(property: "certificat", type: "string", format: "binary"), //certificat
                        new OA\Property(property: "cv", type: "string", format: "binary"), //cv


                        new OA\Property(property: "appartenirOrganisation", type: "string"), // oui ou non
                        new OA\Property(property: "organisationNom", type: "string"),
                        new OA\Property(property: "organisationNumero", type: "string"),
                        new OA\Property(property: "organisationAnnee", type: "string"),
                        new OA\Property(property: "userUpdate", type: "string"),




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
    public function update(Request $request, VilleRepository $villeRepository, PaysRepository $paysRepository, CiviliteRepository $civiliteRepository, Professionnel $professionnel, SpecialiteRepository $specialiteRepository, GenreRepository $genreRepository, ProfessionnelRepository $professionnelRepository, OrganisationRepository $organisationRepository): Response
    {
        try {
            $names = 'document_' . '01';
            $filePrefix  = str_slug($names);
            $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);

            //return $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
            if ($professionnel) {
                $professionnel->setNumber($request->get('numero'));
                $professionnel->setNom($request->get('nom'));
               $professionnel->setVille($villeRepository->find($request->get('ville')));
                $professionnel->setPrenoms($request->get('prenoms'));
                $professionnel->setEmailPro($request->get('emailPro'));
                $professionnel->setAddress($request->get('address'));
                $professionnel->setProfessionnel($request->get('professionnel'));
                $professionnel->setAddressPro($request->get('adresseEmail'));
                $professionnel->setProfession($request->get('professionnel'));
                $professionnel->setLieuResidence($request->get('lieuResidence'));
                $professionnel->setLieuDiplome($request->get('lieuDiplome'));
                  $professionnel->setCivilite($civiliteRepository->find($request->get('civilite')));
                $professionnel->setAdresseEmail($request->get('emailPro'));
                $professionnel->setDateDiplome(new \DateTime($request->get('dateDiplome')));
                $professionnel->setDateNaissance(new \DateTime($request->get('dateNaissance')));
                $professionnel->setContactPro($request->get('contactPro'));
                
               $professionnel->setDateEmploi(new \DateTime($request->get('dateEmploi')));
                $professionnel->setNationate($paysRepository->find($request->get('nationalite')));
                $professionnel->setDiplome($request->get('diplome'));
                $professionnel->setSituation($request->get('situation'));
                $professionnel->setSituationPro($request->get('situationPro'));
                $professionnel->setSpecialite($specialiteRepository->find($request->get('specialite')));
                $professionnel->setGenre($genreRepository->find($request->get('genre')));

                
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

                if ($professionnel->getAppartenirOrganisation() == "oui") {

                    $organisation = new Organisation();
                    $organisation->setNom($request->get('organisationNom'));
                    $organisation->setAnnee($request->get('organisationAnnee'));
                    $organisation->setNumero($request->get('organisationNumero'));
                    $organisation->setEntite($professionnel);
                    $organisation->setCreatedBy($this->userRepository->find($request->get('userUpdate')));
                    $organisation->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));
                    $organisationRepository->add($organisation, true);
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
