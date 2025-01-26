<?php


namespace App\Events;

use App\Controller\ApiInterface;
use App\Entity\Prestataire;
use App\Entity\User;
use App\Entity\UserFront;
use App\Entity\Utilisateur;
use App\Entity\UtilisateurSimple;
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
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        
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
            //dd($user);

            $data['data'] =   [
                'id' => $user->getId(),
                'role' => $userData->getRoles(),
                'username' => $userData->getUserIdentifier(),
                'status' => $userData->getStatus(),
                'payement' => $userData->getPayement(),
                'type' => $userData->getTypeUser(),
            ];
           
            $event->setData($data);
        }

    }
}
