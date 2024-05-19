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

class LinkFollowController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|string',
                'title' => 'nullable|string',
                'time' => 'nullable|string',
                'content' => 'nullable|string',
                'comment' => 'nullable|string',
                'diff_comment' => 'nullable|string',
                'data' => 'nullable|string',
                'diff_data' => 'nullable|string',
                'reaction' => 'nullable|string',
                'diff_reaction' => 'nullable|string',
                'is_scan' => 'nullable|in:0,1,2',
                'note' => 'nullable|string',
                'link_or_post_id' => 'required|string',
                'user_id' => 'required|string',
            ]);
            $user = User::firstWhere('id', $data['user_id']);

            $userLinks = UserLink::with(['link', 'user'])
                ->where('user_id', $user->id)
                ->where('type', GlobalConstant::TYPE_FOLLOW)
                ->get();
            if ($userLinks->count() >= $user->limit) {
                throw new Exception('Đã quá giới hạn link được thêm');
            }

            $userLink = UserLink::with(['link', 'user'])
                ->where('user_id', $user->id)
                ->whereHas('link', function ($q) use ($data) {
                    $q->where('link_or_post_id', $data['link_or_post_id']);
                })
                ->first();

            if ($userLink) {
                throw new Exception('Đã tồn tại link hoặc post ID bên bảng '
                    . ($userLink->type == GlobalConstant::TYPE_SCAN ? 'link quét' : 'link theo dõi'));
            }

            $data['is_scan'] = GlobalConstant::IS_OFF;
            $data['type'] = GlobalConstant::TYPE_FOLLOW;
            $data['status'] = GlobalConstant::STATUS_RUNNING;
            $data['delay'] = $user->delay;

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
                    'delay' => $data['delay'],
                    'status' => $data['status'],
                ]
            );
            $userLink =  UserLink::withTrashed()
                ->where('link_id', $link->id,)
                ->where('user_id', $data['user_id'])
                ->first();

            if ($userLink && $userLink->trashed()) {
                $userLink->restore();
                $userLink->update([
                    'type' => $data['type'],
                    'is_scan' => $data['is_scan'],
                    'created_at' => now(),
                    'is_on_at' => now(),
                ]);
            } else {
                DB::table('user_links')->insert(
                    [
                        'user_id' => $data['user_id'],
                        'link_id' => $link->id,
                        'is_scan' => $data['is_scan'],
                        'title' => $data['title'],
                        'type' => $data['type'],
                        'note' => $link->note,
                        'is_on_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

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
        try {
            $data = $request->validate([
                'id' => 'required|integer',
                'title' => 'nullable|string',
                'content' => 'nullable|string',
                'comment' => 'nullable|string',
                'diff_comment' => 'nullable|string',
                'data' => 'nullable|string',
                'diff_data' => 'nullable|string',
                'reaction' => 'nullable|string',
                'diff_reaction' => 'nullable|string',
                'is_scan' => 'nullable|in:0,1',
                'note' => 'nullable|string',
                'link_or_post_id' => 'required|string',
                'user_id' => 'required|string',
            ]);
            unset($data['id']);
            DB::beginTransaction();
            $link = Link::firstWhere('id', $request->input('id'));
            if ($link) {
                $link->update($data);
                UserLink::where('user_id', $data['user_id'])
                    ->where('link_id', $link->id)
                    ->update([
                        'title' => $link->title,
                        'note' => $link->note,
                    ]);
            }
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
        }
        DB::commit();
        Toastr::success(__('message.success.update'), __('title.toastr.success'));

        return redirect()->back();
    }

    public function index(Request $request)
    {
        return view('admin.linkfollow.list', [
            'title' => 'Danh sách link theo dõi',
            'users' => User::with(['userLinks'])->where('role', GlobalConstant::ROLE_USER)->get()
        ]);
    }

    public function show($id, Request $request)
    {
        return view('admin.linkfollow.edit', [
            'title' => 'Chi tiết link theo dõi',
            'link' => Link::firstWhere('id', $id),
            'userLink' => UserLink::where('link_id', $id)
                ->where('user_id', $request->user_id)
                ->first(),
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
