<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Toastr;

class LinkScanController extends Controller
{
    public function create()
    {
        return view('admin.linkscan.add', [
            'title' => 'Thêm link quét'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'nullable|string',
            'comment' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'is_scan' => 'nullable|in:0,1',
            'note' => 'nullable|string',
            'link_or_post_id' => 'required|string'
        ]);
        $data['type'] = GlobalConstant::TYPE_SCAN;

        Link::create($data);
        Toastr::success('Tạo link quét thành công', __('title.toastr.success'));

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'title' => 'required|string',
            'content' => 'nullable|string',
            'comment' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'is_scan' => 'nullable|in:0,1',
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
                'linkscans' => Link::where('type', GlobalConstant::TYPE_SCAN)->get()
            ]);
        }

        return view('admin.linkscan.list', [
            'title' => 'Danh sách link quét',
        ]);
    }

    public function show($id)
    {
        return view('admin.linkscan.edit', [
            'title' => 'Chi tiết link quét',
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
        $user = User::firstWhere('id', $request->user_id);
        $userLinks = UserLink::with(['link', 'user'])
            ->where('user_id', $user->id)
            ->where('link_id', $request->link_id)
            ->whereHas('link', function ($q) {
                $q->where('is_scan', GlobalConstant::IS_ON);
            })
            ->get();

        $limit = $user->limit;

        $response = [
            'status' => GlobalConstant::STATUS_OK,
        ];
        if ($userLinks->count() >= $limit) {
            $response['status'] = GlobalConstant::STATUS_ERROR;
            $response['message'] = 'Vượt quá số lượng link cho phép';
        }

        return $response;
    }

    public function getAll()
    {
        return response()->json([
            'status' => 0,
            'linkscans' => Link::where('type', GlobalConstant::TYPE_SCAN)->get()
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
