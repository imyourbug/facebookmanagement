<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Throwable;
use Toastr;

class TypeController extends Controller
{
    public function create()
    {
        return view('admin.type.add', [
            'title' => 'Thêm loại nhiệm vụ',
            'types' => Type::all()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|string',
            'parent_id' => 'required|numeric',
        ]);
        try {
            Type::create($data);
            Toastr::success('Tạo loại nhiệm vụ thành công', __('title.toastr.success'));
        } catch (Throwable $e) {
            Toastr::error('Tạo loại nhiệm vụ thất bại', __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int',
            'name' => 'required|string',
            'image' => 'nullable|string',
            'parent_id' => 'required|numeric',
        ]);
        unset($data['id']);
        $update = Type::where('id', $request->input('id'))->update($data);
        if ($update) {
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.update'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function delete($id)
    {
        $delete = Type::firstWhere('id', $id)->delete();
        if ($delete) {
            Toastr::success(__('message.success.delete'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.delete'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => 0,
                'types' => Type::with(['parent'])->get()
            ]);
        }

        return view('admin.type.list', [
            'title' => 'Danh sách loại nhiệm vụ',
            'types' => Type::all()
        ]);
    }

    public function show($id)
    {
        return view('admin.type.edit', [
            'title' => 'Chi tiết loại nhiệm vụ',
            'type' => Type::firstWhere('id', $id),
            'types' => Type::all()
        ]);
    }

    public function destroy($id)
    {
        try {
            Type::firstWhere('id', $id)->delete();

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

    public function getTypeByParentId(Request $request)
    {
        return response()->json([
            'status' => 0,
            'data' => Type::where('parent_id', $request->id)
                ->get()
        ]);;
    }
}
