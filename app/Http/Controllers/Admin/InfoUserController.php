<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Controller;
use App\Models\InfoUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Toastr;

class InfoUserController extends Controller
{

    public function __construct(private AccountController $controller)
    {
    }

    public function create()
    {
        return view('admin.staff.add', [
            'title' => 'Thêm nhân viên'
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
                'avatar' => 'nullable|string',
                'position' => 'required|string',
                'identification' => 'required|string|regex:/\d{12}$/',
                'tel' => 'required|string|regex:/^0\d{9,10}$/',
                'active' => 'required|in:0,1',
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
            InfoUser::create([
                'name' => $data['name'],
                'avatar' => $data['avatar'],
                'position' => $data['position'],
                'identification' => $data['identification'],
                'tel' => $data['tel'],
                'active' => $data['active'],
                'user_id' => $user->id
            ]);
            Toastr::success('Tạo thành công', __('title.toastr.success'));
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
            'avatar' => 'required|string',
            'name' => 'required|string',
            'position' => 'required|string',
            'identification' => 'required|string|regex:/\d{12}$/',
            'tel' => 'required|string|regex:/^0\d{9,10}$/',
            'active' => 'required|in:0,1',
        ]);
        unset($data['id']);
        $update = InfoUser::where('id', $request->input('id'))->update($data);
        if ($update) {
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.update'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function show($id)
    {
        return view('admin.staff.edit', [
            'title' => 'Chi tiết nhân viên',
            'staff' => InfoUser::firstWhere('id', $id)
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => 0,
                'staff' => InfoUser::with(['user'])->get()
            ]);
        }

        return view('admin.staff.list', [
            'title' => 'Danh sách nhân viên',
        ]);
    }



    public function destroy($user_id)
    {
        return $this->controller->destroy($user_id);
    }
}
