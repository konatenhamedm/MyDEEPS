<?php


namespace App\Events;

use App\Controller\ApiInterface;
use App\Entity\Prestataire;
use App\Entity\User;
use App\Entity\UserFront;
use App\Entity\Utilisateur;
use App\Entity\UtilisateurSimple;
use App\Repository\ProfessionnelRepository;
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
    public function __construct(UserRepository $userRepository,ProfessionnelRepository $professionnelRepo)
    {
        $this->userRepository = $userRepository;
        $this->professionnelRepo = $professionnelRepo;
        
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if ($user instanceof User) {
            
            
            $userData = $this->userRepository->find($user->getId());
           

            $data['data'] =   [
                'id' => $user->getId(),
                'role' => $userData->getRoles(),
                'username' => $userData->getUserIdentifier(),
                'avatar' => $userData->getAvatar() ? $userData->getAvatar()->getPath() .'/'. $userData->getAvatar()->getAlt() : null,
                'status' => $userData->getTypeUser() == "PROFESSIONNEL" ? $userData->getPersonne()->getStatus() : null,
                'nom' => $userData->getTypeUser() == "PROFESSIONNEL" ? $userData->getPersonne()->getNom() ." ".$userData->getPersonne()->getPrenoms() : null,
                'payement' => $userData->getPayement(),
                'type' => $userData->getTypeUser(),
                'personneId'=> $userData->getTypeUser() == "ADMINISTRATEUR" ? null : $userData->getPersonne()->getId()
            ];
           
            $event->setData($data);
        }

    }
}
