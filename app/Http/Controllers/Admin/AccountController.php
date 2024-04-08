<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounts\CreateAccountRequest;
use App\Http\Requests\Admin\Accounts\UpdateAccountRequest;
use App\Models\Customer;
use App\Models\InfoUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Toastr;

class AccountController extends Controller
{
    public function create()
    {
        return view('admin.account.add', [
            'title' => 'Thêm tài khoản'
        ]);
    }

    public function store(CreateAccountRequest $request)
    {
        $tel_or_email = $request->input('tel_or_email');
        $check = User::where(is_numeric($tel_or_email) ? 'name' : 'email', $tel_or_email)
            ->get();
        if ($check->count() > 0) {
            Toastr::error('Tài khoản đã có người đăng ký!', __('title.toastr.fail'));

            return redirect()->back();
        }
        try {
            DB::beginTransaction();
            $user = User::create([
                is_numeric($tel_or_email) ? 'name' : 'email' =>  $tel_or_email,
                'password' => Hash::make($request->input('password')),
                'role' => (int) $request->role
            ]);
            switch ((int) $request->role) {
                case 0:
                    InfoUser::create([
                        'name' =>  $tel_or_email,
                        'user_id' => $user->id
                    ]);
                    break;
                case 2:
                    Customer::create([
                        'name' =>  $tel_or_email,
                        'user_id' => $user->id
                    ]);
                    break;
                default:
                    break;
            };
            Toastr::success('Tạo tài khoản thành công', __('title.toastr.success'));
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error('Tạo tài khoản thất bại', __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function update(UpdateAccountRequest $request)
    {
        $data = $request->validated();
        unset($data['id']);
        $update = User::where('id', $request->input('id'))->update($data);
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
                'accounts' => User::with(['customer', 'staff'])->get()
            ]);
        }

        return view('admin.account.list', [
            'title' => 'Danh sách tài khoản',
        ]);
    }

    public function show($id)
    {
        return view('admin.account.edit', [
            'title' => 'Chi tiết tài khoản',
            'user' => User::firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::firstWhere('id', $id);
            $user->delete();
            DB::commit();

            return response()->json([
                'status' => 0,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }
}
