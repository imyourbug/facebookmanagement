<?php

namespace App\Http\Controllers\Users;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\UserLink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;

class LinkFollowController extends Controller
{
    public function create()
    {
        return view('user.linkfollow.add', [
            'title' => 'Thêm link theo dõi'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'nullable|string',
                'content' => 'nullable|string',
                'comment' => 'nullable|string',
                'data' => 'nullable|numeric',
                'emotion' => 'nullable|numeric',
                'note' => 'nullable|string',
                'link_or_post_id' => 'required|string'
            ]);
            $userLinks = UserLink::with(['link', 'user'])
            ->where('user_id', Auth::id())
                ->whereHas('link', function ($q) use ($data) {
                    $q->where('link_or_post_id', $data['link_or_post_id']);
                })
                ->get();

            if ($userLinks->count()) {
                throw new Exception('Đã tồn tại link hoặc post ID');
            }
            $data['type'] = GlobalConstant::TYPE_FOLLOW;

            DB::beginTransaction();
            $link = Link::create($data);
            UserLink::create([
                'user_id' => Auth::id(),
                'link_id' => $link->id
            ]);
            Toastr::success('Tạo link theo dõi thành công', __('title.toastr.success'));
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
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

        return view('user.linkfollow.list', [
            'title' => 'Danh sách link theo dõi',
        ]);
    }

    public function show($id)
    {
        return view('user.linkfollow.edit', [
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
