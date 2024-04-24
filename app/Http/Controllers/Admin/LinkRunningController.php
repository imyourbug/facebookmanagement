<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Throwable;
use Toastr;

class LinkRunningController extends Controller
{
    public function create()
    {
        return view('admin.linkrunning.add', [
            'title' => 'Thêm link đang chạy'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'content' => 'nullable|string',
            'comment' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'is_scan' => 'nullable|in:0,1',
            'note' => 'nullable|string',
            'link_or_post_id' => 'required|string'
        ]);
        $data['type'] = GlobalConstant::TYPE_RUNNING;

        Link::create($data);
        Toastr::success('Tạo link đang chạy thành công', __('title.toastr.success'));

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'title' => 'nullable|string',
            'content' => 'nullable|string',
            'comment' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'is_scan' => 'nullable|in:0,1',
            'note' => 'nullable|string',
            'link_or_post_id' => 'required|string',
            'delay' => 'required|string'
        ]);
        unset($data['id']);
        $update = Link::where('id', $request->input('id'))->update($data);

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
                'linkrunnings' => Link::where('type', GlobalConstant::TYPE_RUNNING)->get()
            ]);
        }

        return view('admin.linkrunning.list', [
            'title' => 'Danh sách link đang chạy',
        ]);
    }

    public function show($id)
    {
        return view('admin.linkrunning.edit', [
            'title' => 'Chi tiết link đang chạy',
            'link' => Link::firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        try {
            $link = Link::firstWhere('id', $id);
            $link->delete();

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

    public function changeIsScan(Request $request)
    {
        Link::where('id', $request->id)->update([
            'is_scan' => $request->is_scan
        ]);

        return response()->json([
            'status' => 0,
        ]);
    }

    public function getAll()
    {
        return response()->json([
            'status' => 0,
            'linkrunnings' => Link::where('type', GlobalConstant::TYPE_RUNNING)->get()
        ]);
    }

    public function follow(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer',
            ]);
            Link::where('id', $data['id'])->update([
                'type' =>  GlobalConstant::TYPE_FOLLOW
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 0,
        ]);
    }
}
