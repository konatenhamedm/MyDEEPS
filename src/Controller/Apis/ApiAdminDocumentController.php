<?php

namespace  App\Controller\Apis;

use App\Controller\Apis\Config\ApiInterface;
use App\DTO\AdminDocumentDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\AdminDocument;
use App\Repository\AdminDocumentRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/adminDocument')]
class ApiAdminDocumentController extends ApiInterface
{



    #[Route('/', methods: ['GET'])]
    /**
     * Retourne la liste des adminDocuments.
     * 
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AdminDocument::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'adminDocument')]
    // #[Security(name: 'Bearer')]
    public function index(AdminDocumentRepository $adminDocumentRepository): Response
    {
        try {

            $adminDocuments = $adminDocumentRepository->findAll();

            $response =  $this->responseData($adminDocuments, 'group1', ['Content-Type' => 'application/json']);
        } catch (\Exception $exception) {
            $this->setMessage("");
            $response = $this->response('[]');
        }

        // On envoie la réponse
        return $response;
    }


    #[Route('/get/one/{id}', methods: ['GET'])]
    /**
     * Affiche un(e) adminDocument en offrant un identifiant.
     */
    #[OA\Response(
        response: 200,
        description: 'Affiche un(e) adminDocument en offrant un identifiant',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AdminDocument::class, groups: ['full']))
        )
    )]
    #[OA\Parameter(
        name: 'code',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'adminDocument')]
    //#[Security(name: 'Bearer')]
    public function getOne(?AdminDocument $adminDocument)
    {
        try {
            if ($adminDocument) {
                $response = $this->response($adminDocument);
            } else {
                $this->setMessage('Cette ressource est inexistante');
                $this->setStatusCode(300);
                $response = $this->response($adminDocument);
            }
        } catch (\Exception $exception) {
            $this->setMessage($exception->getMessage());
            $response = $this->response('[]');
        }


        return $response;
    }


    #[Route('/create',  methods: ['POST'])]
    /**
     * Permet de créer un(e) doc.
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

                        new OA\Property(property: "libelle", type: "string"),
                        new OA\Property(property: "path", type: "string", format: "binary"),
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
    #[OA\Tag(name: 'adminDocument')]
    #[Security(name: 'Bearer')]
    public function create(Request $request, AdminDocumentRepository $adminDocumentRepository): Response
    {

        $data = json_decode($request->getContent(), true);

        $names = 'document_' . '01';
        $filePrefix  = str_slug($names);
        $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);
        $uploadedFile = $request->files->get('path');


        $adminDocument = new AdminDocument();
        $adminDocument->setLibelle($request->get('libelle'));
        $adminDocument->setCreatedAtValue(new \DateTime());
        $adminDocument->setUpdatedAt(new \DateTime());
        $adminDocument->setCreatedBy($this->userRepository->find($request->get('userUpdate')));
        $adminDocument->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));
        $errorResponse = $this->errorResponse($adminDocument);

        if ($uploadedFile) {
            $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedFile, self::UPLOAD_PATH);
            if ($fichier) {

                $adminDocument->setPath($fichier);
            }
        }

        if ($errorResponse !== null) {
            return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
        } else {

            $adminDocumentRepository->add($adminDocument, true);
        }

        return $this->responseData($adminDocument, 'group1', ['Content-Type' => 'application/json']);
    }


    #[Route('/update/{id}', methods: ['PUT', 'POST'])]
    /**
     * Permet mettre à jour doc.
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

                        new OA\Property(property: "libelle", type: "string"),
                        new OA\Property(property: "path", type: "string", format: "binary"),
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
    #[OA\Tag(name: 'adminDocument')]
    #[Security(name: 'Bearer')]
    public function update(Request $request, AdminDocument $adminDocument, AdminDocumentRepository $adminDocumentRepository): Response
    {
        try {
            $data = json_decode($request->getContent());

            $names = 'document_' . '01';
            $filePrefix  = str_slug($names);
            $filePath = $this->getUploadDir(self::UPLOAD_PATH, true);
            $uploadedFile = $request->files->get('path');

            if ($adminDocument != null) {

                $adminDocument->setLibelle($request->get('libelle'));
                $adminDocument->setUpdatedAt(new \DateTime());
                $adminDocument->setUpdatedBy($this->userRepository->find($request->get('userUpdate')));
                $errorResponse = $this->errorResponse($adminDocument);

                if ($uploadedFile) {
                    $fichier = $this->utils->sauvegardeFichier($filePath, $filePrefix, $uploadedFile, self::UPLOAD_PATH);
                    if ($fichier) {

                        $adminDocument->setPath($fichier);
                    }
                }

                if ($errorResponse !== null) {
                    return $errorResponse; // Retourne la réponse d'erreur si des erreurs sont présentes
                } else {

                    $adminDocumentRepository->add($adminDocument, true);
                }
                // On retourne la confirmation
                $response = $this->responseData($adminDocument, 'group1', ['Content-Type' => 'application/json']);
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
     * permet de supprimer un(e) adminDocument.
     */
    #[OA\Response(
        response: 200,
        description: 'permet de supprimer un(e) adminDocument',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AdminDocument::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'adminDocument')]
    //#[Security(name: 'Bearer')]
    public function delete(Request $request, AdminDocument $adminDocument, AdminDocumentRepository $villeRepository): Response
    {
        try {

            if ($adminDocument != null) {

                $villeRepository->remove($adminDocument, true);

                // On retourne la confirmation
                $this->setMessage("Operation effectuées avec success");
                $response = $this->response($adminDocument);
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
     * Permet de supprimer plusieurs adminDocument.
     */
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AdminDocument::class, groups: ['full']))
        )
    )]
    #[OA\Tag(name: 'adminDocument')]
    #[Security(name: 'Bearer')]
    public function deleteAll(Request $request, AdminDocumentRepository $villeRepository): Response
    {
        try {
            $data = json_decode($request->getContent());

            foreach ($data->ids as $key => $value) {
                $adminDocument = $villeRepository->find($value['id']);

                if ($adminDocument != null) {
                    $villeRepository->remove($adminDocument);
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
