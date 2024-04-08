<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customer_id = $request->customer_id;
            $contracts = Contract::with(['customer', 'branch'])
            ->when($customer_id, function ($q) use ($customer_id) {
                return $q->whereHas('customer', function ($q) use ($customer_id) {
                    $q->where('id', $customer_id);
                });
            })
            ->get();
            return response()->json([
                'status' => 0,
                'contracts' => $contracts
            ]);
        }

        return view('admin.plan.list', [
            'title' => 'Danh sách kế hoạch',
            'contracts' => Contract::with('customer')->get(),
        ]);
    }
}
