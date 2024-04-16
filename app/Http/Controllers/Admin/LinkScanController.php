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
                'title' => 'required|string',
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
            $userLinks = UserLink::with(['link', 'user'])
                ->where('user_id', $data['user_id'])
                ->whereHas('link', function ($q) use ($data) {
                    $q->where('link_or_post_id', $data['link_or_post_id']);
                })
                ->get();

            if ($userLinks->count()) {
                throw new Exception('Đã tồn tại link hoặc post ID');
            }

            $userLinks = UserLink::with(['link', 'user'])
                ->where('user_id', $data['user_id'])
                ->whereHas('link', function ($q) use ($data) {
                    $q->where('is_scan', GlobalConstant::IS_ON);
                })
                ->get();

            $data['is_scan'] = $userLinks->count() < (User::firstWhere('id', $data['user_id'])->limit ?? 0)
                ? GlobalConstant::IS_ON : GlobalConstant::IS_OFF;
            $data['type'] = GlobalConstant::TYPE_SCAN;

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

        $user = User::firstWhere('id', $request->user_id);

        return view('admin.linkscan.list', [
            'title' => 'Danh sách link quét - ' . $user->email ?? $user->name,
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

    public function changeIsScan(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|integer',
                'link_id' => 'required|integer',
                'is_scan' => 'nullable|in:0,1,2',
            ]);

            if ($data['is_scan'] == GlobalConstant::IS_ON) {
                $userLinks = UserLink::with(['link', 'user'])
                    ->where('user_id', $data['user_id'])
                    ->whereHas('link', function ($q) {
                        $q->where('is_scan', GlobalConstant::IS_ON);
                    })
                    ->get();
                $limit = User::firstWhere('id', $data['user_id'])->limit ?? 0;

                if ($userLinks->count() >= $limit) {
                    return response()->json([
                        'status' => GlobalConstant::STATUS_ERROR,
                        'message' => 'Vượt quá số lượng link cho phép'
                    ]);
                }
            }

            Link::firstWhere('id', $data['link_id'])->update([
                'is_scan' => $data['is_scan']
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => GlobalConstant::STATUS_ERROR,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => GlobalConstant::STATUS_OK,
        ]);
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
