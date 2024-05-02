<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Toastr;

class LinkFollowController extends Controller
{
    public function create()
    {
        return view('admin.linkfollow.add', [
            'title' => 'Thêm link theo dõi'
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
            'note' => 'nullable|string',
            'link_or_post_id' => 'required|string'
        ]);
        $data['type'] = GlobalConstant::TYPE_FOLLOW;

        Link::create($data);
        Toastr::success('Tạo link theo dõi thành công', __('title.toastr.success'));

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
            'note' => 'nullable|string',
            'link_or_post_id' => 'required|string'
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
                'linkfollows' => Link::where('type', GlobalConstant::TYPE_FOLLOW)->get()
            ]);
        }

        return view('admin.linkfollow.list', [
            'title' => 'Danh sách link theo dõi',
        ]);
    }

    public function show($id)
    {
        return view('admin.linkfollow.edit', [
            'title' => 'Chi tiết link theo dõi',
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

    public function getAll()
    {
        return response()->json([
            'status' => 0,
            'linkfollows' => Link::where('type', GlobalConstant::TYPE_FOLLOW)->get()
        ]);
    }
}
