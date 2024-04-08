<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Toastr;

class CustomerController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int',
            'name' => 'required|string',
            'address' => 'required|string',
            'tel' => 'required|string|regex:/^0\d{9,10}$/',
            'email' => 'required|email:rfc,dns',
        ]);
        unset($data['id']);
        $update = Customer::where('id', $request->input('id'))->update($data);
        if ($update) {
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.update'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function me(Request $request)
    {
        return view('customer.me', [
            'title' => 'Thông tin cá nhân',
            'customer' => User::with(['customer'])
                ->firstWhere('id', Auth::id())
                ->customer
        ]);
    }
}
