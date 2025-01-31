<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use App\Models\Link;
use App\Models\LinkHistory;
use App\Models\Uid;
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
        $title = $request->title;
        $name_facebook = $request->name_facebook;
        $today = $request->today;
        $limit = $request->limit ?? GlobalConstant::LIMIT_COMMENT;
        $ids = $request->ids ?? [];
        $link_or_post_id = is_numeric($request->link_or_post_id) ? $request->link_or_post_id : $this->getLinkOrPostIdFromUrl($request->link_or_post_id ?? '');

        $links = Link::with(['userLinks', 'parentLink'])
            ->when($user_id, function ($q) use ($user_id) {
                return $q->withTrashed()->where('user_id', $user_id);
            })
            ->when($user, function ($q) use ($user) {
                return $q->where('user_id', $user);
            })
            ->get();

        $list_link_of_user = [];
        foreach ($links as $key => $link) {
            $tmp_link_or_post_id = $link?->parentLink ? $link->parentLink->link_or_post_id : $link->link_or_post_id;
            if (!in_array($tmp_link_or_post_id, $list_link_of_user)) {
                $list_link_of_user[] = $tmp_link_or_post_id;
            }
        }

        DB::enableQueryLog();
        $comments = Comment::with([
            'getUid',
            'link.user',
            'link.userLinks.user',
            'link.userLinks.user',
            'link.childLinks.user',
            'link.parentLink.user',
            'link.childLinks.userLinks.user',
            'link.parentLink.userLinks.user',
            'link.parentLink.childLinks.user'
        ])
            // default
            // ->whereHas('link', function ($q) use ($list_link_of_user) {
            //     $q->whereIn('link_or_post_id', $list_link_of_user);
            // })
            // to
            ->when($to, function ($q) use ($to) {
                return $q->where('created_at', '<=', $to . ' 23:59:59');
            })
            // from
            ->when($from, function ($q) use ($from) {
                return $q->where(
                    'created_at',
                    '>=',
                    $from
                );
            })
            // comment_id
            ->when($comment_id, function ($q) use ($comment_id) {
                return $q->where('comment_id', $comment_id);
            })
            // today
            ->when(strlen($today), function ($q) use ($today) {
                return $q->where('created_at', 'like', "%$today%");
            })
            // title
            ->when(strlen($title), function ($q) use ($title) {
                return $q->where('title', 'like', "%$title%");
            })
            // link_or_post_id
            // ->when(strlen($link_or_post_id), function ($q) use ($link_or_post_id) {
            //     return $q->whereHas('link', function ($q) use ($link_or_post_id) {
            //         $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
            //     });
            // })
            // name_facebook
            ->when(strlen($name_facebook), function ($q) use ($name_facebook) {
                return $q->where('name_facebook', 'like', "%$name_facebook%");
            })
            // note
            ->when(strlen($note), function ($q) use ($note) {
                return $q->where('note', 'like', "%$note%");
            })
            // content
            ->when(strlen($content), function ($q) use ($content) {
                return $q->where('content', 'like', "%$content%");
            })
            // phone
            ->when(strlen($phone), function ($q) use ($phone) {
                return $q->whereHas('getUid', function ($q) use ($phone) {
                    $q->where('phone', 'like', "%$phone%");
                });
                // return $q->where('phone', 'like', "%$phone%");
            })
            // uid
            ->when(strlen($uid), function ($q) use ($uid) {
                return $q->where('uid', 'like', "%$uid%");
            })
            // ids
            ->when(count($ids), function ($q) use ($ids) {
                $q->whereIn('id', $ids);
            })
            // order
            ->orderByDesc('created_at');

        // limit
        if ($limit) {
            $comments = $comments->limit($limit);
        }

        $comments = $comments->get()?->toArray() ?? [];;
        // dd(DB::getRawQueryLog());

        $result_comments = [];
        foreach ($comments as $value) {
            $link = $value['link'];
            if (strlen($value['link']['parent_link_or_post_id'] ?? '')) {
                $link = $value['link']['parent_link'];
            }
            $account = [];
            if (!empty($link['user']['name'])) {
                $account[] = $link['user']['name'];
            }
            // foreach ($link['user_links'] as $is_on_user_link) {
            //     $account[$is_on_user_link['id']] = $is_on_user_link;
            // }
            foreach ($link['child_links'] ?? [] as $childLink) {
                if (!empty($childLink['user']['name']) && !in_array($childLink['user']['name'], $account)) {
                    $account[] = $childLink['user']['name'];
                }
            }
            $result_comments[] = [
                ...$value,
                'accounts' => collect($account)->values()
            ];
        }
        // dd($result_comments);

        return response()->json([
            'status' => 0,
            'comments' => $result_comments
        ]);
    }

    public function getAllCommentNew(Request $request)
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
        $title = $request->title;
        $name_facebook = $request->name_facebook;
        $today = $request->today;
        $limit = $request->limit ?? GlobalConstant::LIMIT_COMMENT;
        $ids = $request->ids ?? [];
        //$link_or_post_id = is_numeric($request->link_or_post_id) ? $request->link_or_post_id : $this->getLinkOrPostIdFromUrl($request->link_or_post_id ?? '');

        try{
            $links = Link::get()->toArray();
            $users = User::get()->toArray();
    
            $userMap = [];
            foreach ($users as $u) {
                $userMap[$u['id']] = $u['name'];
            }
    
            $linkMap = [];
            foreach ($links as $link) {
                $linkMap[$link['parent_link_or_post_id']]['titles'][] = $link['title'];
                $linkMap[$link['parent_link_or_post_id']]['users'][] = $userMap[$link['user_id']];
            }
    
            // Combine titles and users into a single string
            foreach ($linkMap as $id => $data) {
                $linkMap[$id]['titles'] = implode('|', $data['titles']);
                $linkMap[$id]['users'] = implode('|', $data['users']);
            }
    
            DB::enableQueryLog();
            $comments = Comment::when(strlen($today), function ($q) use ($today) {
                return $q->where('created_at', 'like', "%$today%");
            })->orderByDesc('created_at');
    
            // limit
            if ($limit) {
                $comments = $comments->limit($limit);
            }
    
            $comments = $comments->get()?->toArray() ?? [];;
            // dd(DB::getRawQueryLog());
    
            $result = [];
            foreach ($comments as $comment) {
                $parentId = $comment['link_or_post_id'];
                $uid = $comment['uid'];
                $result[] = [
                    'comment_id' => $comment['comment_id'],
                    'title' => $linkMap[$parentId]['titles'] ?? '',
                    'content' => $comment['content'],
                    'accounts' => $linkMap[$parentId]['users'] ?? '',
                    'link_or_post_id' => $parentId,
                    'uid' => $uid,
                    'name_facebook' => $comment['name_facebook'],
                    'created_at' => $comment['created_at'],
                    'id' => $comment['id'],
                    'note' => $comment['note']
                ];
            }
    
            return response()->json([
                'status' => 0,
                'comments' => $result
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'comments' => var_dump($ex)
            ]);
        }
    }

    public function getAllByUser(Request $request)
    {
        $user_id = $request->user_id;
        // $comment_id = $request->comment_id;
        // $to = $request->to;
        // $from = $request->from;
        // $content = $request->content;
        // $user = $request->user;
        // $uid = $request->uid;
        // $note = $request->note;
        // $phone = $request->phone;
        // $title = $request->title;
        // $name_facebook = $request->name_facebook;
        $today = $request->today;
        $limit = $request->limit ?? GlobalConstant::LIMIT_COMMENT;
        // $ids = $request->ids ?? [];
        // $link_or_post_id = is_numeric($request->link_or_post_id) ? $request->link_or_post_id : $this->getLinkOrPostIdFromUrl($request->link_or_post_id ?? '');

        $links = Link::where('user_id', $user_id)->pluck('parent_link_or_post_id')->toArray() ?? [];

        // $list_link_of_user = [];
        // foreach ($links as $key => $link) {
        //     $tmp_link_or_post_id = $link?->parentLink ? $link->parentLink->link_or_post_id : $link->link_or_post_id;
        //     if (!in_array($tmp_link_or_post_id, $list_link_of_user)) {
        //         $list_link_of_user[] = $tmp_link_or_post_id;
        //     }
        // }

        DB::enableQueryLog();
        $comments = Comment::whereIn('link_or_post_id', $links)
            // order
            ->orderByDesc('created_at');

        // limit
        if ($limit) {
            $comments = $comments->limit($limit);
        }

        $comments = $comments->get()?->toArray() ?? [];;
        // dd(DB::getRawQueryLog());

        $result_comments = [];
        // foreach ($comments as $value) {
        //     $link = $value['link'];
        //     if (strlen($value['link']['parent_link_or_post_id'] ?? '')) {
        //         $link = $value['link']['parent_link'];
        //     }
        //     $account = [];
        //     if (!empty($link['user']['name'])) {
        //         $account[] = $link['user']['name'];
        //     }
        //     // foreach ($link['user_links'] as $is_on_user_link) {
        //     //     $account[$is_on_user_link['id']] = $is_on_user_link;
        //     // }
        //     foreach ($link['child_links'] ?? [] as $childLink) {
        //         if (!empty($childLink['user']['name']) && !in_array($childLink['user']['name'], $account)) {
        //             $account[] = $childLink['user']['name'];
        //         }
        //     }
        //     $result_comments[] = [
        //         ...$value,
        //         'accounts' => collect($account)->values()
        //     ];
        // }
        // dd($result_comments);

        return response()->json([
            'status' => 0,
            'comments' => $comments,
            'links' => $links
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
            $unique_link_ids = [];
            $uids = [];
            $error = [
                'comment_id' => [],
                'link_or_post_id' => [],
            ];
            foreach ($data['comments'] as $key => $value) {
                // $link = Link::firstWhere('link_or_post_id', $value['link_or_post_id']);
                // if (!$link) {
                //     if (!in_array($value['link_or_post_id'], $error['link_or_post_id'])) {
                //         $error['link_or_post_id'][] = $value['link_or_post_id'];
                //     }
                //     // throw new Exception('Không tồn tại link_or_post_id');
                //     continue;
                // }
                $comment = Comment::firstWhere('comment_id', $value['comment_id']);
                if ($comment) {
                    if (!in_array($value['comment_id'], $error['comment_id'])) {
                        $error['comment_id'][] = $value['comment_id'];
                    }
                    continue;
                }
                //$unique_link_ids[$link->id] = $link;
                $comment = Comment::create($value);
                //
                // get data phone
                $pattern = '/\d{10,11}/';
                preg_match_all($pattern, $comment->content . ' ' . $comment->phone, $matches);
                $uids[$comment->uid][] = implode(',', $matches[0]);
                $count++;
            }
            if ($count) {
                // insert uids
                foreach ($uids as $key => $value_uid) {
                    $value_uid = array_filter($value_uid);
                    $uid = Uid::firstWhere('uid', $key);
                    if (!$uid) {
                        Uid::create([
                            'uid' => $key,
                            'phone' => implode(',', $value_uid),
                        ]);
                    } else {
                        DB::table('uids')
                            ->where('uid', (string)$key)
                            ->update([
                                'phone' => count($value_uid) ? $uid->phone . ',' . implode(',', $value_uid) : $uid->phone,
                            ]);
                    }
                }
                // update column data of link
                $dataLinks = [];
                foreach ($unique_link_ids as $link) {
                    // $comments = Comment::where('')
                    $count_data = Comment::where('link_or_post_id', $link->link_or_post_id)
                        ->get()
                        ->count();
                    // get history
                    $lastHistory = LinkHistory::with(['link'])
                        ->where('type', GlobalConstant::TYPE_DATA)
                        ->where('link_id', $link->id)
                        ->orderByDesc('id')
                        ->first();
                    //
                    $diff_data = $lastHistory?->data ? $count_data - (int)$lastHistory->data : $count_data;
                    //
                    Link::firstWhere('link_or_post_id', $link->link_or_post_id)
                        ->update([
                            'data' => $count_data,
                            'diff_data' => $diff_data,
                        ]);
                    //
                    $dataLinks[] = [
                        'data' => $count_data,
                        'diff_data' => $diff_data,
                        'link_id' => $link->id,
                        'type' => GlobalConstant::TYPE_DATA,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                LinkHistory::insert($dataLinks);
            }
            //
            DB::commit();
            $all = count($data['comments']);

            return response()->json([
                'status' => 0,
                'rate' => "$count/$all",
                'error' => $error
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
