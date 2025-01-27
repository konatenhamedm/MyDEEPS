<?php


namespace App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\ProfessionnelDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Professionnel;
use App\Repository\ProfessionnelRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
                $response = $this->response($professionnel);
            } else {
                $this->setMessage('Cette ressource est inexistante');
                $this->setStatusCode(300);
                $response = $this->response($professionnel);
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
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "user", type: "string"),// user

                    new OA\Property(property: "numero", type: "string"),// code_verification ..
                    new OA\Property(property: "nom", type: "string"),//first_name 
                    new OA\Property(property: "prenoms", type: "string"),
                    new OA\Property(property: "emailPro", type: "string"), //email_pro
                    new OA\Property(property: "address", type: "string"), //address
                    new OA\Property(property: "professionnel", type: "string"), //professionnel
                    new OA\Property(property: "addressPro", type: "string"), //address_pro


                    new OA\Property(property: "profession", type: "string"), //profession
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
    public function create(Request $request, ProfessionnelRepository $professionnelRepository): Response
    {

        $data = json_decode($request->getContent(), true);
        $professionnel = new Professionnel();
       
        $professionnel->setProfessionnel($data['professionnel']);
        $professionnel->setCreatedBy($this->userRepository->find($data['sender']));
        $professionnel->setUpdatedBy($this->userRepository->find($data['sender']));
        $errorResponse = $this->errorResponse($professionnel);
        if ($errorResponse !== null) {
            return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
        } else {
            $professionnelRepository->add($professionnel, true);
        }
        return $this->responseData($professionnel, 'group_pro', ['Content-Type' => 'application/json']);
    }


    #[Route('/update/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Creation de professionnel",
        description: "Permet de créer un professionnel.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "sender", type: "string"),
                    new OA\Property(property: "receiver", type: "string"),
                    new OA\Property(property: "professionnel", type: "string"),
                    new OA\Property(property: "userUpdate", type: "string"),

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
    public function update(Request $request, Professionnel $professionnel, ProfessionnelRepository $professionnelRepository): Response
    {
        try {
            $data = json_decode($request->getContent());
            if ($professionnel != null) {

                $professionnel->setSender($this->userRepository->find($data->sender));
                $professionnel->setSender($this->userRepository->find($data->receiver));
                $professionnel->setProfessionnel($data->professionnel);
                $professionnel->setUpdatedBy($this->userRepository->find($data->userUpdate));
                $professionnel->setUpdatedAt(new \DateTime());
                $errorResponse = $this->errorResponse($professionnel);

                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {
                    $professionnelRepository->add($professionnel, true);
                }



                // On retourne la confirmation
                $response = $this->responseData($professionnel, 'group1', ['Content-Type' => 'application/json']);
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