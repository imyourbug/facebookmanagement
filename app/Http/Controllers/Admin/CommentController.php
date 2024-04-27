<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Link;
use App\Models\LinkComment;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;


class CommentController extends Controller
{
    public function getAll(Request $request)
    {
        $user_id = $request->user_id;
        $comment_id = $request->comment_id;
        $to = $request->to;
        $from = $request->from;

        return response()->json([
            'status' => 0,
            'comments' => LinkComment::with(['comment', 'link.userLinks.user'])
                ->when($user_id, function ($q) use ($user_id) {
                    return $q->whereHas('link.userLinks', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                })
                ->when($to, function ($q) use ($to) {
                    return $q->whereHas('comment', function ($q) use ($to) {
                        $q->where('created_at', '<=', $to);
                    });
                })
                ->when($from, function ($q) use ($from) {
                    return $q->whereHas('comment', function ($q) use ($from) {
                        $q->where(
                            'created_at',
                            '>=',
                            $from
                        );
                    });
                })
                ->when($comment_id, function ($q) use ($comment_id) {
                    return $q->where('comment_id', $comment_id);
                })
                ->get()
        ]);
    }

    public function create()
    {
        return view('admin.comment.add', [
            'title' => 'Thêm bình luận'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'comments' => 'nullable|array',
                'comments.*.link_or_post_id' => 'required|string',
                'comments.*.title' => 'nullable|string',
                'comments.*.uid' => 'nullable|string',
                'comments.*.phone' => 'nullable|string',
                'comments.*.content' => 'nullable|string',
                'comments.*.name_facebook' => 'nullable|string',
                'comments.*.note' => 'nullable|string',
                'comments.*.comment_id' => 'nullable|string',
            ]);
            DB::beginTransaction();
            foreach ($data['comments'] as $key => $data) {
                $link = Link::firstWhere('link_or_post_id', $data['link_or_post_id']);
                if (!$link) {
                    throw new Exception('link_or_post_id không tồn tại');
                }
                $comment = Comment::firstWhere('comment_id', $data['comment_id']);
                if ($comment) {
                    throw new Exception('comment_id đã tồn tại');
                }
                unset($data['link_or_post_id']);
                $comment = Comment::create($data);
                LinkComment::create([
                    'link_id' => $link->id,
                    'comment_id' => $comment->id,
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => 0,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
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
            'name_facebook' => 'nullable|string',
            'note' => 'nullable|string',
            'comment_id' => 'nullable|string',
            'link_or_post_id' => 'required|string'
        ]);
        unset($data['id']);
        $update = Comment::where('id', $request->input('id'))->update($data);

        if ($update) {
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.update'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $from = $request->from ?? '';
            $to = $request->to ?? '';

            $comments = LinkComment::with(['link.userLinks.user', 'comment'])
                ->orderByDesc('id')
                ->when($from, function ($q) use ($from) {
                    return $q->where('created_at', '>=', $from);
                })
                ->when($to, function ($q) use ($to) {
                    return $q->where('created_at', '<=', $to);
                })
                ->get();

            return response()->json([
                'status' => 0,
                'comments' => $comments
            ]);
        }

        return view('admin.comment.list', [
            'title' => 'Danh sách bình luận',
        ]);
    }

    public function show($id)
    {
        return view('admin.comment.edit', [
            'title' => 'Chi tiết bình luận',
            'comment' => Comment::firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        try {
            $link = Comment::firstWhere('id', $id);
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
}
