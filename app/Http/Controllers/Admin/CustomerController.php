<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Toastr;

class CustomerController extends Controller
{
    public function create()
    {
        return view('admin.customer.add', [
            'title' => 'Thêm khách hàng'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $tel_or_email = $request->tel_or_email;
            $rules = [
                'tel_or_email' => !is_numeric($tel_or_email) ? 'required|email:dns,rfc'
                    : 'required|string|regex:/^0\d{9,10}$/',
                'password' => 'required|string',
                'name' => 'required|string',
                'address' => 'required|string',
                'tel' => 'required|string|regex:/^0\d{9,10}$/',
                'province' => 'nullable|string',
                'manager' => 'nullable|string',
                'website' => 'nullable|string',
                'avatar' => 'nullable|string',
                'tax_code' => 'nullable|string',
                'representative' => 'nullable|string',
                'field' => 'nullable|string',
                'email' => 'required|email:rfc,dns',
            ];
            $data = $request->validate($rules);

            $tel_or_email = $data['tel_or_email'];
            $check = User::where(is_numeric($tel_or_email) ? 'name' : 'email', $tel_or_email)
                ->get();
            if ($check->count() > 0) {
                throw new Exception('Tài khoản đã có người đăng ký!');
            }

            DB::beginTransaction();
            $user = User::create([
                is_numeric($tel_or_email) ? 'name' : 'email' =>  $tel_or_email,
                'password' => Hash::make($data['password']),
                'role' => GlobalConstant::ROLE_CUSTOMER
            ]);
            Customer::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'tel' => $data['tel'],
                'tax_code' => $data['tax_code'],
                'website' => $data['website'],
                'manager' => $data['manager'],
                'representative' => $data['representative'],
                'avatar' => $data['avatar'],
                'email' => $data['email'],
                'user_id' => $user->id
            ]);
            Toastr::success('Thành công', __('title.toastr.success'));
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int',
            'name' => 'required|string',
            'address' => 'required|string',
            'tel' => 'required|string|regex:/^0\d{9,10}$/',
            'province' => 'nullable|string',
            'manager' => 'nullable|string',
            'website' => 'nullable|string',
            'avatar' => 'nullable|string',
            'tax_code' => 'nullable|string',
            'representative' => 'nullable|string',
            'field' => 'nullable|string',
            'email' => 'required|email:rfc,dns',
        ]);
        unset($data['id']);
        $update = Customer::where('id', $request->input('id'))->update($data);
        if ($update) {
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.update'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => 0,
                'customers' => Customer::with(['user'])->get()
            ]);
        }

        return view('admin.customer.list', [
            'title' => 'Danh sách khách hàng',
            'customers' => Customer::all()
        ]);
    }

    public function show($id)
    {
        return view('admin.customer.edit', [
            'title' => 'Chi tiết khách hàng',
            'customer' => Customer::firstWhere('id', $id)
        ]);
    }

    public function detail($id)
    {
        return view('admin.customer.detail', [
            'title' => 'Chi tiết khách hàng',
            'customer' => Customer::with([
                'contracts.tasks.details', 'contracts.tasks.type',
            ])
                ->firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        try {
            User::firstWhere('id', $id)->delete();

            return response()->json([
                'status' => 0,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }
}
