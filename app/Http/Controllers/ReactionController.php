<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkReaction;
use App\Models\Reaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use Toastr;

class ReactionController extends Controller
{
    public function getAll(Request $request)
    {
        $user_id = $request->user_id;
        $reaction_id = $request->reaction_id;
        $to = $request->to;
        $from = $request->from;
        $reaction = $request->reaction;
        $user = $request->user;
        $uid = $request->uid;
        $note = $request->note;
        $phone = $request->phone;
        $link_or_post_id = $request->link_or_post_id;
        $title = $request->title;
        $name_facebook = $request->name_facebook;
        $today = $request->today;
        $limit = $request->limit;
        $ids = $request->ids ? explode(',', $request->ids) : [];

        $reactions = LinkReaction::with(['reaction.getUid', 'link.userLinks.user'])
            ->when($user_id, function ($q) use ($user_id) {
                return $q->whereHas('link.userLinks', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                });
            })
            ->when($to, function ($q) use ($to) {
                return $q->whereHas('reaction', function ($q) use ($to) {
                    $q->where('created_at', '<=', $to);
                });
            })
            ->when($from, function ($q) use ($from) {
                return $q->whereHas('reaction', function ($q) use ($from) {
                    $q->where(
                        'created_at',
                        '>=',
                        $from
                    );
                });
            })
            ->when($reaction_id, function ($q) use ($reaction_id) {
                return $q->where('reaction_id', $reaction_id);
            })
            // user
            ->when($user, function ($q) use ($user) {
                return $q->whereHas('link.userLinks.user', function ($q) use ($user) {
                    $q->where('name', 'like', "%$user%")
                        ->orWhere('email', 'like', "%$user%");
                });
            })
            // today
            ->when($today, function ($q) use ($today) {
                return $q->whereHas('reaction', function ($q) use ($today) {
                    $q->where('created_at', 'like', "%$today%");
                });
            })
            // title
            ->when($title, function ($q) use ($title) {
                return $q->whereHas('reaction', function ($q) use ($title) {
                    $q->where('title', 'like', "%$title%");
                });
            })
            // link_or_post_id
            ->when($link_or_post_id, function ($q) use ($link_or_post_id) {
                return $q->whereHas('link', function ($q) use ($link_or_post_id) {
                    $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
                });
            })
            // name_facebook
            ->when($name_facebook, function ($q) use ($name_facebook) {
                return $q->whereHas('reaction', function ($q) use ($name_facebook) {
                    $q->where('name_facebook', 'like', "%$name_facebook%");
                });
            })
            // note
            ->when($note, function ($q) use ($note) {
                return $q->whereHas('reaction', function ($q) use ($note) {
                    $q->where('note', 'like', "%$note%");
                });
            })
            // reaction
            ->when($reaction, function ($q) use ($reaction) {
                return $q->whereHas('reaction', function ($q) use ($reaction) {
                    $q->where('reaction', 'like', "%$reaction%");
                });
            })
            // phone
            ->when($phone, function ($q) use ($phone) {
                return $q->whereHas('reaction.getUid', function ($q) use ($phone) {
                    $q->where('phone', 'like', "%$phone%");
                });
            })
            // uid
            ->when($uid, function ($q) use ($uid) {
                return $q->whereHas('reaction', function ($q) use ($uid) {
                    $q->where('uid', 'like', "%$uid%");
                });
            })
            // ids
            ->when(count($ids), function ($q) use ($ids) {
                $q->whereIn('id', $ids);
            })
            // order
            ->orderByDesc('created_at');

        // limit
        if ($limit) {
            $reactions = $reactions->limit($limit);
        }

        return response()->json([
            'status' => 0,
            'reactions' => $reactions->get()
        ]);
    }

    public function create()
    {
        return view('admin.reaction.add', [
            'title' => 'Thêm cảm xúc'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'reactions' => 'nullable|array',
                'reactions.*.link_or_post_id' => 'required|string',
                'reactions.*.title' => 'nullable|string',
                'reactions.*.uid' => 'nullable|string',
                'reactions.*.phone' => 'nullable|string',
                'reactions.*.reaction' => 'nullable|string',
                'reactions.*.name_facebook' => 'nullable|string',
                'reactions.*.note' => 'nullable|string',
            ]);
            DB::beginTransaction();
            foreach ($data['reactions'] as $key => $value) {
                $link = Link::firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    throw new Exception('link_or_post_id không tồn tại');
                }
                $count_uid = LinkReaction::with(['reaction'])
                    ->where('link_id', $link->id)
                    ->when(strlen($value['uid'] ?? ''), function ($q) use ($value) {
                        return $q->whereHas('reaction', function ($q) use ($value) {
                            $q->where('uid', $value['uid']);
                        });
                    })
                    ->get()
                    ->count();
                if ($count_uid) {
                    throw new Exception('Trùng uid');
                }

                unset($value['link_or_post_id']);
                $reaction = Reaction::create($value);
                LinkReaction::create([
                    'link_id' => $link->id,
                    'reaction_id' => $reaction->id,
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
            'reaction' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'note' => 'nullable|string',
            'name_facebook' => 'nullable|string',
            'link_or_post_id' => 'required|string'
        ]);
        unset($data['id']);
        $update = Reaction::where('id', $request->input('id'))->update($data);

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

            $reactions = LinkReaction::with(['link.userLinks.user', 'reaction'])
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
                'reactions' => $reactions
            ]);
        }

        return view('admin.reaction.list', [
            'title' => 'Danh sách cảm xúc',
        ]);
    }

    public function show($id)
    {
        return view('admin.reaction.edit', [
            'title' => 'Chi tiết cảm xúc',
            'reaction' => Reaction::firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        $reaction = Reaction::firstWhere('id', $id);
        if (!$reaction) {
            throw new Exception('Cảm xúc không tồn tại');
        }
        $reaction->delete();

        return response()->json([
            'status' => 0,
        ]);
    }

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();
            Reaction::whereIn('id', $request->ids)->delete();

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
