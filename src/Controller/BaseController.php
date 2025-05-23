<?php


namespace App\Controller;


use App\Controller\FileTrait;
use App\Service\Menu;
use App\Service\NotificationService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    use FileTrait;

    protected const UPLOAD_PATH = 'media_entreprise';
    protected $em;
    protected $security;
    protected $menu;
    protected $hasher;
    protected $workflow;
    protected $entreprise;
    protected $notificationService;


    #[Route(path: '/print-iframe', name: 'default_print_iframe', methods: ["DELETE", "GET"], condition: "request.query.get('r')", options: ["expose" => true])]
    public function defaultPrintIframe(Request $request, UrlGeneratorInterface $urlGenerator)
    {
        $all = $request->query->all();
        //print-iframe?r=foo_bar_foo&params[']
        $routeName = $request->query->get('r');
        $title = $request->query->get('title');
        $params = $all['params'] ?? [];
        $stacked = $params['stacked'] ?? false;
        $redirect = isset($params['redirect']) ? $urlGenerator->generate($params['redirect'], $params) : '';
        $iframeUrl = $urlGenerator->generate($routeName, $params);

        $isFacture = isset($params['mode']) && $params['mode'] == 'facture' && $routeName == 'facturation_facture_print';

        return $this->render('home/iframe.html.twig', [
            'iframe_url' => $iframeUrl,
            'id' => $params['id'] ?? null,
            'stacked' => $stacked,
            'redirect' => $redirect,
            'title' => $title,
            'facture' => 0/*$isFacture*/
        ]);
    }


   /*  #[Route('/seconde', name: 'app_home_seconde')]
    public function updateData(): Response
    {

        // ðŸ‘‡ recuepere le dernier jour du mois
        $lastDayOfMonth = (new DateTime())->modify("last day of this month")->format("d");
        $lastDate = (new DateTime())->modify("last day of this month")->format("Y-m-d");

        $contrats = $this->contratRepository->findBy(['etat' => 'Actif']);

        foreach ($contrats as $key => $contrat) {
            //$verifFactureExist = $this->factureRepository->findOneBy();
            if ($contrat->isFirstPay() == false) {
                // dd((int)$contrat->getDateProchainVersement()->format("d"));{}

                if ($contrat->getDateProchainVersement()->format("Y-m-d") == (new DateTime())->format("Y-m-d")) {

                    $this->creationFacture($contrat, false);
                    /// $this->factureRepository->add($facture, true);
                }
            } else {
                if ($contrat->getJourReceptionFacture()  == null) {
                    if ($lastDate == (new DateTime())->format("Y-m-d")) {
                        if ($this->factureRepository->findOneBy(['contrat' => $contrat, 'mois' => $this->moisRepository->findOneByNumero((int)$lastDayOfMonth)]) == null) {

                            $this->creationFacture($contrat, false);
                        }
                    }
                } else {

                    if ($lastDayOfMonth == $contrat->getJourReceptionFacture()) {
                        if ($this->factureRepository->findOneBy(['contrat' => $contrat, 'mois' => $this->moisRepository->findOneByNumero((int)$contrat->getJourReceptionFacture())]) == null) {

                            $this->creationFacture($contrat, true);
                        }
                    }
                }
            }
        }




        return $this->json("rrrr");
    } */
}
