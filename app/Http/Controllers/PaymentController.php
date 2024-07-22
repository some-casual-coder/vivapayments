<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VivaPaymentService;

class PaymentController extends Controller
{
    protected $vivaPaymentService;

    public function __construct(VivaPaymentService $vivaPaymentService)
    {
        $this->vivaPaymentService = $vivaPaymentService;
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer',
            'customerTrns' => 'required|string',
            'email' => 'required|email',
            'fullName' => 'required|string',
            'requestLang' => 'required|string'
        ]);

        $amount = $request->input('amount');
        $customerDetails = [
            'customerTrns' => $request->input('customerTrns'),
            'email' => $request->input('email'),
            'fullName' => $request->input('fullName'),
            'requestLang' => $request->input('requestLang')
        ];

        $order = $this->vivaPaymentService->createOrder($amount, $customerDetails);

        return response()->json($order);
    }
}
