<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chemistry;
use Illuminate\Http\Request;
use Throwable;
use Toastr;

class ChemistryController extends Controller
{
    public function create()
    {
        return view('admin.chemistry.add', [
            'title' => 'Thêm hóa chất'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'name' => 'nullable|string',
            'number_regist' => 'nullable|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'supplier' => 'nullable|string',
            'active' => 'required|in:0,1',
        ]);
        try {
            Chemistry::create($data);
            Toastr::success('Tạo hóa chất thành công', __('title.toastr.success'));
        } catch (Throwable $e) {
            Toastr::error('Tạo hóa chất thất bại', __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|numeric',
            'code' => 'required|string',
            'name' => 'nullable|string',
            'number_regist' => 'nullable|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'supplier' => 'nullable|string',
            'active' => 'required|in:0,1',
        ]);
        unset($data['id']);
        $update = Chemistry::where('id', $request->input('id'))->update($data);
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
                'chemistries' => Chemistry::all()
            ]);
        }

        return view('admin.chemistry.list', [
            'title' => 'Danh sách hóa chất',
            'chemistries' => Chemistry::all()
        ]);
    }

    public function show($id)
    {
        return view('admin.chemistry.edit', [
            'title' => 'Chi tiết hóa chất',
            'chemistry' => Chemistry::firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        try {
            Chemistry::firstWhere('id', $id)->delete();

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
