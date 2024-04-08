<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Http\Request;
use Throwable;
use Toastr;

class BranchController extends Controller
{
    public function create()
    {
        return view('admin.branch.add', [
            'title' => 'Thêm chi nhánh',
            'customers' => Customer::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'tel' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'manager' => 'required|string',
            'user_id' => 'required|numeric',
        ]);
        try {
            Branch::create($data);
            Toastr::success('Tạo chi nhánh thành công', __('title.toastr.success'));
        } catch (Throwable $e) {
            Toastr::error('Tạo chi nhánh thất bại', __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
            'address' => 'required|string',
            'tel' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'manager' => 'required|string',
            'user_id' => 'required|numeric',
        ]);
        unset($data['id']);
        $update = Branch::where('id', $request->input('id'))->update($data);
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
                'branches' => Branch::with(['user.customer'])->get(),
            ]);
        }

        return view('admin.branch.list', [
            'title' => 'Danh sách chi nhánh',
            'branches' => Branch::with(['user.customer'])->get(),
        ]);
    }

    public function show($id)
    {
        return view('admin.branch.edit', [
            'title' => 'Chi tiết chi nhánh',
            'branch' => Branch::with(['contracts.customer'])->firstWhere('id', $id),
            'customers' => Customer::all(),
        ]);
    }

    public function destroy($id)
    {
        try {
            Branch::firstWhere('id', $id)->delete();

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

    public function getBranchById(Request $request)
    {
        $id_customer = $request->id;
        $user_id = Customer::firstWhere('id', $id_customer)->user->id ?? '';
        $branches = Branch::where('user_id', $user_id)->get();
        return response()->json([
            'status' => 0,
            'data' => $branches
        ]);
    }
}
