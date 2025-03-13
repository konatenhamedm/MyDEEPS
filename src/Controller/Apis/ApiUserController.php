<?php

namespace  App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\UserDTO;
use App\Entity\Administrateur;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Repository\AdministrateurRepository;
use App\Repository\ResetPasswordTokenRepository;
use App\Repository\UserRepository;
use App\Service\ResetPasswordService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    #[Route('/get/admin', methods: ['GET'])]
    /**
     * Retourne la liste des users admin.
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
    public function indexAdmin(UserRepository $userRepository): Response
    {
        try {

            $users = $userRepository->findBy(['typeUser' => 'ADMINISTRATEUR']);

            $response = $this->responseData($users, 'group_user', ['Content-Type' => 'application/json']);
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
                $response = $this->responseData($user, 'group_user', ['Content-Type' => 'application/json']);
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


    #[Route('/admin/create',  methods: ['POST'])]
    /**
     * Permet de créer un(e) user.
     */
    #[OA\Post(
        summary: "Creation user admin",
        description: "Génère un token JWT pour les administrateurs.",
        requestBody: new OA\RequestBody(
            required: true,

            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [

                        new OA\Property(property: "username", type: "string"),
                        new OA\Property(property: "password", type: "string"),
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
    public function create(Request $request, UserRepository $userRepository): Response
    {

        $names = 'document_' . '01';
        $filePrefix  = str_slug($names);
        $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);
        $uploadedFile = $request->files->get('avatar');

        try {
            $data = json_decode($request->getContent(), true);

            $personne = new Administrateur();
            $personne->setNom($request->get('nom'));
            $personne->setPrenoms($request->get('prenoms'));

            $personne->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));
            $personne->setUpdatedAt(new \DateTime());
            $personne->setCreatedAtValue(new \DateTime());
            $personne->setCreatedBy($this->userRepository->find($request->get('userUpdate')));

            $this->em->persist($personne);

            $user = new User();
            $user->setUsername($request->get('username'));
            $user->setEmail($request->get('email'));
            $user->setTypeUser($request->get('typeUser'));
            $user->setPersonne($personne());
            if ($request->get('password') != "")
                $user->setPassword($this->hasher->hashPassword($user,  $request->get('password')));

            $user->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));
            $user->setUpdatedAt(new \DateTime());
            $user->setCreatedAtValue(new \DateTime());
            $user->setCreatedBy($this->userRepository->find($request->get('userUpdate')));

            if ($uploadedFile) {
                $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedFile, self::UPLOAD_PATH);
                if ($fichier) {
                    $user->setAvatar($fichier);
                }
            }

            $errorResponse = $this->errorResponse($user);

            $errorResponse = $data['password'] !== $data['confirmPassword'] ?  $this->errorResponse($user, "Les mots de passe ne sont pas identiques") :  $this->errorResponse($user);
            if ($errorResponse !== null) {
                return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
            } else {
                $this->em->flush();
                $userRepository->add($user, true);
            }

            $response = $this->responseData($user, 'group_user', ['Content-Type' => 'application/json']);
        } catch (\Throwable $th) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        return $response;
    }

    #[Route('/membre/create',  methods: ['POST'])]
    /**
     * Permet de créer un(e) user.
     */
    #[OA\Post(
        summary: "Creation user memnbre",
        description: "Creation user memnbre",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [

                    new OA\Property(property: "password", type: "string"),
                    new OA\Property(property: "nom", type: "string"),
                    new OA\Property(property: "prenoms", type: "string"),
                    new OA\Property(property: "phone", type: "string"),
                    new OA\Property(property: "confirmPassword", type: "string"),
                    new OA\Property(property: "email", type: "string"),

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
    public function createMembre(Request $request, UserRepository $userRepository): Response
    {

        try {
            $data = json_decode($request->getContent(), true);
            $user = new User();
            $user->setUsername($data['nom'] . " " . $this->numero());
            $user->setEmail($data['email']);
            $user->setPassword($this->hasher->hashPassword($user,  $data['password']));
            $user->setRoles(['ROLE_MEMBRE']);

            $errorResponse = $data['password'] !== $data['confirmPassword'] ?  $this->errorResponse($user, "Les mots de passe ne sont pas identiques") :  $this->errorResponse($user);
            if ($errorResponse !== null) {
                return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
            } else {

                $userRepository->add($user, true);
            }

            $response = $this->responseData($user, 'group_user', ['Content-Type' => 'application/json']);
        } catch (\Throwable $th) {
            $this->setMessage("");
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
    public function update(Request $request, User $user, UserRepository $userRepository, AdministrateurRepository $administrateurRepository): Response
    {
        try {
            $data = json_decode($request->getContent());
            $names = 'document_' . '01';
            $filePrefix  = str_slug($names);
            $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);
            $uploadedFile = $request->files->get('avatar');

            if ($user != null) {
                $personne = $administrateurRepository->find($user->getPersonne()->getId());
                $personne->setNom($request->get('nom'));
                $personne->setPrenoms($request->get('prenoms'));

                $personne->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));
                $personne->setUpdatedAt(new \DateTime());
                $personne->setCreatedBy($this->userRepository->find($request->get('userUpdate')));

                $user->setUsername($request->get('username'));
                $user->setTypeUser($request->get('typeUser'));
                $user->setEmail($request->get('email'));
                if ($request->get('password') != "")
                    $user->setPassword($this->hasher->hashPassword($user,  $request->get('password')));

                $user->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));
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
                $response = $this->responseData($user, 'group_user', ['Content-Type' => 'application/json']);
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

    #[Route('/profil/update/{id}', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Modification user membre",
        description: "Permet de modifier un user MEMBRE.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "password", type: "string"),
                        new OA\Property(property: "newPassword", type: "string"),
                        new OA\Property(property: "userUpdate", type: "string"),
                        new OA\Property(property: "email", type: "string"),
                        new OA\Property(property: "avatar", type: "string", format: "binary"),
                        new OA\Property(property: "username", type: "string"),

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
    public function updateMembre(Request $request, User $user, UserRepository $userRepository): Response
    {
        try {
            $data = json_decode($request->getContent());
            $names = 'document_' . '01';
            $filePrefix  = str_slug($names);
            $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);
            $uploadedFile = $request->files->get('avatar');

            if ($user !== null) {

                $password = $request->get('password');
                $newPassword = $request->get('newPassword');
                $userUpdate = $this->userRepository->find($request->get('userUpdate'));

                if (!empty($password) && !empty($newPassword)) {
                    if (!$this->hasher->isPasswordValid($user, $password)) {
                        return $this->errorResponse($user, "L'ancien mot de passe ne correspond pas à celui qui existe en base");
                    }
                    $user->setPassword($this->hasher->hashPassword($user, $newPassword));
                }

                // Mise à jour des informations utilisateur
                $user->setUpdatedBy($userUpdate);
                $user->setUpdatedAt(new \DateTime());

                // Gestion de l'upload de l'avatar
                if ($uploadedFile) {
                    if ($fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedFile, self::UPLOAD_PATH)) {
                        $user->setAvatar($fichier);
                    }
                }

                // Vérification des erreurs
                if ($errorResponse = $this->errorResponse($user)) {
                    return $errorResponse;
                }

                $userRepository->add($user, true);

                // Retour de la réponse
                return $this->responseData($user, 'group_user', ['Content-Type' => 'application/json']);
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

    #[Route('/membre/mot/passe/oublie', methods: ['PUT', 'POST'])]
    #[OA\Post(
        summary: "Mot passe oublié",
        description: "Permet de modifier le mot de passe.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "newPassword", type: "string"),
                    new OA\Property(property: "email", type: "string"),
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
    public function motPasseOublie(Request $request, UserRepository $userRepository): Response
    {
        try {
            $data = json_decode($request->getContent());

            $user = $userRepository->findOneBy(['email' => $data["email"]]);

            if ($user != null) {
                $user->setPassword($this->hasher->hashPassword($user, $data["newPassword"]));
                $userRepository->add($user, true);
                $response = $this->responseData($user, 'group_user', ['Content-Type' => 'application/json']);
            } else {
                $this->setMessage("Cet email n'existe pas");
                $this->setStatusCode(300);
                $response = $this->response('[]');
            }
        } catch (\Throwable $th) {
            $this->setMessage("erreur serveur");
            $response = $this->response('[]');
        }

        return $response;
    }



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



    #[Route('/api/reset-password-request', methods: ['POST'])]
    public function requestResetPassword(Request $request, UserRepository $userRepo, ResetPasswordService $resetPasswordService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';

        $user = $userRepo->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $resetPasswordService->sendResetPasswordEmail($user);

        return $this->json(['message' => 'Email de réinitialisation envoyé']);
    }



    #[Route('/api/reset-password', methods: ['POST'])]
    public function resetPassword(Request $request, ResetPasswordTokenRepository $tokenRepo, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'] ?? '';
        $newPassword = $data['password'] ?? '';

        $resetToken = $tokenRepo->findOneBy(['token' => $token]);

        if (!$resetToken || $resetToken->isExpired()) {
            return $this->json(['message' => 'Token invalide ou expiré'], 400);
        }

        $user = $resetToken->getUser();
        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));

        $em->remove($resetToken); // On supprime le token après utilisation
        $em->flush();

        return $this->json(['message' => 'Mot de passe mis à jour avec succès']);
    }
}
