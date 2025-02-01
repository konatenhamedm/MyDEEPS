<?php


namespace App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\ProfessionnelDTO;
use App\Entity\Organisation;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Professionnel;
use App\Repository\GenreRepository;
use App\Repository\OrganisationRepository;
use App\Repository\ProfessionnelRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
    public function index(ProfessionnelRepository $professionnelRepository): Response
    {
        try {

            $professionnels = $professionnelRepository->findAll();
            $context = [AbstractNormalizer::GROUPS => 'group_pro'];
            $json = $this->serializer->serialize($professionnels, 'json', $context);

            return new JsonResponse(['code' => 200, 'data' => json_decode($json)]);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
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
        summary: "Acpeter ou refuser un professionnel",
        description: "Permet Acpeter ou refuser un professionnel.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "status", type: "string"), // user
                    new OA\Property(property: "raison", type: "string"), // user


                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'professionnel')]
    #[Security(name: 'Bearer')]
    public function active(Request $request, Professionnel $professionnel, ProfessionnelRepository $professionnelRepository, UserRepository $userRepository): Response
    {
        try {
            $data = json_decode($request->getContent());


            if ($professionnel != null) {

                $user = $userRepository->find($professionnel->getUser()->getId());

                $user->setStatus($data->status);

                if($data->raison)
                    $user->setReason($data->raison);

                $userRepository->add($user, true);

                // On retourne la confirmation
                $response = $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
            } else {
                $this->setMessage("Cette ressource est inexsitante");
                $this->setStatusCode(300);
                $response = $this->response('[]');
            }
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }
        return $response;
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
    public function getOne(?Professionnel $professionnel)
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



    #[Route('/create',  methods: ['POST'])]
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
                        new OA\Property(property: "user", type: "string"), // user
                        new OA\Property(property: "numero", type: "string"), // code_verification ..
                        new OA\Property(property: "nom", type: "string"), //first_name 
                        new OA\Property(property: "prenoms", type: "string"),
                        new OA\Property(property: "emailPro", type: "string"), //email_pro
                        new OA\Property(property: "address", type: "string"), //address
                        new OA\Property(property: "professionnel", type: "string"), //professionnel
                        new OA\Property(property: "addressPro", type: "string"), //address_pro

                        new OA\Property(property: "profession", type: "string"), //profession
                        new OA\Property(property: "specialite", type: "string"), //specialite
                        new OA\Property(property: "genre", type: "string"), //genre
                        new OA\Property(property: "civilite", type: "string"), //civilite
                        new OA\Property(property: "adresseEmail", type: "string"), //adresseEmail
                        new OA\Property(property: "dateDiplome", type: "string"), //dateDiplome
                        new OA\Property(property: "dateNaissance", type: "string"), //dateNaissance
                        new OA\Property(property: "contactPro", type: "string"), //contactPerso
                        new OA\Property(property: "dateEmploi", type: "string"), //dateEmploi
                        new OA\Property(property: "nationate", type: "string"), //nationate
                        new OA\Property(property: "diplome", type: "string"), //diplome
                        new OA\Property(property: "situationPro", type: "string"), //situation_pro


                        new OA\Property(property: "photo", type: "string"), //photo
                        new OA\Property(property: "cni", type: "string"), //cni
                        new OA\Property(property: "casier", type: "string"), //casier
                        new OA\Property(property: "diplomeFile", type: "string"), //diplomeFile
                        new OA\Property(property: "certificat", type: "string"), //certificat
                        new OA\Property(property: "cv", type: "string"), //cv
                        new OA\Property(property: "appartenirOrganisation", type: "string"), //cv
                        new OA\Property(property: "userUpdate", type: "string"),
                        new OA\Property(property: "organisationNom", type: "string"),
                        new OA\Property(property: "organisationNumero", type: "string"),
                        new OA\Property(property: "organisationAnnee", type: "string"),


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
    public function create(Request $request,SpecialiteRepository $specialiteRepository,GenreRepository $genreRepository, ProfessionnelRepository $professionnelRepository, OrganisationRepository $organisationRepository): Response
    {

        $names = 'document_' . '01';
        $filePrefix  = str_slug($names);
        $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);


        $professionnel = new Professionnel();

        $professionnel->setNumber($request->get('numero'));
        $professionnel->setNom($request->get('nom'));
        $professionnel->setPrenoms($request->get('prenoms'));
        $professionnel->setEmailPro($request->get('emailPro'));
        $professionnel->setAddress($request->get('address'));
        $professionnel->setProfessionnel($request->get('professionnel'));
        $professionnel->setAddressPro($request->get('addressPro'));
        $professionnel->setProfession($request->get('professionnel'));
        $professionnel->setCivilite($request->get('civilite'));
        $professionnel->setAdresseEmail($request->get('adresseEmail'));
        $professionnel->setDateDiplome($request->get('dateDiplome'));
        $professionnel->setDateNaissance($request->get('dateNaissance'));
        $professionnel->setContactPro($request->get('contactPro'));

        $professionnel->setDateEmploi($request->get('dateEmploi'));
        $professionnel->setNationate($request->get('nationate'));
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


        $user = $this->userRepository->find($request->get('user'));
        $professionnel->setAppartenirOrganisation($request->get('appartenirOrganisation'));
        $professionnel->setUser($user);


        $professionnel->setCreatedBy($this->userRepository->find($request->get('userUpdate')));
        $professionnel->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));

        $errorResponse = $this->errorResponse($professionnel);
        if ($errorResponse !== null) {
            return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
        } else {
            $professionnelRepository->add($professionnel, true);
            $user->setTypeUser("PROFESSIONNEL");
            $this->userRepository->add($user, true);
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
        }
        return $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
    }


    #[Route('/update/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Creation de professionnel",
        description: "Permet de créer un professionnel.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "user", type: "string"), // user
                        new OA\Property(property: "numero", type: "string"), // code_verification ..
                        new OA\Property(property: "nom", type: "string"), //first_name 
                        new OA\Property(property: "prenoms", type: "string"),
                        new OA\Property(property: "emailPro", type: "string"), //email_pro
                        new OA\Property(property: "address", type: "string"), //address
                        new OA\Property(property: "professionnel", type: "string"), //professionnel
                        new OA\Property(property: "addressPro", type: "string"), //address_pro

                        new OA\Property(property: "profession", type: "string"), //profession
                        new OA\Property(property: "specialite", type: "string"), //specialite
                        new OA\Property(property: "genre", type: "string"), //genre
                        new OA\Property(property: "civilite", type: "string"), //civilite
                        new OA\Property(property: "adresseEmail", type: "string"), //adresseEmail
                        new OA\Property(property: "dateDiplome", type: "string"), //dateDiplome
                        new OA\Property(property: "dateNaissance", type: "string"), //dateNaissance
                        new OA\Property(property: "contactPro", type: "string"), //contactPerso
                        new OA\Property(property: "dateEmploi", type: "string"), //dateEmploi
                        new OA\Property(property: "nationate", type: "string"), //nationate
                        new OA\Property(property: "diplome", type: "string"), //diplome
                        new OA\Property(property: "situationPro", type: "string"), //situation_pro


                        new OA\Property(property: "photo", type: "string"), //photo
                        new OA\Property(property: "cni", type: "string"), //cni
                        new OA\Property(property: "casier", type: "string"), //casier
                        new OA\Property(property: "diplomeFile", type: "string"), //diplomeFile
                        new OA\Property(property: "certificat", type: "string"), //certificat
                        new OA\Property(property: "cv", type: "string"), //cv
                        new OA\Property(property: "appartenirOrganisation", type: "string"), //cv
                        new OA\Property(property: "userUpdate", type: "string"),

                        new OA\Property(property: "organisationNom", type: "string"),
                        new OA\Property(property: "organisationNumero", type: "string"),
                        new OA\Property(property: "organisationAnnee", type: "string"),


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
    public function update(Request $request, Professionnel $professionnel,SpecialiteRepository $specialiteRepository,GenreRepository $genreRepository, ProfessionnelRepository $professionnelRepository, OrganisationRepository $organisationRepository): Response
    {
        try {
            $names = 'document_' . '01';
            $filePrefix  = str_slug($names);
            $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);


            if ($professionnel) {
                $professionnel->setNumber($request->get('numero'));
                $professionnel->setNom($request->get('nom'));
                $professionnel->setPrenoms($request->get('prenoms'));
                $professionnel->setEmailPro($request->get('emailPro'));
                $professionnel->setAddress($request->get('address'));
                $professionnel->setProfessionnel($request->get('professionnel'));
                $professionnel->setAddressPro($request->get('addressPro'));
                $professionnel->setProfession($request->get('professionnel'));
                $professionnel->setCivilite($request->get('civilite'));
                $professionnel->setAdresseEmail($request->get('adresseEmail'));
                $professionnel->setDateDiplome($request->get('dateDiplome'));
                $professionnel->setDateNaissance($request->get('dateNaissance'));
                $professionnel->setContactPro($request->get('contactPro'));

                $professionnel->setDateEmploi($request->get('dateEmploi'));
                $professionnel->setNationate($request->get('nationate'));
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
                $user = $this->userRepository->find($request->get('user'));
                $professionnel->setUser($user);


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
                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {
                    $professionnelRepository->add($professionnel, true);
                    $user->setTypeUser("PROFESSIONNEL");
                    $this->userRepository->add($user, true);
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
