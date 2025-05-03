<?php

namespace  App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\ProfessionDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Profession;
use App\Entity\TypeProfession;
use App\Entity\Ville;
use App\Repository\ProfessionRepository;
use App\Repository\TypeProfessionRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use DateTime;
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

  

    #[Route('/get/status/paiement/{code}', methods: ['GET'])]
    /**
     * Affiche un(e) specialite en offrant un identifiant.
     */
    #[OA\Response(
        response: 200,
        description: 'Affiche etat paiement de la specialite',
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
    public function getPaiementStatus($code, ProfessionRepository $professionRepository)
    {
        try {

            $profession = $professionRepository->findOneBy(['code' => $code]);
            if ($profession) {
                $response = $this->response($profession->getMontantNouvelleDemande() != null ? true : false);
            } else {
                $this->setMessage('Cette ressource est inexistante');
                $this->setStatusCode(300);
                $response = $this->response(false);
            }
        } catch (\Exception $exception) {
            $this->setMessage($exception->getMessage());
            $response = $this->response('[]');
        }


        return $response;
    }
    #[Route('/get/by/code/{code}', methods: ['GET'])]
    /**
     * Affiche un(e) specialite en offrant un identifiant.
     */
    #[OA\Response(
        response: 200,
        description: 'Affiche etat paiement de la specialite',
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
    public function getByCodes($code, ProfessionRepository $professionRepository)
    {
        try {

            $profession = $professionRepository->findOneBy(['code' => $code]);
            if ($profession) {
                $response = $this->response($profession->getLibelle());
            } else {
                $this->setMessage('Cette ressource est inexistante');
                $this->setStatusCode(300);
                $response = $this->response('');
            }
        } catch (\Exception $exception) {
            $this->setMessage($exception->getMessage());
            $response = $this->response('[]');
        }


        return $response;
    }


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

    #[Route('/mise-jour/ville', methods: ['GET'])]
    /**
     * Retourne la liste des villes.
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
    public function indexMiseJourVille(VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            
            $villes =  array (
                0 => 
                array (
                  'city' => 'Abidjan',
                  'lat' => '5.3364',
                  'lng' => '-4.0267',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Abidjan',
                  'capital' => 'primary',
                  'population' => '4980000',
                  'population_proper' => '4980000',
                ),
                1 => 
                array (
                  'city' => 'Bouaké',
                  'lat' => '7.6833',
                  'lng' => '-5.0331',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Vallée du Bandama',
                  'capital' => 'admin',
                  'population' => '659233',
                  'population_proper' => '659233',
                ),
                2 => 
                array (
                  'city' => 'Yamoussoukro',
                  'lat' => '6.8161',
                  'lng' => '-5.2742',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Yamoussoukro',
                  'capital' => 'primary',
                  'population' => '355573',
                  'population_proper' => '355573',
                ),
                3 => 
                array (
                  'city' => 'Korhogo',
                  'lat' => '9.4578',
                  'lng' => '-5.6294',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'admin',
                  'population' => '286071',
                  'population_proper' => '286071',
                ),
                4 => 
                array (
                  'city' => 'Daloa',
                  'lat' => '6.8900',
                  'lng' => '-6.4500',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Sassandra-Marahoué',
                  'capital' => 'admin',
                  'population' => '255168',
                  'population_proper' => '215652',
                ),
                5 => 
                array (
                  'city' => 'San-Pédro',
                  'lat' => '4.7704',
                  'lng' => '-6.6400',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Bas-Sassandra',
                  'capital' => 'admin',
                  'population' => '210273',
                  'population_proper' => '196751',
                ),
                6 => 
                array (
                  'city' => 'Man',
                  'lat' => '7.4004',
                  'lng' => '-7.5500',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'admin',
                  'population' => '146974',
                  'population_proper' => '139341',
                ),
                7 => 
                array (
                  'city' => 'Divo',
                  'lat' => '5.8372',
                  'lng' => '-5.3572',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Gôh-Djiboua',
                  'capital' => 'minor',
                  'population' => '127867',
                  'population_proper' => '127867',
                ),
                8 => 
                array (
                  'city' => 'Gagnoa',
                  'lat' => '6.1333',
                  'lng' => '-5.9333',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Gôh-Djiboua',
                  'capital' => 'admin',
                  'population' => '123184',
                  'population_proper' => '99192',
                ),
                9 => 
                array (
                  'city' => 'Soubré',
                  'lat' => '5.7836',
                  'lng' => '-6.5939',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Bas-Sassandra',
                  'capital' => 'minor',
                  'population' => '108933',
                  'population_proper' => '58492',
                ),
                10 => 
                array (
                  'city' => 'Abengourou',
                  'lat' => '6.7297',
                  'lng' => '-3.4964',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'admin',
                  'population' => '104020',
                  'population_proper' => '71598',
                ),
                11 => 
                array (
                  'city' => 'Agboville',
                  'lat' => '5.9333',
                  'lng' => '-4.2167',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '81770',
                  'population_proper' => '64285',
                ),
                12 => 
                array (
                  'city' => 'Grand-Bassam',
                  'lat' => '5.2000',
                  'lng' => '-3.7333',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '73772',
                  'population_proper' => '48681',
                ),
                13 => 
                array (
                  'city' => 'Dabou',
                  'lat' => '5.3256',
                  'lng' => '-4.3767',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'admin',
                  'population' => '72913',
                  'population_proper' => '69661',
                ),
                14 => 
                array (
                  'city' => 'Bouaflé',
                  'lat' => '6.9903',
                  'lng' => '-5.7442',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Sassandra-Marahoué',
                  'capital' => 'minor',
                  'population' => '71792',
                  'population_proper' => '71792',
                ),
                15 => 
                array (
                  'city' => 'Dimbokro',
                  'lat' => '6.6505',
                  'lng' => '-4.7100',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'admin',
                  'population' => '67349',
                  'population_proper' => '25586',
                ),
                16 => 
                array (
                  'city' => 'Guiglo',
                  'lat' => '6.5436',
                  'lng' => '-7.4933',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '63643',
                  'population_proper' => '63643',
                ),
                17 => 
                array (
                  'city' => 'Ferkessédougou',
                  'lat' => '9.5928',
                  'lng' => '-5.1944',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '62008',
                  'population_proper' => '52812',
                ),
                18 => 
                array (
                  'city' => 'Bondoukou',
                  'lat' => '8.0400',
                  'lng' => '-2.8000',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'admin',
                  'population' => '58297',
                  'population_proper' => '18706',
                ),
                19 => 
                array (
                  'city' => 'Séguéla',
                  'lat' => '7.9611',
                  'lng' => '-6.6731',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Woroba',
                  'capital' => 'admin',
                  'population' => '51157',
                  'population_proper' => '12603',
                ),
                20 => 
                array (
                  'city' => 'Odienné',
                  'lat' => '9.5000',
                  'lng' => '-7.5667',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Denguélé',
                  'capital' => 'admin',
                  'population' => '49857',
                  'population_proper' => '19119',
                ),
                21 => 
                array (
                  'city' => 'Toumodi',
                  'lat' => '6.5520',
                  'lng' => '-5.0190',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '39005',
                  'population_proper' => '39005',
                ),
                22 => 
                array (
                  'city' => 'Sassandra',
                  'lat' => '4.9504',
                  'lng' => '-6.0833',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Bas-Sassandra',
                  'capital' => 'minor',
                  'population' => '38411',
                  'population_proper' => '23274',
                ),
                23 => 
                array (
                  'city' => 'Aboisso',
                  'lat' => '5.4667',
                  'lng' => '-3.2000',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '37654',
                  'population_proper' => '37654',
                ),
                24 => 
                array (
                  'city' => 'Touba',
                  'lat' => '8.2833',
                  'lng' => '-7.6833',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Woroba',
                  'capital' => 'minor',
                  'population' => '31844',
                  'population_proper' => '31844',
                ),
                25 => 
                array (
                  'city' => 'Bangolo',
                  'lat' => '7.0123',
                  'lng' => '-7.4864',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'admin',
                  'population' => '',
                  'population_proper' => '',
                ),
                26 => 
                array (
                  'city' => 'Bingerville',
                  'lat' => '5.3500',
                  'lng' => '-3.9000',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Abidjan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                27 => 
                array (
                  'city' => 'Anyama',
                  'lat' => '5.4946',
                  'lng' => '-4.0518',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Abidjan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                28 => 
                array (
                  'city' => 'Duekoué',
                  'lat' => '6.7419',
                  'lng' => '-7.3492',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                29 => 
                array (
                  'city' => 'Vavoua',
                  'lat' => '7.3819',
                  'lng' => '-6.4778',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Sassandra-Marahoué',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                30 => 
                array (
                  'city' => 'Méagui',
                  'lat' => '5.4048',
                  'lng' => '-6.5584',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Bas-Sassandra',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                31 => 
                array (
                  'city' => 'Zouan-Hounien',
                  'lat' => '6.9194',
                  'lng' => '-8.2065',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                32 => 
                array (
                  'city' => 'Facobly',
                  'lat' => '7.3883',
                  'lng' => '-7.3764',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                33 => 
                array (
                  'city' => 'Kouibly',
                  'lat' => '7.2560',
                  'lng' => '-7.2351',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                34 => 
                array (
                  'city' => 'Sinfra',
                  'lat' => '6.6210',
                  'lng' => '-5.9114',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Sassandra-Marahoué',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                35 => 
                array (
                  'city' => 'Adiaké',
                  'lat' => '5.2863',
                  'lng' => '-3.3040',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                36 => 
                array (
                  'city' => 'Agnibilékrou',
                  'lat' => '7.1311',
                  'lng' => '-3.2041',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                37 => 
                array (
                  'city' => 'Danané',
                  'lat' => '7.2596',
                  'lng' => '-8.1550',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                38 => 
                array (
                  'city' => 'Issia',
                  'lat' => '6.4922',
                  'lng' => '-6.5856',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Sassandra-Marahoué',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                39 => 
                array (
                  'city' => 'Bongouanou',
                  'lat' => '6.6517',
                  'lng' => '-4.2041',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                40 => 
                array (
                  'city' => 'Toulépleu',
                  'lat' => '6.5797',
                  'lng' => '-8.4109',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                41 => 
                array (
                  'city' => 'Guéyo',
                  'lat' => '5.6882',
                  'lng' => '-6.0712',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Bas-Sassandra',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                42 => 
                array (
                  'city' => 'Bonoua',
                  'lat' => '5.2725',
                  'lng' => '-3.5962',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                43 => 
                array (
                  'city' => 'Sikensi',
                  'lat' => '5.6683',
                  'lng' => '-4.5737',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                44 => 
                array (
                  'city' => 'Tiapoum',
                  'lat' => '5.1362',
                  'lng' => '-3.0231',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                45 => 
                array (
                  'city' => 'Sinématiali',
                  'lat' => '9.5841',
                  'lng' => '-5.3848',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                46 => 
                array (
                  'city' => 'Buyo',
                  'lat' => '6.2753',
                  'lng' => '-6.9970',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Bas-Sassandra',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                47 => 
                array (
                  'city' => 'Ouangolodougou',
                  'lat' => '9.9684',
                  'lng' => '-5.1488',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                48 => 
                array (
                  'city' => 'Songon',
                  'lat' => '5.3196',
                  'lng' => '-4.2542',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Abidjan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                49 => 
                array (
                  'city' => 'Oumé',
                  'lat' => '6.3833',
                  'lng' => '-5.4167',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Gôh-Djiboua',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                50 => 
                array (
                  'city' => 'Adzopé',
                  'lat' => '6.1035',
                  'lng' => '-3.8648',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                51 => 
                array (
                  'city' => 'Akoupé',
                  'lat' => '6.3842',
                  'lng' => '-3.8876',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                52 => 
                array (
                  'city' => 'Jacqueville',
                  'lat' => '5.2000',
                  'lng' => '-4.4167',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                53 => 
                array (
                  'city' => 'Zuénoula',
                  'lat' => '7.4303',
                  'lng' => '-6.0505',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Sassandra-Marahoué',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                54 => 
                array (
                  'city' => 'Tabou',
                  'lat' => '4.4230',
                  'lng' => '-7.3528',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Bas-Sassandra',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                55 => 
                array (
                  'city' => 'Tiassalé',
                  'lat' => '5.8984',
                  'lng' => '-4.8229',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                56 => 
                array (
                  'city' => 'Daoukro',
                  'lat' => '7.0586',
                  'lng' => '-3.9646',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                57 => 
                array (
                  'city' => 'Taabo',
                  'lat' => '6.1998',
                  'lng' => '-5.1088',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                58 => 
                array (
                  'city' => 'Zoukougbeu',
                  'lat' => '6.7623',
                  'lng' => '-6.8638',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Sassandra-Marahoué',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                59 => 
                array (
                  'city' => 'Biankouma',
                  'lat' => '7.7333',
                  'lng' => '-7.6167',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                60 => 
                array (
                  'city' => 'Agou',
                  'lat' => '5.9829',
                  'lng' => '-3.9439',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                61 => 
                array (
                  'city' => 'Alépé',
                  'lat' => '5.5004',
                  'lng' => '-3.6631',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                62 => 
                array (
                  'city' => 'Transua',
                  'lat' => '7.5500',
                  'lng' => '-3.0143',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                63 => 
                array (
                  'city' => 'Katiola',
                  'lat' => '8.1333',
                  'lng' => '-5.1000',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Vallée du Bandama',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                64 => 
                array (
                  'city' => 'Tengréla',
                  'lat' => '10.4811',
                  'lng' => '-6.4069',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                65 => 
                array (
                  'city' => 'Lakota',
                  'lat' => '5.8500',
                  'lng' => '-5.6833',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Gôh-Djiboua',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                66 => 
                array (
                  'city' => 'Yakassé-Attobrou',
                  'lat' => '6.1817',
                  'lng' => '-3.6511',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                67 => 
                array (
                  'city' => 'Tanda',
                  'lat' => '7.8034',
                  'lng' => '-3.1683',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                68 => 
                array (
                  'city' => 'Djékanou',
                  'lat' => '6.4839',
                  'lng' => '-5.1155',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                69 => 
                array (
                  'city' => 'Botro',
                  'lat' => '7.8525',
                  'lng' => '-5.3106',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Vallée du Bandama',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                70 => 
                array (
                  'city' => 'Grand-Lahou',
                  'lat' => '5.1333',
                  'lng' => '-5.0167',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                71 => 
                array (
                  'city' => 'Kounahiri',
                  'lat' => '7.7909',
                  'lng' => '-5.8348',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Woroba',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                72 => 
                array (
                  'city' => 'Azaguié',
                  'lat' => '5.6298',
                  'lng' => '-4.0820',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lagunes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                73 => 
                array (
                  'city' => 'Guitry',
                  'lat' => '5.5195',
                  'lng' => '-5.2404',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Gôh-Djiboua',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                74 => 
                array (
                  'city' => 'Didiévi',
                  'lat' => '7.1287',
                  'lng' => '-4.8980',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                75 => 
                array (
                  'city' => 'Béoumi',
                  'lat' => '7.6740',
                  'lng' => '-5.5809',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Vallée du Bandama',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                76 => 
                array (
                  'city' => 'Kouto',
                  'lat' => '9.8903',
                  'lng' => '-6.4092',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                77 => 
                array (
                  'city' => 'Tiébissou',
                  'lat' => '7.1577',
                  'lng' => '-5.2248',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                78 => 
                array (
                  'city' => 'Doropo',
                  'lat' => '9.8103',
                  'lng' => '-3.3450',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                79 => 
                array (
                  'city' => 'Sakassou',
                  'lat' => '7.4546',
                  'lng' => '-5.2926',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Vallée du Bandama',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                80 => 
                array (
                  'city' => 'Maféré',
                  'lat' => '5.4151',
                  'lng' => '-3.0301',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                81 => 
                array (
                  'city' => 'M’Batto',
                  'lat' => '6.4720',
                  'lng' => '-4.3578',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                82 => 
                array (
                  'city' => 'Sipilou',
                  'lat' => '7.8667',
                  'lng' => '-8.1000',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                83 => 
                array (
                  'city' => 'Arrah',
                  'lat' => '6.6734',
                  'lng' => '-3.9694',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                84 => 
                array (
                  'city' => 'Bocanda',
                  'lat' => '7.0626',
                  'lng' => '-4.4995',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                85 => 
                array (
                  'city' => 'Kouassi-Kouassikro',
                  'lat' => '7.3414',
                  'lng' => '-4.6771',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                86 => 
                array (
                  'city' => 'Bloléquin',
                  'lat' => '6.5691',
                  'lng' => '-8.0025',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                87 => 
                array (
                  'city' => 'Prikro',
                  'lat' => '7.6470',
                  'lng' => '-3.9963',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                88 => 
                array (
                  'city' => 'Samatiguila',
                  'lat' => '9.8195',
                  'lng' => '-7.5608',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Denguélé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                89 => 
                array (
                  'city' => 'Boundiali',
                  'lat' => '9.5167',
                  'lng' => '-6.4833',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                90 => 
                array (
                  'city' => 'Béttié',
                  'lat' => '6.0757',
                  'lng' => '-3.4085',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                91 => 
                array (
                  'city' => 'Attiégouakro',
                  'lat' => '6.7740',
                  'lng' => '-5.1141',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Yamoussoukro',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                92 => 
                array (
                  'city' => 'Dianra',
                  'lat' => '8.9433',
                  'lng' => '-6.2549',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Woroba',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                93 => 
                array (
                  'city' => 'Mankono',
                  'lat' => '8.0586',
                  'lng' => '-6.1897',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Woroba',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                94 => 
                array (
                  'city' => 'M’Bengué',
                  'lat' => '10.0024',
                  'lng' => '-5.9004',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                95 => 
                array (
                  'city' => 'Dikodougou',
                  'lat' => '9.0676',
                  'lng' => '-5.7722',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                96 => 
                array (
                  'city' => 'Ayamé',
                  'lat' => '5.6052',
                  'lng' => '-3.1571',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Comoé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                97 => 
                array (
                  'city' => 'M’Bahiakro',
                  'lat' => '7.4573',
                  'lng' => '-4.3399',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Lacs',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                98 => 
                array (
                  'city' => 'Sandégué',
                  'lat' => '7.9538',
                  'lng' => '-3.5801',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                99 => 
                array (
                  'city' => 'Koun-Fao',
                  'lat' => '7.4876',
                  'lng' => '-3.2525',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                100 => 
                array (
                  'city' => 'Ouaninou',
                  'lat' => '8.2379',
                  'lng' => '-7.8664',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Woroba',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                101 => 
                array (
                  'city' => 'Bouna',
                  'lat' => '9.2667',
                  'lng' => '-3.0000',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                102 => 
                array (
                  'city' => 'Koro',
                  'lat' => '8.5550',
                  'lng' => '-7.4635',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Woroba',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                103 => 
                array (
                  'city' => 'Kaniasso',
                  'lat' => '9.8147',
                  'lng' => '-7.5125',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Denguélé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                104 => 
                array (
                  'city' => 'Kani',
                  'lat' => '8.4781',
                  'lng' => '-6.6051',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Woroba',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                105 => 
                array (
                  'city' => 'Dabakala',
                  'lat' => '8.3667',
                  'lng' => '-4.4333',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Vallée du Bandama',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                106 => 
                array (
                  'city' => 'Niakaramandougou',
                  'lat' => '8.6576',
                  'lng' => '-5.2911',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Vallée du Bandama',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                107 => 
                array (
                  'city' => 'Nassian',
                  'lat' => '8.4527',
                  'lng' => '-3.4715',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                108 => 
                array (
                  'city' => 'Séguélon',
                  'lat' => '9.3569',
                  'lng' => '-7.1208',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Denguélé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                109 => 
                array (
                  'city' => 'Taï',
                  'lat' => '5.8737',
                  'lng' => '-7.4552',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Montagnes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                110 => 
                array (
                  'city' => 'Madinani',
                  'lat' => '9.6108',
                  'lng' => '-6.9422',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Denguélé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                111 => 
                array (
                  'city' => 'Téhini',
                  'lat' => '9.6054',
                  'lng' => '-3.6580',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Zanzan',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                112 => 
                array (
                  'city' => 'Minignan',
                  'lat' => '9.9974',
                  'lng' => '-7.8359',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Denguélé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                113 => 
                array (
                  'city' => 'Kong',
                  'lat' => '9.1506',
                  'lng' => '-4.6103',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Savanes',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                114 => 
                array (
                  'city' => 'Gbéléban',
                  'lat' => '9.5833',
                  'lng' => '-8.1333',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Denguélé',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                115 => 
                array (
                  'city' => 'Fresco',
                  'lat' => '5.0823',
                  'lng' => '-5.5694',
                  'country' => 'Côte D’Ivoire',
                  'iso2' => 'CI',
                  'admin_name' => 'Bas-Sassandra',
                  'capital' => 'minor',
                  'population' => '',
                  'population_proper' => '',
                ),
                116 => 
                array (
                  'city' => 'Accra',
                ),
                117 => 
                array (
                  'city' => 'Lomé',
                ),
                118 => 
                array (
                  'city' => 'Abobo',
                ),
                119 => 
                array (
                  'city' => 'Adjamé',
                ),
                120 => 
                array (
                  'city' => 'Anyama',
                ),
                121 => 
                array (
                  'city' => 'Attécoubé',
                ),
                122 => 
                array (
                  'city' => 'Bingerville',
                ),
                123 => 
                array (
                  'city' => 'Cocody',
                ),
                124 => 
                array (
                  'city' => 'Koumassi',
                ),
                125 => 
                array (
                  'city' => 'Marcory',
                ),
                126 => 
                array (
                  'city' => 'Plateau',
                ),
                127 => 
                array (
                  'city' => 'Port bouët',
                ),
                128 => 
                array (
                  'city' => 'Treichville',
                ),
                129 => 
                array (
                  'city' => 'Songon',
                ),
                130 => 
                array (
                  'city' => 'Yopougon',
                )
            ); 

            foreach ($villes as $key => $value) {
                $ville = new Ville();
                $ville->setCode($value['city']);
                $ville->setLibelle($value['city']);
                $ville->setCreatedAtValue(new DateTime());
                $ville->setUpdatedAt(new DateTime());
                $villeRepository->add($ville,true);

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
                    new OA\Property(property: "montantNouvelleDemande", type: "string"),
                    new OA\Property(property: "montantRenouvellement", type: "string"),
                    new OA\Property(property: "userUpdate", type: "string"),
                    new OA\Property(property: "code", type: "string"),
                    new OA\Property(property: "chronoMax", type: "string"),
                    new OA\Property(property: "codeGeneration", type: "string"),

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
        $profession->setChronoMax($data['chronoMax']);
        $profession->setMaxCode($data['chronoMax']);
        $profession->setCodeGeneration($data['codeGeneration']);
        $profession->setCreatedAtValue(new \DateTime());
        $profession->setUpdatedAt(new \DateTime());
        $profession->setTypeProfession($typeProfessionRepository->find($data['typeProfession']));
        $profession->setMontantNouvelleDemande($data['montantNouvelleDemande']);
        $profession->setMontantRenouvellement($data['montantRenouvellement']);
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
                    new OA\Property(property: "montantNouvelleDemande", type: "string"),
                    new OA\Property(property: "montantRenouvellement", type: "string"),
                    new OA\Property(property: "codeGeneration", type: "string"),
                    new OA\Property(property: "chronoMax", type: "string"),
                    new OA\Property(property: "code", type: "string"),
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
                $profession->setChronoMax($data->chronoMax);
                $profession->setMaxCode($profession->getMaxCode() == null ? $data->chronoMax : $profession->getMaxCode());
                $profession->setCodeGeneration($data->codeGeneration);
                $profession->setTypeProfession($typeProfessionRepository->find($data->typeProfession));
                $profession->setMontantNouvelleDemande($data->montantNouvelleDemande);
                $profession->setMontantRenouvellement($data->montantRenouvellement);
                $profession->setCode($typeProfessionRepository->find($data->typeProfession)->getCode() . '_' . $data->libelle);
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
