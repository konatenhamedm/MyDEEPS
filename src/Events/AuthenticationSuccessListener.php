<?php


namespace App\Events;

use App\Controller\ApiInterface;
use App\Entity\Prestataire;
use App\Entity\User;
use App\Entity\UserFront;
use App\Entity\Utilisateur;
use App\Entity\UtilisateurSimple;
use App\Repository\ProfessionnelRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserFrontRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;


class AuthenticationSuccessListener
{
    private $userRepository;
    private $professionnelRepo;
    private $transactionRepo;
    public function __construct(UserRepository $userRepository, ProfessionnelRepository $professionnelRepo,TransactionRepository $transactionRepo)
    {
        $this->userRepository = $userRepository;
        $this->professionnelRepo = $professionnelRepo;
        $this->transactionRepo = $transactionRepo;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        $expire = true;
        $finRenouvelement = "";

        

        if ($user instanceof User) {

             // 1. Récupérer la dernière transaction
       
       

            $userData = $this->userRepository->find($user->getId());


            $data['data'] =   [
                'id' => $user->getId(),
                'role' => $userData->getRoles(),
                "expire" => $userData->getPersonne()->getStatus() == "renouvellement" ? true : false,
                "finRenouvellement" => $finRenouvelement,
                'username' => $userData->getUserIdentifier(),
                'avatar' => ($userData->getTypeUser() != "ADMINISTRATEUR")
                    ? ($userData->getAvatar()
                        ? $userData->getAvatar()->getPath() . '/' . $userData->getAvatar()->getAlt()
                        : $userData->getPersonne()->getPhoto()->getPath() . '/' . $userData->getPersonne()->getPhoto()->getAlt()
                    )
                    : null,
                'status' => $userData->getTypeUser() == "PROFESSIONNEL" ? $userData->getPersonne()->getStatus() : null,
                'nom' => $userData->getTypeUser() == "PROFESSIONNEL" ? $userData->getPersonne()->getNom() . " " . $userData->getPersonne()->getPrenoms() : null,
                'payement' => $userData->getPayement(),
                'type' => $userData->getTypeUser(),
                'personneId' => $userData->getTypeUser() == "ADMINISTRATEUR" ? null : $userData->getPersonne()->getId()
            ];

            $event->setData($data);
        }
    }
}
