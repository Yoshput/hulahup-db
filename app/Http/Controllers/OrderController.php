<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * API endpoint untuk save order ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.qty' => 'required|integer|min:1',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();
            $totalAmount = (float) $request->total_amount;
            $paymentMethod = $request->payment_method;
            
            // DEFENSIVE PROGRAMMING: Jika bayar pakai Saldo TyU-Pay, validasi saldo mencukupi
            if ($paymentMethod === 'Saldo TyU-Pay') {
                if ($user->balance < $totalAmount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memesan! Saldo TyU-Pay kamu tidak mencukupi. Saldo: Rp ' . number_format($user->balance, 0, ',', '.') . ', Total: Rp ' . number_format($totalAmount, 0, ',', '.') . '. Silakan lakukan top up terlebih dahulu.',
                        'balance' => $user->balance,
                        'required' => $totalAmount,
                    ], 400);
                }
            }
            
            // Generate order number
            $orderNumber = Order::generateOrderNumber();
            
            // Create order - Store both payment method and discount info in notes
            $notesParts = [];
            $notesParts[] = 'Pembayaran: ' . $paymentMethod;
            if ($request->notes) {
                $notesParts[] = $request->notes;
            }
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'items' => $request->items,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => implode(' | ', $notesParts),
            ]);
            
            // Jika bayar pakai Saldo TyU-Pay, potong saldo otomatis
            if ($paymentMethod === 'Saldo TyU-Pay') {
                $user->decrement('balance', $totalAmount);
                $user->refresh();
                $successMsg = 'Pesanan berhasil dibuat! Saldo TyU-Pay kamu otomatis terpotong.';
            } else {
                // Untuk metode lain (QRIS, E-Wallet): order dibuat tanpa potong saldo
                $successMsg = 'Pesanan berhasil dibuat! Tunggu konfirmasi dari sistem pembayaran.';
            }

            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'order' => $order,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'balance_remaining' => $user->balance,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
