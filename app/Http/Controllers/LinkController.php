<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkHistory;
use App\Models\User;
use App\Models\UserLink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;

class LinkController extends Controller
{

    public function getAll(Request $request)
    {
        $comment_from = $request->comment_from;
        $comment_to = $request->comment_to;
        $delay_from = $request->delay_from;
        $delay_to = $request->delay_to;
        $data_from = $request->data_from;
        $data_to = $request->data_to;
        $reaction_from = $request->reaction_from;
        $reaction_to = $request->reaction_to;
        $time_from = $request->time_from;
        $time_to = $request->time_to;
        $last_data_from = $request->last_data_from;
        $last_data_to = $request->last_data_to;
        $from = $request->from;
        $to = $request->to;
        $user_id = $request->user_id;
        $user = $request->user;
        $note = $request->note;
        $link_id = $request->link_id;
        $is_scan = $request->is_scan;
        $type = (string)$request->type;
        $link_or_post_id = $request->link_or_post_id;
        $title = $request->title;
        $content = $request->content;
        $status = $request->status;

        $query = '(HOUR(CURRENT_TIMESTAMP()) * 60 + MINUTE(CURRENT_TIMESTAMP()) - HOUR(updated_at) * 60 - MINUTE(updated_at))/60 + DATEDIFF(CURRENT_TIMESTAMP(), updated_at) * 24';
        $queryLastData = '(HOUR(CURRENT_TIMESTAMP()) * 60 + MINUTE(CURRENT_TIMESTAMP()) - HOUR(created_at) * 60 - MINUTE(created_at))/60 + DATEDIFF(CURRENT_TIMESTAMP(), created_at) * 24';

        // DB::enableQueryLog();

        $links = Link::with(['commentLinks.comment', 'userLinks.user'])
            // title
            ->when($title, function ($q) use ($title) {
                return $q->where('title', 'like', "%$title%");
            })
            // link_or_post_id
            ->when($link_or_post_id, function ($q) use ($link_or_post_id) {
                return $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
            })
            // content
            ->when($content, function ($q) use ($content) {
                return $q->where('content', 'like', "%$content%");
            })
            ->when($user_id, function ($q) use ($user_id) {
                return $q->whereHas('userLinks', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                });
            })
            ->when($link_id, function ($q) use ($link_id) {
                return $q->where('id', $link_id);
            })
            // delay
            ->when(strlen($delay_from), function ($q) use ($delay_from, $delay_to) {
                return $q->when(strlen($delay_to), function ($q) use ($delay_from, $delay_to) {
                    return $q->whereRaw('delay >= ?', $delay_from)
                        ->whereRaw('delay <= ?', $delay_to);
                }, function ($q) use ($delay_from) {
                    return $q->whereRaw('delay >= ?', $delay_from);
                });
            }, function ($q) use ($delay_to) {
                return $q->when(strlen($delay_to), function ($q) use ($delay_to) {
                    return $q->whereRaw('delay <= ?', $delay_to);
                });
            })
            // data
            ->when(strlen($data_from), function ($q) use ($data_from, $data_to) {
                return $q->when(strlen($data_to), function ($q) use ($data_from, $data_to) {
                    return $q->whereRaw('diff_data >= ?', $data_from)
                        ->whereRaw('diff_data <= ?', $data_to);
                }, function ($q) use ($data_from) {
                    return $q->whereRaw('diff_data >= ?', $data_from);
                });
            }, function ($q) use ($data_to) {
                return $q->when(strlen($data_to), function ($q) use ($data_to) {
                    return $q->whereRaw('diff_data <= ?', $data_to);
                });
            })
            // reaction
            ->when(strlen($reaction_from), function ($q) use ($reaction_from, $reaction_to) {
                return $q->when(strlen($reaction_to), function ($q) use ($reaction_from, $reaction_to) {
                    return $q->whereRaw('diff_reaction >= ?', $reaction_from)
                        ->whereRaw('diff_reaction <= ?', $reaction_to);
                }, function ($q) use ($reaction_from) {
                    return $q->whereRaw('diff_reaction >= ?', $reaction_from);
                });
            }, function ($q) use ($reaction_to) {
                return $q->when(strlen($reaction_to), function ($q) use ($reaction_to) {
                    return $q->whereRaw('diff_reaction <= ?', $reaction_to);
                });
            })
            // comment
            ->when(strlen($comment_from), function ($q) use ($comment_from, $comment_to) {
                return $q->when(strlen($comment_to), function ($q) use ($comment_from, $comment_to) {
                    return $q->whereRaw('diff_comment >= ?', $comment_from)
                        ->whereRaw('diff_comment <= ?', $comment_to);
                }, function ($q) use ($comment_from) {
                    return $q->whereRaw('diff_comment >= ?', $comment_from);
                });
            }, function ($q) use ($comment_to) {
                return $q->when(strlen($comment_to), function ($q) use ($comment_to) {
                    return $q->whereRaw('diff_comment <= ?', $comment_to);
                });
            })
            // last data
            ->when(strlen($last_data_from), function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                return $q->when(strlen($last_data_to), function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                    return $q->whereHas('commentLinks', function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                        $q->whereRaw("$queryLastData >= ?", $last_data_from)
                            ->whereRaw("$queryLastData <= ?", $last_data_to);
                    });
                }, function ($q) use ($last_data_from, $queryLastData) {
                    return $q->whereHas('commentLinks', function ($q) use ($last_data_from, $queryLastData) {
                        $q->whereRaw("$queryLastData >= ?", $last_data_from);
                    });
                });
            }, function ($q) use ($last_data_to, $queryLastData) {
                return $q->when(strlen($last_data_to), function ($q) use ($last_data_to, $queryLastData) {
                    return $q->whereHas('commentLinks', function ($q) use ($last_data_to, $queryLastData) {
                        $q->whereRaw("$queryLastData <= ?", $last_data_to);
                    });
                });
            })
            // data update count
            ->when(strlen($time_from), function ($q) use ($time_from, $time_to, $query) {
                return $q->when(strlen($time_to), function ($q) use ($time_from, $time_to, $query) {
                    return $q->whereRaw("$query >= ?", $time_from)
                        ->whereRaw("$query <= ?", $time_to);
                }, function ($q) use ($time_from, $query) {
                    return $q->whereRaw("$query >= ?", $time_from);
                });
            }, function ($q) use ($time_to, $query) {
                return $q->when(strlen($time_to), function ($q) use ($time_to, $query) {
                    return $q->whereRaw("$query <= ?", $time_to);
                });
            })
            // date
            ->when($from, function ($q) use ($from, $to) {
                return $q->when($to, function ($q) use ($from, $to) {
                    return $q->whereRaw('created_at >= ?', $from)
                        ->whereRaw('created_at <= ?', $to . ' 23:59:59');
                }, function ($q) use ($from) {
                    return $q->whereRaw('created_at >= ?', $from);
                });
            }, function ($q) use ($to) {
                return $q->when($to, function ($q) use ($to) {
                    return $q->whereRaw('created_at <= ?', $to . ' 23:59:59');
                });
            })
            // is_scan
            ->when(is_numeric($is_scan) || is_array($is_scan), function ($q) use ($is_scan) {
                switch (true) {
                    case is_array($is_scan):
                        return $q->whereHas('userLinks', function ($q) use ($is_scan) {
                            $q->whereIn('is_scan', $is_scan);
                        });
                        break;
                    default:
                        return $q->whereHas('userLinks', function ($q) use ($is_scan) {
                            $q->where('is_scan', $is_scan);
                        });
                        break;
                }
            })
            // user
            ->when($user, function ($q) use ($user) {
                return $q->whereHas('userLinks.user', function ($q) use ($user) {
                    $q->where('name', 'like', "%$user%")
                        ->orWhere('email', 'like', "%$user%");
                });
            })
            // note
            ->when($note, function ($q) use ($note) {
                return $q->where('note', 'like', "%$note%");
            })
            // type
            ->when(in_array($type, GlobalConstant::LINK_STATUS), function ($q) use ($type) {
                return $q->where('type', $type);
            })
            // status
            ->when(strlen($status), function ($q) use ($status) {
                return $q->where('status', $status);
            })
            // default just get all link has at least an userLink record with is_scan = ON
            ->whereHas('userLinks', function ($q) {
                $q->where('is_scan', GlobalConstant::IS_ON);
            })
            //
            ->orderByDesc('created_at')
            ->get()?->toArray() ?? [];

        // dd(DB::getRawQueryLog());

        $links = array_map(function ($value) {
            return [
                ...$value,
                'accounts' => UserLink::with(['link', 'user'])
                    ->whereHas('link', function ($q) use ($value) {
                        $q->where('link_or_post_id', $value['link_or_post_id']);
                    })->get()
            ];
        }, $links);

        return response()->json([
            'status' => 0,
            'links' => $links,
            'user' => User::firstWhere('id', $user_id),
        ]);
    }

    public function getByType(Request $request)
    {
        return response()->json([
            'status' => 0,
            'links' => Link::where('type', $request->type)->get()
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'links' => 'nullable|array',
            'links.*.title' => 'nullable|string',
            'links.*.time' => 'nullable|string',
            'links.*.content' => 'nullable|string',
            'links.*.comment' => 'nullable|string',
            'links.*.diff_comment' => 'nullable|string',
            'links.*.data' => 'nullable|string',
            'links.*.diff_data' => 'nullable|string',
            'links.*.reaction' => 'nullable|string',
            'links.*.diff_reaction' => 'nullable|string',
            'links.*.is_scan' => 'nullable|in:0,1,2',
            'links.*.note' => 'nullable|string',
            'links.*.link_or_post_id' => 'required|string',
            'links.*.type' => 'required|in:0,1,2',
        ]);

        $data = array_map(function ($item) {
            return [
                ...$item,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $data['links']);
        Link::insert($data['links']);

        return response()->json([
            'status' => 0,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'required|array',
                'links.*.title' => 'nullable|string',
                'links.*.time' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.comment' => 'nullable|string',
                'links.*.diff_comment' => 'nullable|string',
                'links.*.data' => 'nullable|string',
                'links.*.diff_data' => 'nullable|string',
                'links.*.reaction' => 'nullable|string',
                'links.*.diff_reaction' => 'nullable|string',
                'links.*.is_scan' => 'nullable|in:0,1,2',
                'links.*.note' => 'nullable|string',
                'links.*.link_or_post_id' => 'required|string',
                'links.*.parent_link_or_post_id' => 'nullable|string',
                'links.*.end_cursor' => 'nullable|string',
                'links.*.type' => 'required|in:0,1,2',
            ]);

            $data = array_map(function ($item) {
                return [
                    ...$item,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $data['links']);
            Link::insert($data);

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

    /*
        Just for external calling
    **/
    public function updateLinkByLinkOrPostId(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'required|array',
                'links.*.link_or_post_id' => 'required|string',
                'links.*.parent_link_or_post_id' => 'nullable|string',
                'links.*.title' => 'nullable|string',
                'links.*.time' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.comment' => 'nullable|string',
                // 'links.*.diff_comment' => 'nullable|string',
                'links.*.data' => 'nullable|string',
                // 'links.*.diff_data' => 'nullable|string',
                'links.*.reaction' => 'nullable|string',
                // 'links.*.diff_reaction' => 'nullable|string',
                'links.*.is_scan' => 'nullable|in:0,1,2',
                'links.*.status' => 'nullable|in:0,1',
                'links.*.note' => 'nullable|string',
                'links.*.end_cursor' => 'nullable|string',
                'links.*.type' => 'nullable|in:0,1,2',
            ]);

            DB::beginTransaction();

            foreach ($data['links'] as $key => &$value) {
                $link = Link::firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    throw new Exception('link_or_post_id không tồn tại');
                }
                // get and set diff
                if (isset($value['comment']) && strlen($value['comment'])) {
                    $lastHistory = LinkHistory::where('link_id', $link->id)
                        ->where('type', GlobalConstant::TYPE_COMMENT)
                        ->orderByDesc('id')
                        ->first();
                    $value['diff_comment'] = $lastHistory?->comment ? ((int)$value['comment'] - (int)$lastHistory->comment) : (int)$value['comment'];
                    LinkHistory::create([
                        'comment' => $value['comment'],
                        'diff_comment' => $value['diff_comment'],
                        'link_id' => $link->id,
                        'type' => GlobalConstant::TYPE_COMMENT
                    ]);
                }
                if (isset($value['data']) && strlen($value['data'])) {
                    $lastHistory = LinkHistory::where('link_id', $link->id)
                        ->where('type', GlobalConstant::TYPE_DATA)
                        ->orderByDesc('id')
                        ->first();
                    $value['diff_data'] = $lastHistory?->data ? ((int)$value['data'] - (int)$lastHistory->data) : (int)$value['data'];
                    LinkHistory::create([
                        'data' => $value['data'],
                        'diff_data' => $value['diff_data'],
                        'link_id' => $link->id,
                        'type' => GlobalConstant::TYPE_DATA
                    ]);
                }
                if (isset($value['reaction']) && strlen($value['reaction'])) {
                    $lastHistory = LinkHistory::where('link_id', $link->id)
                        ->where('type', GlobalConstant::TYPE_REACTION)
                        ->orderByDesc('id')
                        ->first();
                    $value['diff_reaction'] = $lastHistory?->reaction ? ((int)$value['reaction'] - (int)$lastHistory->reaction) : (int)$value['reaction'];
                    LinkHistory::create([
                        'reaction' => $value['reaction'],
                        'diff_reaction' => $value['diff_reaction'],
                        'link_id' => $link->id,
                        'type' => GlobalConstant::TYPE_REACTION
                    ]);
                }
                //
                unset($value['link_or_post_id']);
                $link->update($value);
                $value['link_id'] = $link->id;
                $value['created_at'] = now();
                $value['updated_at'] = now();
                //
                $is_scan = $value['is_scan'] ?? '';
                if (strlen($is_scan)) {
                    UserLink::where('link_id', $link->id)
                        ->update([
                            'is_scan' => $is_scan,
                            'created_at' => now(),
                        ]);
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 0,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer',
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
                'status' => 'nullable|in:0,1',
                'note' => 'nullable|string',
                'end_cursor' => 'nullable|string',
                'link_or_post_id' => 'nullable|string',
                'parent_link_or_post_id' => 'nullable|string',
                'type' => 'nullable|in:0,1,2',
                'user_id' => 'nullable|integer',
            ]);

            unset($data['id']);
            $link = Link::with(['userLinks.user'])->firstWhere('id', $request->input('id'));
            $link->update($data);
            // $link->userLinks()->update([
            //     'is_scan' => $data['is_scan'],
            //     'title' => $data['title'],
            //     'type' => $data['type'],
            //     'note' => $data['note'],
            // ]);
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

    /*
        Using for update link by list ids in admin/linkrunning
    **/
    public function updateLinkByListLinkId(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
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
            'status' => 'nullable|in:0,1',
            'note' => 'nullable|string',
            'delay' => 'nullable|string',
            'end_cursor' => 'nullable|string',
            'parent_link_or_post_id' => 'nullable|string',
            'link_or_post_id' => 'nullable|string',
            'type' => 'nullable|in:0,1,2',
            'user_id' => 'nullable|integer',
        ]);

        $links = Link::whereIn('id', $data['ids']);

        if ($links->get()->count() === 0) {
            throw new Exception('Link không tồn tại');
        }
        //

        unset($data['ids'], $data['user_id']);
        $links->update($data);

        return response()->json([
            'status' => 0,
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

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();
            Link::whereIn('id', $request->ids)->delete();

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

    public function deleteAllUserLink(Request $request)
    {
        try {
            DB::beginTransaction();
            UserLink::whereIn('id', $request->ids)->delete();

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

    public function index()
    {
        return response()->json([
            'status' => 0,
            'links' => Link::all()
        ]);
    }
}
