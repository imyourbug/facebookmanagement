<?php

namespace App\Http\Controllers\Users;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\LinkComment;
use Illuminate\Http\Request;
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
                ->orderByDesc('id')
                ->get()
        ]);
    }

    public function create()
    {
        return view('user.comment.add', [
            'title' => 'Thêm bình luận'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'comments' => 'nullable|array',
                'comments.*.account' => 'nullable|string',
                'comments.*.title' => 'nullable|string',
                'comments.*.uid' => 'nullable|string',
                'comments.*.phone' => 'nullable|string',
                'comments.*.content' => 'nullable|string',
                'comments.*.note' => 'nullable|string',
                'comments.*.comment_id' => 'nullable|string',
            ]);
            $data = array_map(function ($item) {
                return [
                    ...$item,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $data['comments']);
            Comment::insert($data);

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

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'title' => 'required|string',
            'content' => 'nullable|string',
            'comment' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
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

            $comments = Comment::orderByDesc('id')
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

        return view('user.comment.list', [
            'title' => 'Danh sách bình luận',
        ]);
    }

    public function show($id)
    {
        return view('user.comment.edit', [
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
