<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function payment()
    {
        return view('payment.payment');
    }

    public function createOrder(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            // Create an order on Razorpay
            $order = $api->order->create([
                'amount' => $request->amount * 100, // Convert to paise
                'currency' => 'INR',
                'receipt' => 'order_rcptid_' . time(),
            ]);

            return response()->json($order);
        } catch (\Exception $e) {
            Log::error('Error creating Razorpay order: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to create order'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        try {
            $signature = $request->razorpay_signature;
            $paymentId = $request->razorpay_payment_id;
            $orderId = $request->razorpay_order_id;

            if (!$signature || !$paymentId || !$orderId) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            $generatedSignature = hash_hmac('sha256', $orderId
            . "|" . $paymentId, env('RAZORPAY_SECRET'));

            if ($generatedSignature === $signature) {
                // Payment successful, save to database or perform additional actions
                return response()->json(['status' => 'Payment successful']);
            } else {
                return response()->json(['error' => 'Signature verification failed'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error verifying payment: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to verify payment'], 500);
        }
    }
}
