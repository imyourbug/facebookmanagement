<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounts\CreateAccountRequest;
use App\Http\Requests\Admin\Accounts\UpdateAccountRequest;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserRole;
use Exception;
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

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'password' => 'required|string',
                'delay' => 'required|integer',
                'limit' => 'required|integer',
                'limit_follow' => 'required|integer',
                'expire' => 'required|integer',
                'role' => 'required|in:0,1',
                'roles' => 'nullable|array',
                'roles.*' => 'nullable|integer|in:0,1,2,3',
            ]);
            $check = User::firstWhere('name', $data['name']);
            if ($check) {
                throw new Exception('Tài khoản đã có người đăng ký!');
            }
            DB::beginTransaction();
            $user = User::create([
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'delay' => $data['delay'],
                'limit' => $data['limit'],
                'limit_follow' => $data['limit_follow'],
                'expire' => $data['expire'],
            ]);
            $data['roles'] = array_map(function ($item) use ($user) {
                return [
                    'user_id' => $user->id,
                    'role' => $item,
                    'name' => GlobalConstant::ROLE_ALL[$item],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $data['roles']);
            UserRole::insert($data['roles']);
            Toastr::success('Tạo tài khoản thành công', __('title.toastr.success'));
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function update(UpdateAccountRequest $request)
    {
        $data = $request->validated();
        foreach ($data as $key => &$item) {
            if (!strlen($item)) {
                unset($data[$key]);
            }
            if ($key === 'password') {
                $item = Hash::make($item);
            }
        }
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
                'accounts' => User::all()
            ]);
        }

        return view('admin.account.list', [
            'title' => 'Danh sách tài khoản',
            'roles' => GlobalConstant::ROLE_ALL,
            'setting' => Setting::all()->pluck('value', 'key')->toArray(),
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
