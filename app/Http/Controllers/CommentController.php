<?php

namespace App\Http\Controllers;

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
        $content = $request->content;
        $user = $request->user;
        $uid = $request->uid;
        $note = $request->note;
        $phone = $request->phone;

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
                // note
                ->when($note, function ($q) use ($note) {
                    return $q->whereHas('comment', function ($q) use ($note) {
                        $q->where('note', 'like', "%$note%");
                    });
                })
                // content
                ->when($content, function ($q) use ($content) {
                    return $q->whereHas('comment', function ($q) use ($content) {
                        $q->where('content', 'like', "%$content%");
                    });
                })
                // phone
                ->when($phone, function ($q) use ($phone) {
                    return $q->whereHas('comment', function ($q) use ($phone) {
                        $q->where('phone', 'like', "%$phone%");
                    });
                })
                // uid
                ->when($uid, function ($q) use ($uid) {
                    return $q->whereHas('comment', function ($q) use ($uid) {
                        $q->where('uid', 'like', "%$uid%");
                    });
                })
                // user
                ->when($user, function ($q) use ($user) {
                    return $q->whereHas('link.userLinks.user', function ($q) use ($user) {
                        $q->where('name', 'like', "%$user%")
                            ->orWhere('email', 'like', "%$user%");
                    });
                })
                // order
                ->whereHas('comment', function ($q) {
                    $q->orderByDesc('created_at');
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
                'comments.*.note' => 'nullable|string',
                'comments.*.name_facebook' => 'nullable|string',
                'comments.*.comment_id' => 'nullable|string',
                'comments.*.created_at' => 'nullable|string',
            ]);
            DB::beginTransaction();
            $count = 0;
            foreach ($data['comments'] as $key => $value) {
                $link = Link::firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    // throw new Exception('Không tồn tại link_or_post_id');
                    continue;
                }
                $comment = Comment::firstWhere('comment_id', $value['comment_id']);
                if ($comment) {
                    continue;
                }
                unset($value['link_or_post_id']);
                $comment = Comment::create($value);
                LinkComment::create([
                    'link_id' => $link->id,
                    'comment_id' => $comment->id,
                ]);
                $count++;
            }
            DB::commit();
            $all = count($data['comments']);

            return response()->json([
                'status' => 0,
                'rate' => "$count/$all"
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateById(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'title' => 'nullable|string',
            'content' => 'nullable|string',
            'comment' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'note' => 'nullable|string',
            'name_facebook' => 'nullable|string',
            'comment_id' => 'nullable|string',
            'link_or_post_id' => 'nullable|string'
        ]);
        unset($data['id']);
        Comment::where('id', $request->input('id'))->update($data);

        return response()->json([
            'status' => 0,
        ]);
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
            'name_facebook' => 'nullable|string',
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

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();
            Comment::whereIn('id', $request->ids)->delete();

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
}
