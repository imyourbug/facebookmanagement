<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\User;
use App\Models\UserLink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
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
        try {
            $data = $request->validate([
                'user_id' => 'required|string',
                'title' => 'nullable|string',
                'time' => 'nullable|string',
                'content' => 'nullable|string',
                'comment_first' => 'nullable|string',
                'comment_second' => 'nullable|string',
                'data_first' => 'nullable|string',
                'data_second' => 'nullable|string',
                'emotion_first' => 'nullable|string',
                'emotion_second' => 'nullable|string',
                'is_scan' => 'nullable|in:0,1,2',
                'note' => 'nullable|string',
                'link_or_post_id' => 'required|string',
            ]);
            $user = User::firstWhere('id', $data['user_id']);

            $userLinks = UserLink::with(['link', 'user'])
                ->where('user_id', $user->id)
                ->whereHas('link', function ($q) use ($data) {
                    $q->where('type', GlobalConstant::TYPE_SCAN);
                })
                ->get();
            if ($userLinks->count() >= $user->limit) {
                throw new Exception('Đã quá giới hạn link được thêm');
            }

            $userLinks = UserLink::with(['link', 'user'])
                ->where('user_id', $user->id)
                ->whereHas('link', function ($q) use ($data) {
                    $q->where('link_or_post_id', $data['link_or_post_id']);
                })
                ->get();

            if ($userLinks->count()) {
                throw new Exception('Đã tồn tại link hoặc post ID');
            }

            $data['is_scan'] = GlobalConstant::IS_OFF;
            $data['type'] = GlobalConstant::TYPE_SCAN;

            // check link_or_post_id
            if (!is_numeric($data['link_or_post_id'])) {
                if (!(str_contains($data['link_or_post_id'], 'videos') || str_contains($data['link_or_post_id'], 'reel'))) {
                    throw new Exception('Link không đúng định dạng');
                }
                $link_or_post_id = explode('/', $data['link_or_post_id']);
                $data['link_or_post_id'] = $link_or_post_id[count($link_or_post_id) - 1];
            }

            DB::beginTransaction();
            $link = Link::firstOrCreate(
                ['link_or_post_id' => $data['link_or_post_id']],
                [
                    'title' =>  $data['title'],
                    'is_scan' => $data['is_scan'],
                    'type' => $data['type'],
                ]
            );
            UserLink::create([
                'user_id' => $data['user_id'],
                'link_id' => $link->id
            ]);
            Toastr::success('Thêm thành công', 'Thông báo');
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), 'Thông báo');
        }

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

        $user = User::firstWhere('id', $request->user_id);

        return view('admin.linkscan.list', [
            'title' => 'Danh sách link quét - ' . $user->name ?? $user->email,
            'user' => $user
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
