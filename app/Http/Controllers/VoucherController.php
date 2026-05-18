<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // List all vouchers
    public function index()
    {
        $vouchers = Voucher::paginate(10);
        return view('admin.vouchers.index', ['vouchers' => $vouchers]);
    }

    // Show create form
    public function create()
    {
        return view('admin.vouchers.form', ['voucher' => null]);
    }

    // Store voucher
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'description' => 'nullable|string|max:500',
            'discount_type' => 'required|in:percentage,amount',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'max_uses' => 'required|numeric|min:1',
        ]);

        // Set discount_percentage or discount_amount based on type
        if ($validated['discount_type'] === 'percentage') {
            $validated['discount_percentage'] = $validated['discount_value'];
            $validated['discount_amount'] = null;
        } else {
            $validated['discount_amount'] = $validated['discount_value'];
            $validated['discount_percentage'] = null;
        }

        unset($validated['discount_type']);
        unset($validated['discount_value']);

        Voucher::create($validated);

        return redirect('/admin/vouchers')->with('success', 'Voucher berhasil dibuat!');
    }

    // Show edit form
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.form', ['voucher' => $voucher]);
    }

    // Update voucher
    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'description' => 'nullable|string|max:500',
            'discount_type' => 'required|in:percentage,amount',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'max_uses' => 'required|numeric|min:1',
        ]);

        // Set discount_percentage or discount_amount based on type
        if ($validated['discount_type'] === 'percentage') {
            $validated['discount_percentage'] = $validated['discount_value'];
            $validated['discount_amount'] = null;
        } else {
            $validated['discount_amount'] = $validated['discount_value'];
            $validated['discount_percentage'] = null;
        }

        unset($validated['discount_type']);
        unset($validated['discount_value']);

        $voucher->update($validated);

        return redirect('/admin/vouchers')->with('success', 'Voucher berhasil diperbarui!');
    }

    // Delete voucher
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect('/admin/vouchers')->with('success', 'Voucher berhasil dihapus!');
    }
}
