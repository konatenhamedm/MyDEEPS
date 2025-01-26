<?php

namespace  App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\UserDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/user')]
class ApiUserController extends ApiInterface
{

    #[Route('/', methods: ['GET'])]
    /**
     * Retourne la liste des users.
     * 
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'user')]
    // #[Security(name: 'Bearer')]
    public function index(UserRepository $userRepository): Response
    {
        try {

            $users = $userRepository->findAll();

            $context = [AbstractNormalizer::GROUPS => 'group1'];
            $json = $this->serializer->serialize($users, 'json', $context);

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
     * Affiche un(e) user en offrant un identifiant.
     */
    #[OA\Response(
        response: 200,
        description: 'Affiche un(e) user en offrant un identifiant',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['full']))
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'user')]
    //#[Security(name: 'Bearer')]
    public function getOne(?User $user)
    {
        try {
            if ($user) {
                $response = $this->response($user);
            } else {
                $this->setMessage('Cette ressource est inexistante');
                $this->setStatusCode(300);
                $response = $this->response($user);
            }
        } catch (\Exception $exception) {
            $this->setMessage($exception->getMessage());
            $response = $this->response('[]');
        }


        return $response;
    }


    #[Route('/admin/create',  methods: ['POST'])]
    /**
     * Permet de créer un(e) user.
     */
    #[OA\Post(
        summary: "Creation user admin",
        description: "Génère un token JWT pour les administrateurs.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "username", type: "string"),
                    new OA\Property(property: "password", type: "string"),
                    new OA\Property(property: "nom", type: "string"),
                    new OA\Property(property: "prenoms", type: "string"),
                    new OA\Property(property: "phone", type: "string"),
                    new OA\Property(property: "confirmPassword", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "userUpdate", type: "string"),

                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'user')]
    #[Security(name: 'Bearer')]
    public function create(Request $request, UserRepository $userRepository): Response
    {

        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($this->hasher->hashPassword($user,  $data['password']));
        $user->setNom($data['nom']);
        $user->setPrenoms($data['prenoms']);
        $user->setPhone($data['phone']);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCreatedBy($this->userRepository->find($data['userUpdate']));
        $user->setUpdatedBy($this->userRepository->find($data['userUpdate']));
        $errorResponse = $data['password'] !== $data['confirmPassword'] ?  $this->errorResponse($user,"Les mots de passe ne sont pas identiques") :  $this->errorResponse($user) ;
        if ($errorResponse !== null) {
            return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
        } else {

            $userRepository->add($user, true);
        }

        return $this->responseData($user, 'group1', ['Content-Type' => 'application/json']);
    }


    #[Route('/admin/update/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Modification user admin",
        description: "Permet de créer un user.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [

                        new OA\Property(property: "username", type: "string"),
                        new OA\Property(property: "password", type: "string"),
                        new OA\Property(property: "nom", type: "string"),
                        new OA\Property(property: "prenoms", type: "string"),
                        new OA\Property(property: "phone", type: "string"),
                        new OA\Property(property: "email", type: "string"),
                        new OA\Property(property: "avatar", type: "string", format: "binary"),
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
    #[OA\Tag(name: 'user')]
    #[Security(name: 'Bearer')]
    public function update(Request $request, User $user, UserRepository $userRepository): Response
    {
        try {
            $data = json_decode($request->getContent());
            $names = 'document_' . '01';
            $filePrefix  = str_slug($names);
            $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);
            $uploadedFile = $request->files->get('avatar');

            if ($user != null) {


                $user->setUsername($data['username']);
                $user->setEmail($data['email']);
                if($data['password'] != null)
                    $user->setPassword($this->hasher->hashPassword($user,  $data['password']));
                $user->setNom($data['nom']);
                $user->setPrenoms($data['prenoms']);
                $user->setPhone($data['phone']);
                $user->setUpdatedBy($this->userRepository->find($data->userUpdate));
                $user->setUpdatedAt(new \DateTime());

                if ($uploadedFile) {
                    $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedFile, self::UPLOAD_PATH);
                    if ($fichier) {
                        $user->setAvatar($fichier);
                    }
                }

                $errorResponse = $this->errorResponse($user);

                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {
                    $userRepository->add($user, true);
                }



                // On retourne la confirmation
                $response = $this->responseData($user, 'group1', ['Content-Type' => 'application/json']);
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
     * permet de supprimer un(e) user.
     */
    #[OA\Response(
        response: 200,
        description: 'permet de supprimer un(e) user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'user')]
    //#[Security(name: 'Bearer')]
    public function delete(Request $request, User $user, UserRepository $villeRepository): Response
    {
        try {

            if ($user != null) {

                $villeRepository->remove($user, true);

                // On retourne la confirmation
                $this->setMessage("Operation effectuées avec success");
                $response = $this->response($user);
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
     * Permet de supprimer plusieurs user.
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'user')]
    #[Security(name: 'Bearer')]
    public function deleteAll(Request $request, UserRepository $villeRepository): Response
    {
        try {
            $data = json_decode($request->getContent());

            foreach ($data->ids as $key => $value) {
                $user = $villeRepository->find($value['id']);

                if ($user != null) {
                    $villeRepository->remove($user);
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
