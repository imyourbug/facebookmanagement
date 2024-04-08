<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => 0,
                'contracts' => Contract::with(['customer', 'branch'])->get()
            ]);
        }

        return view('customer.contract.list', [
            'title' => 'Danh sách hợp đồng',
            'customer' => Customer::firstWhere('user_id', Auth::id()),
        ]);
    }

    public function detail($id)
    {
        return view('customer.contract.detail', [
            'title' => 'Chi tiết hợp đồng',
            'contract' => Contract::with([
                'tasks.details', 'tasks.type',
            ])->firstWhere('id', $id),
            'customers' => Customer::all(),
        ]);
    }
}
