<?php
namespace App\Service;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaiementService
{
    private string $apiKey;
    private string $merchantId;
    private string $paiementUrl;

    public function __construct(
        private TransactionRepository $transactionRepository,
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $em,
        private ParameterBagInterface $params,
        
    ) {
        $this->apiKey = $params->get('API_KEY');
        $this->merchantId = $params->get('MERCHANT_ID');
        $this->paiementUrl = $params->get('PAIEMENT_URL');
    }

    public function traiterPaiement(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        $transaction = new Transaction();
        $transaction->setChannel("");
        $transaction->setReference($this->genererNumero());
        $transaction->setMontant("15000");
        $transaction->setReferenceChannel("");
        $transaction->setType("Renouvellement");
        $transaction->setState(0);
        $transaction->setCreatedAtValue(new \DateTime());
        $transaction->setUpdatedAt(new \DateTime());

        $this->transactionRepository->add($transaction, true);

        $requestData = [
            "code_paiement" => $transaction->getReference(),
            "nom_usager" => $request->get('nom'),
            "prenom_usager" => $request->get('prenoms'),
            "telephone" => $request->get('numero'),
            "email" => $request->get('email'),
            "libelle_article" => "DEMANDE D'ADHESION",
            "quantite" => 1,
            "montant" => "15000",
            "lib_order" => "PAIEMENT ONMCI",
            "Url_Retour" => "https://mydepps.pages.dev/site/professionnel",
            "Url_Callback" => "https://depps.leadagro.net/api/paiement/info-paiement"
        ];

        $response = $this->httpClient->request('POST', $this->paiementUrl, [
            'json' => $requestData,
            'headers' => [
                "ApiKey" => $this->apiKey,
                "MerchantId" => $this->merchantId,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
            'verify_peer' => false,
            'verify_host' => false
        ]);

        $dataResponse = $response->toArray();

        return [
            'code' => 200,
            'url' => $dataResponse['url'] ?? null,
            'reference' => $transaction->getReference()
        ];
    }

    private function genererNumero(): string
    {
        $query = $this->em->createQueryBuilder();
        $query->select("count(a.id)")
            ->from(Transaction::class, 'a');

        $nb = $query->getQuery()->getSingleScalarResult();
        return ('DEPPS' . date("y") . date("m") . date("d") . str_pad($nb + 1, 3, '0', STR_PAD_LEFT));
    }
}

