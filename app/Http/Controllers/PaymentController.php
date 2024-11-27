<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Preference\Item;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class PaymentController extends Controller
{
    protected function authenticate()
    {
        // Getting the access token from .env file (create your own function)
        $mpAccessToken = env('MERCADOPAGO_ACCESS_TOKEN');
        // Set the token the SDK's config
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        // (Optional) Set the runtime environment to LOCAL if you want to test on localhost
        // Default value is set to SERVER
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    // Function that will return a request object to be sent to Mercado Pago API
    protected function createPreferenceRequest($items, $payer): array
    {
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,
            "default_installments" => 1
        ];  

        $backUrls = array(
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failed')
        );

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
            "external_reference" => "1234567890",
            "expires" => false,
            "auto_return" => 'approved',
        ];

        return $request;
    }

    public function createPaymentPreference(): ?Preference
    {
        // Authenticate MercadoPago
        $this->authenticate();

        // Fill the data about the product(s) being purchased
        $product1 = new Item();
        $product1->id = "1234567890";
        $product1->title = "Product 1 Title";
        $product1->description = "Product 1 Description";
        $product1->currency_id = "BRL";
        $product1->quantity = 12;
        $product1->unit_price = 9.90;

        $product2 = new Item();
        $product2->id = "9012345678";
        $product2->title = "Product 2 Title";
        $product2->description = "Product 2 Description";
        $product2->currency_id = "BRL";
        $product2->quantity = 5;
        $product2->unit_price = 19.90;

        // Mount the array of products that will integrate the purchase amount
        $items = [$product1, $product2];

        // Retrieve information about the user
        $user = Auth::user();

        $payer = [
            "name" => $user->name,
            "surname" => $user->surname,
            "email" => $user->email,
        ];

        // Create the request object to be sent to the API when the preference is created
        $request = $this->createPreferenceRequest($items, $payer);

        try {
            // Instantiate a new Preference
            $preference = new Preference();

            // Set the items and payer in the preference
            $preference->items = $request['items'];
            $preference->payer = $request['payer'];
            $preference->payment_methods = $request['payment_methods'];
            $preference->back_urls = $request['back_urls'];
            $preference->statement_descriptor = $request['statement_descriptor'];
            $preference->external_reference = $request['external_reference'];
            $preference->expires = $request['expires'];
            $preference->auto_return = $request['auto_return'];

            // Save the preference
            $preference->save();

            return $preference;
        } catch (MPApiException $error) {
            // Handle API error
            return null;
        }
    }

    public function success(Request $request)
    {
        return 'Pagamento aprovado! ID: ' . $request->payment_id;
    }

    public function failure()
    {
        return 'Pagamento falhou.';
    }

    public function pending()
    {
        return 'Pagamento pendente.';
    }
}
