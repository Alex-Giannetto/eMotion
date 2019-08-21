<?php


namespace App\Service;


use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentService
{
    /**
     * @var ParameterBagInterface
     */
    private $params;


    /**
     * PaymentService constructor.
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function createClient(User $user, string $stripeToken): User
    {
        $params = [
            'source' => $stripeToken,
            'description' => $user->getLastname().' '.$user->getFirstname(),
            'email' => $user->getEMail(),
        ];

        $url = 'https://api.stripe.com/v1/customers';

        $response = $this->executeCurl($url, $params);
        if ($response->id) {
            $user->setStripeToken($response->id);
        }

        return $user;
    }

    private function executeCurl(string $url, array $params)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERPWD => $this->params->get('privateStripeToken'),
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_POSTFIELDS => http_build_query($params),
            ]
        );

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        return $response;
    }

    public function chargeClient(User $user, float $amount): bool
    {
        $params = [
            'customer' => $user->getStripeToken(),
            'currency' => 'eur',
            'amount' => $amount * 100,
        ];

        $url = 'https://api.stripe.com/v1/charges';

        $response = $this->executeCurl($url, $params);

        return isset($response->status) && $response->status === 'succeeded' ?? false;
    }
}