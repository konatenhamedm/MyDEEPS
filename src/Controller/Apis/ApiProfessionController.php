<?php

namespace  App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\ProfessionDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Profession;
use App\Entity\TypeProfession;
use App\Repository\ProfessionRepository;
use App\Repository\TypeProfessionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/profession')]
class ApiProfessionController extends ApiInterface
{



    #[Route('/mise-jour', methods: ['GET'])]
    /**
     * Retourne la liste des professions.
     * 
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Profession::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'profession')]
    // #[Security(name: 'Bearer')]
    public function indexMiseJour(ProfessionRepository $professionRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $professions = [
                [
                    "title" => "Médecins",
                    "professions" => [
                        ["id" => "rd_medecins", "title" => "Médecins"],
                        ["id" => "rd_medecins_specialistes", "title" => "Médecins spécialistes"],
                        ["id" => "rd_chirurgiens_dentistes", "title" => "Chirurgiens-dentistes"],
                        ["id" => "rd_chirurgiens_dentistes_specialistes", "title" => "Chirurgiens-dentistes spécialistes"],
                    ],
                ],
                [
                    "title" => "Profession de pharmacie et de la physique médicale",
                    "professions" => [
                        ["id" => "rd_pharmaciens", "title" => "Pharmaciens"],
                        ["id" => "rd_pharmaciens_specialistes", "title" => "Pharmaciens spécialistes"],
                        ["id" => "rd_physiciens_medicaux", "title" => "Physiciens Médicaux"],
                        ["id" => "rd_infirmiers", "title" => "Infirmiers"],
                        ["id" => "rd_infirmiers_specialistes", "title" => "Infirmiers spécialistes"],
                        ["id" => "rd_sages_femmes", "title" => "Sages-femmes"],
                        ["id" => "rd_sages_femmes_specialistes", "title" => "Sages-femmes spécialistes"],
                    ],
                ],
                [
                    "title" => "Professions paramédicales",
                    "professions" => [
                        ["id" => "rd_dieteticien", "title" => "Diététicien"],
                        ["id" => "rd_opticiens_optometristes", "title" => "Opticiens / Optométristes"],
                        ["id" => "rd_audioprothesistes", "title" => "Audioioprothésistes"],
                        ["id" => "rd_orthoprothesistes", "title" => "Orthoioprothésistes"],
                        ["id" => "rd_pedicure_pedologue", "title" => "Pédicure, podologue"],
                        ["id" => "rd_assistants_dentistes", "title" => "Assistants dentistes"],
                        ["id" => "rd_psychotromiciens", "title" => "Psychotromiciens"],
                        ["id" => "rd_ergothérapeutes", "title" => "Ergothérapeutes"],
                    ],
                ],
                [
                    "title" => "Technicien Supérieur de la Santé",
                    "professions" => [
                        ["id" => "rd_technicien_biologie_medicale", "title" => "Biologie Médicale"],
                        ["id" => "rd_technicien_hygiene_assainissement", "title" => "Hygiène et Assainissement"],
                        ["id" => "rd_technicien_imagerie_medicale", "title" => "Imagerie Médicale"],
                        ["id" => "rd_technien_bio_medicale", "title" => "Biomédicale"],
                        ["id" => "rd_kinesithérapie", "title" => "Kinésitherapie"],
                        ["id" => "rd_prothese_dentaire", "title" => "Prothèse Dentaire"],
                        ["id" => "rd_preparation_gestion_pharmacie", "title" => "Préparation et Gestion en Pharmacie"],
                    ],
                ],
                [
                    "title" => "Auxiliaire des techniques sanitaires",
                    "professions" => [
                        ["id" => "rd_soins_obstetricaux", "title" => "Soins obstétricaux"],
                        ["id" => "rd_soins_infirmiers", "title" => "Soins Infirmiers"],
                        ["id" => "rd_pharmacie", "title" => "Pharmacie"],
                        ["id" => "rd_laboratoire", "title" => "Laboratoire"],
                        ["id" => "rd_auxiliaire_hygiene_assainissement", "title" => "Hygiène et Assainissement"],
                        ["id" => "rd_auxiliaire_imagerie_medicale", "title" => "Imagerie Médicale"],
                    ],
                ],
                [
                    "title" => "Ingénieur des Techniques Sanitaires",
                    "professions" => [
                        ["id" => "rd_techniques_preparation_et_gestion_en_pharmacie", "title" => "Préparation et Gestion en Pharmacie"],
                        ["id" => "rd_ingenieur_biologie_medicale", "title" => "Biologie Médicale"],
                        ["id" => "rd_ingenieur_hygiene_assainissement", "title" => "Hygiène et Assainissement"],
                        ["id" => "rd_ingenieur_imagerie_medicale", "title" => "Imagerie Médicale"],
                        ["id" => "rd_ingenieur_bio_medicale", "title" => "Biomédicale"],
                        ["id" => "rd_ingenieur_sante_publique", "title" => "Santé Publique"],
                    ],
                ],
                [
                    "title" => "Ingénieur des Services de Santé",
                    "professions" => [
                        ["id" => "rd_services_preparation_et_gestion_en_pharmacie", "title" => "Préparation et Gestion en Pharmacie"],
                        ["id" => "rd_services_biologie_medicale", "title" => "Biologie Médicale"],
                        ["id" => "rd_services_hygiene_et_assainissement", "title" => "Hygiène et Assainissement"],
                        ["id" => "rd_services_imagerie_medicale", "title" => "Imagerie Médicale"],
                        ["id" => "rd_services_bio_medicale", "title" => "Biomédicale"],
                        ["id" => "rd_services_sante_publique", "title" => "Santé Publique"],
                        ["id" => "rd_epidemiologie", "title" => "Épidémiologie"],
                    ],
                ],
                [
                    "title" => "Profession de la médecine traditionnelle",
                    "professions" => [
                        ["id" => "rd_naturotherapeutes", "title" => "Naturothérapeutes"],
                        ["id" => "rd_phytotherapeutes", "title" => "Phytothérapeutes"],
                        ["id" => "rd_psychotherapeutes", "title" => "Psychothérapeutes"],
                        ["id" => "rd_herboristes", "title" => "Herboristes"],
                        ["id" => "rd_medico-droguistes", "title" => "Médico-droguistes"],
                        ["id" => "rd_accoucheuses_traditionnelles", "title" => "Accoucheuses traditionnelles"],
                    ],
                ],
                [
                    "title" => "Profession de la médecine alternative et complémentaire",
                    "professions" => [
                        ["id" => "rd_naturotherapie", "title" => "Naturothérapie"],
                        ["id" => "rd_praticiens_acupuncture", "title" => "Praticiens d'acupuncture"],
                        ["id" => "rd_homeopathie", "title" => "Homéopathie"],
                        ["id" => "rd_naturopathie", "title" => "Naturopathie"],
                        ["id" => "rd_phytotherapie", "title" => "Phytothérapie"],
                        ["id" => "rd_chiropractie", "title" => "Chiropractie"],
                        ["id" => "rd_osteopathie", "title" => "Ostéopathie"],
                        ["id" => "rd_psychotherapie", "title" => "Psychothérapie"],
                        ["id" => "rd_hypnotherapie", "title" => "Hypnothérapie"],
                        ["id" => "rd_massootherapie", "title" => "Massoothérapie"],
                    ],
                ],
            ];


            foreach ($professions as $professionElement) {
                $lastType = $entityManager->getRepository(TypeProfession::class)->findOneBy([], ['id' => 'DESC']);
                $nextId = $lastType ? $lastType->getId() + 1 : 1;
                $typeProfession = new TypeProfession();
                $typeProfession->setLibelle($professionElement['title']);
                $typeProfession->setCode('TP' . str_pad($nextId, 5, '0', STR_PAD_LEFT));
                $typeProfession->setCreatedAtValue(new \DateTime());
                $typeProfession->setUpdatedAt(new \DateTime());
                /*    $typeProfession->setCreatedBy($this->userRepository->find($data['userUpdate']));
                $typeProfession->setUpdatedBy($this->userRepository->find($data['userUpdate'])); */
                $entityManager->persist($typeProfession);
                $entityManager->flush();
                
                foreach ($professionElement['professions'] as $profession) {
                    $professionEntity = new Profession();
                    $professionEntity->setLibelle($profession['title']);
                    $professionEntity->setCode($profession['id']);
                    $professionEntity->setCreatedAtValue(new \DateTime());
                    $professionEntity->setUpdatedAt(new \DateTime());
                   /*  $professionEntity->setCreatedBy($this->userRepository->find($data['userUpdate']));
                    $professionEntity->setUpdatedBy($this->userRepository->find($data['userUpdate'])); */
                    $professionEntity->setTypeProfession($typeProfession);

                    // Supposons que l'EntityManager soit disponible sous $this->entityManager
                    $entityManager->persist($professionEntity);
                    $entityManager->flush();
                }

            }

           


            $response =  $this->response('[]');
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }

    #[Route('/', methods: ['GET'])]
    /**
     * Retourne la liste des professions.
     * 
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Profession::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'profession')]
    // #[Security(name: 'Bearer')]
    public function index(ProfessionRepository $professionRepository): Response
    {
        try {

            $professions = $professionRepository->findAll();



            $response =  $this->responseData($professions, 'group1', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }


    #[Route('/get/one/{id}', methods: ['GET'])]
    /**
     * Affiche un(e) profession en offrant un identifiant.
     */
    #[OA\Response(
        response: 200,
        description: 'Affiche un(e) profession en offrant un identifiant',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Profession::class, groups: ['full']))
        )
    )]
    #[OA\Parameter(
        name: 'code',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'profession')]
    //#[Security(name: 'Bearer')]
    public function getOne(?Profession $profession)
    {
        try {
            if ($profession) {
                $response = $this->response($profession);
            } else {
                $this->setMessage('Cette ressource est inexistante');
                $this->setStatusCode(300);
                $response = $this->response($profession);
            }
        } catch (\Exception $exception) {
            $this->setMessage($exception->getMessage());
            $response = $this->response('[]');
        }


        return $response;
    }


    #[Route('/create',  methods: ['POST'])]
    /**
     * Permet de créer un(e) profession.
     */
    #[OA\Post(
        summary: "Authentification admin",
        description: "Génère un token JWT pour les administrateurs.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "libelle", type: "string"),
                    new OA\Property(property: "typeProfession", type: "string"),
                    new OA\Property(property: "userUpdate", type: "string"),

                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'profession')]
    #[Security(name: 'Bearer')]
    public function create(Request $request, ProfessionRepository $professionRepository, TypeProfessionRepository $typeProfessionRepository): Response
    {


        $data = json_decode($request->getContent(), true);

        $profession = new Profession();
        $profession->setLibelle($data['libelle']);
        $profession->setCreatedAtValue(new \DateTime());
        $profession->setUpdatedAt(new \DateTime());
        $profession->setTypeProfession($typeProfessionRepository->find($data['typeProfession']));
        $profession->setCode($typeProfessionRepository->find($data['typeProfession'])->getCode() . '_' . $data['libelle']);
        $profession->setCreatedBy($this->userRepository->find($data['userUpdate']));
        $profession->setUpdatedBy($this->userRepository->find($data['userUpdate']));
        $errorResponse = $this->errorResponse($profession);
        if ($errorResponse !== null) {
            return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
        } else {

            $professionRepository->add($profession, true);
        }

        return $this->responseData($profession, 'group1', ['Content-Type' => 'application/json']);
    }


    #[Route('/update/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Creation de profession",
        description: "Permet de créer un profession.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "libelle", type: "string"),
                    new OA\Property(property: "typeProfession", type: "string"),
                    new OA\Property(property: "userUpdate", type: "string"),

                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'profession')]
    #[Security(name: 'Bearer')]
    public function update(Request $request, Profession $profession, ProfessionRepository $professionRepository, TypeProfessionRepository $typeProfessionRepository): Response
    {
        try {
            $data = json_decode($request->getContent());
            if ($profession != null) {

                $profession->setLibelle($data->libelle);
                $profession->setTypeProfession($typeProfessionRepository->find($data->typeProfession));
                $profession->setCode($typeProfessionRepository->find($data->typeProfession)->getCode() . '_' . $data['libelle']);
                $profession->setUpdatedBy($this->userRepository->find($data->userUpdate));
                $profession->setUpdatedAt(new \DateTime());
                $errorResponse = $this->errorResponse($profession);

                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {
                    $professionRepository->add($profession, true);
                }

                // On retourne la confirmation
                $response = $this->responseData($profession, 'group1', ['Content-Type' => 'application/json']);
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

    //const TAB_ID = 'parametre-tabs';

    #[Route('/delete/{id}',  methods: ['DELETE'])]
    /**
     * permet de supprimer un(e) profession.
     */
    #[OA\Response(
        response: 200,
        description: 'permet de supprimer un(e) profession',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Profession::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'profession')]
    //#[Security(name: 'Bearer')]
    public function delete(Request $request, Profession $profession, ProfessionRepository $villeRepository): Response
    {
        try {

            if ($profession != null) {

                $villeRepository->remove($profession, true);

                // On retourne la confirmation
                $this->setMessage("Operation effectuées avec success");
                $response = $this->response($profession);
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
     * Permet de supprimer plusieurs profession.
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Profession::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'profession')]
    #[Security(name: 'Bearer')]
    public function deleteAll(Request $request, ProfessionRepository $villeRepository): Response
    {
        try {
            $data = json_decode($request->getContent());

            foreach ($data->ids as $key => $value) {
                $profession = $villeRepository->find($value['id']);

                if ($profession != null) {
                    $villeRepository->remove($profession);
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
