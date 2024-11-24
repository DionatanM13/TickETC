<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\Preference;
use MercadoPago\Item;

class PaymentController extends Controller
{
    public function createPayment()
    {
        // Criar uma preferência de pagamento
        $preference = new Preference();

        // Criar os itens
        $item = new Item();
        $item->title = 'Nome do Produto';
        $item->quantity = 1;
        $item->unit_price = 100.00; // Valor em Reais

        $preference->items = [$item];

        // URLs de retorno
        $preference->back_urls = [
            'success' => route('payment.success'),
            'failure' => route('payment.failure'),
            'pending' => route('payment.pending'),
        ];
        $preference->auto_return = 'approved'; // Redirecionar após aprovação

        // Salvar a preferência
        $preference->save();

        return view('checkout', ['preferenceId' => $preference->id]);
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
