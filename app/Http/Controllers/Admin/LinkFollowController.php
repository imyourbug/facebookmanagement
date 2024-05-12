<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\UserLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;

class LinkFollowController extends Controller
{
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
