<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkHistory;
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

        // DB::enableQueryLog();

        $userLinks = UserLink::with(['link', 'user'])
            // title
            ->when($title, function ($q) use ($title) {
                return $q->whereHas('link', function ($q) use ($title) {
                    $q->where('title', 'like', "%$title%");
                });
            })
            // link_or_post_id
            ->when($link_or_post_id, function ($q) use ($link_or_post_id) {
                return $q->whereHas('link', function ($q) use ($link_or_post_id) {
                    $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
                });
            })
            // content
            ->when($content, function ($q) use ($content) {
                return $q->whereHas('link', function ($q) use ($content) {
                    $q->where('content', 'like', "%$content%");
                });
            })
            ->when($user_id, function ($q) use ($user_id) {
                return $q->where('user_id', $user_id);
            })
            ->when($link_id, function ($q) use ($link_id) {
                return $q->where('link_id', $link_id);
            })
            // delay
            ->when(strlen($delay_from), function ($q) use ($delay_from, $delay_to) {
                return $q->when(strlen($delay_to), function ($q) use ($delay_from, $delay_to) {
                    return $q->whereHas('link', function ($q) use ($delay_from, $delay_to) {
                        $q->whereRaw('delay >= ?', $delay_from)
                            ->whereRaw('delay <= ?', $delay_to);
                    });
                }, function ($q) use ($delay_from) {
                    return $q->whereHas('link', function ($q) use ($delay_from) {
                        $q->whereRaw('delay >= ?', $delay_from);
                    });
                });
            }, function ($q) use ($delay_to) {
                return $q->when(strlen($delay_to), function ($q) use ($delay_to) {
                    return $q->whereHas('link', function ($q) use ($delay_to) {
                        $q->whereRaw('delay <= ?', $delay_to);
                    });
                });
            })
            // data
            ->when(strlen($data_from), function ($q) use ($data_from, $data_to) {
                return $q->when(strlen($data_to), function ($q) use ($data_from, $data_to) {
                    return $q->whereHas('link', function ($q) use ($data_from, $data_to) {
                        $q->whereRaw('(data_second - data_first) >= ?', $data_from)
                            ->whereRaw('(data_second - data_first) <= ?', $data_to);
                    });
                }, function ($q) use ($data_from) {
                    return $q->whereHas('link', function ($q) use ($data_from) {
                        $q->whereRaw('(data_second - data_first) >= ?', $data_from);
                    });
                });
            }, function ($q) use ($data_to) {
                return $q->when(strlen($data_to), function ($q) use ($data_to) {
                    return $q->whereHas('link', function ($q) use ($data_to) {
                        $q->whereRaw('(data_second - data_first) <= ?', $data_to);
                    });
                });
            })
            // reaction
            ->when(strlen($reaction_from), function ($q) use ($reaction_from, $reaction_to) {
                return $q->when(strlen($reaction_to), function ($q) use ($reaction_from, $reaction_to) {
                    return $q->whereHas('link', function ($q) use ($reaction_from, $reaction_to) {
                        $q->whereRaw('(emotion_second - emotion_first) >= ?', $reaction_from)
                            ->whereRaw('(emotion_second - emotion_first) <= ?', $reaction_to);
                    });
                }, function ($q) use ($reaction_from) {
                    return $q->whereHas('link', function ($q) use ($reaction_from) {
                        $q->whereRaw('(emotion_second - emotion_first) >= ?', $reaction_from);
                    });
                });
            }, function ($q) use ($reaction_to) {
                return $q->when(strlen($reaction_to), function ($q) use ($reaction_to) {
                    return $q->whereHas('link', function ($q) use ($reaction_to) {
                        $q->whereRaw('(emotion_second - emotion_first) <= ?', $reaction_to);
                    });
                });
            })
            // comment
            ->when(strlen($comment_from), function ($q) use ($comment_from, $comment_to) {
                return $q->when(strlen($comment_to), function ($q) use ($comment_from, $comment_to) {
                    return $q->whereHas('link', function ($q) use ($comment_from, $comment_to) {
                        $q->whereRaw('(comment_second - comment_first) >= ?', $comment_from)
                            ->whereRaw('(comment_second - comment_first) <= ?', $comment_to);
                    });
                }, function ($q) use ($comment_from) {
                    return $q->whereHas('link', function ($q) use ($comment_from) {
                        $q->whereRaw('(comment_second - comment_first) >= ?', $comment_from);
                    });
                });
            }, function ($q) use ($comment_to) {
                return $q->when(strlen($comment_to), function ($q) use ($comment_to) {
                    return $q->whereHas('link', function ($q) use ($comment_to) {
                        $q->whereRaw('(comment_second - comment_first) <= ?', $comment_to);
                    });
                });
            })
            // time
            ->when(strlen($time_from), function ($q) use ($time_from, $time_to, $query) {
                return $q->when(strlen($time_to), function ($q) use ($time_from, $time_to, $query) {
                    return $q->whereHas('link', function ($q) use ($time_from, $time_to, $query) {
                        $q->whereRaw("$query >= ?", $time_from)
                            ->whereRaw("$query <= ?", $time_to);
                    });
                }, function ($q) use ($time_from, $query) {
                    return $q->whereHas('link', function ($q) use ($time_from, $query) {
                        $q->whereRaw("$query >= ?", $time_from);
                    });
                });
            }, function ($q) use ($time_to, $query) {
                return $q->when(strlen($time_to), function ($q) use ($time_to, $query) {
                    return $q->whereHas('link', function ($q) use ($time_to, $query) {
                        $q->whereRaw("$query <= ?", $time_to);
                    });
                });
            })
            // date
            ->when($from, function ($q) use ($from, $to) {
                return $q->when($to, function ($q) use ($from, $to) {
                    return $q->whereHas('link', function ($q) use ($from, $to) {
                        $q->whereRaw('created_at >= ?', $from)
                            ->whereRaw('created_at <= ?', $to . ' 23:59:59');
                    });
                }, function ($q) use ($from) {
                    return $q->whereHas('link', function ($q) use ($from) {
                        $q->whereRaw('created_at >= ?', $from);
                    });
                });
            }, function ($q) use ($to) {
                return $q->when($to, function ($q) use ($to) {
                    return $q->whereHas('link', function ($q) use ($to) {
                        $q->whereRaw('created_at <= ?', $to . ' 23:59:59');
                    });
                });
            })
            // is_scan
            ->when(is_numeric($is_scan) || is_array($is_scan), function ($q) use ($is_scan) {
                return $q->whereHas('link', function ($q) use ($is_scan) {
                    switch (true) {
                        case is_array($is_scan):
                            $q->whereIn('is_scan', $is_scan);
                            break;
                        default:
                            $q->where('is_scan', $is_scan);
                            break;
                    }
                });
            })
            // user
            ->when($user, function ($q) use ($user) {
                return $q->whereHas('user', function ($q) use ($user) {
                    $q->where('name', 'like', "%$user%")
                        ->orWhere('email', 'like', "%$user%");
                });
            })
            // note
            ->when($note, function ($q) use ($note) {
                return $q->whereHas('link', function ($q) use ($note) {
                    $q->where('note', 'like', "%$note%");
                });
            })
            // type
            ->when(in_array($type, GlobalConstant::LINK_STATUS), function ($q) use ($type) {
                return $q->whereHas('link', function ($q) use ($type) {
                    $q->where('type', $type);
                });
            })
            // status
            ->when(strlen($status), function ($q) use ($status) {
                return $q->whereHas('link', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            })
            ->get()?->toArray() ?? [];

        // dd(DB::getRawQueryLog());

        $userLinks = array_map(function ($value) {
            return [
                ...$value,
                'accounts' => UserLink::with(['link', 'user'])
                    ->whereHas('link', function ($q) use ($value) {
                        $q->where('link_or_post_id', $value['link']['link_or_post_id']);
                    })->get()
            ];
        }, $userLinks);

        return response()->json([
            'status' => 0,
            'links' => $userLinks
        ]);
    }

    public function updateMultipleLinkByLinkOrPostId(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'required|array',
                'links.*.link_or_post_id' => 'required|string',
                'links.*.title' => 'nullable|string',
                'links.*.time' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.comment_first' => 'nullable|string',
                'links.*.comment_second' => 'nullable|string',
                'links.*.data_first' => 'nullable|string',
                'links.*.data_second' => 'nullable|string',
                'links.*.emotion_first' => 'nullable|string',
                'links.*.emotion_second' => 'nullable|string',
                'links.*.is_scan' => 'nullable|in:0,1,2',
                'links.*.status' => 'nullable|in:0,1',
                'links.*.note' => 'nullable|string',
                'links.*.end_cursor' => 'nullable|string',
                'links.*.type' => 'nullable|in:0,1,2',
            ]);

            DB::beginTransaction();

            foreach ($data['links'] as $value) {
                # code...
                $link = Link::firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    throw new Exception('link_or_post_id không tồn tại');
                }
                $link->update($value);
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

    public function updateLinkByLinkOrPostId(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'required|array',
                'links.*.link_or_post_id' => 'required|string',
                'links.*.title' => 'nullable|string',
                'links.*.time' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.comment_first' => 'nullable|string',
                'links.*.comment_second' => 'nullable|string',
                'links.*.data_first' => 'nullable|string',
                'links.*.data_second' => 'nullable|string',
                'links.*.emotion_first' => 'nullable|string',
                'links.*.emotion_second' => 'nullable|string',
                'links.*.is_scan' => 'nullable|in:0,1,2',
                'links.*.status' => 'nullable|in:0,1',
                'links.*.note' => 'nullable|string',
                'links.*.end_cursor' => 'nullable|string',
                'links.*.type' => 'nullable|in:0,1,2',
            ]);

            DB::beginTransaction();

            foreach ($data['links'] as $key => &$value) {
                # code...
                $link = Link::firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    throw new Exception('link_or_post_id không tồn tại');
                }
                unset($value['link_or_post_id']);
                $link->update($value);
                $value['link_id'] = $link->id;
                $value['created_at'] = now();
                $value['updated_at'] = now();
            }
            LinkHistory::insert($data['links']);
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

    public function getByType(Request $request)
    {
        return response()->json([
            'status' => 0,
            'links' => Link::where('type', $request->type)->get()
        ]);
    }

    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|string',
                'title' => 'nullable|string',
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
                'type' => 'required|in:0,1,2',
            ]);

            unset($data['user_id']);
            DB::beginTransaction();
            $link = Link::create($data);
            UserLink::create([
                'user_id' => $data['user_id'],
                'link_id' => $link->id
            ]);
            DB::commit();
            Toastr::success('Thêm thành công', 'Thông báo');
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), 'Thông báo');
        }

        return redirect()->back();
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'nullable|array',
                'links.*.title' => 'required|string',
                'links.*.time' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.comment_first' => 'nullable|string',
                'links.*.comment_second' => 'nullable|string',
                'links.*.data_first' => 'nullable|string',
                'links.*.data_second' => 'nullable|string',
                'links.*.emotion_first' => 'nullable|string',
                'links.*.emotion_second' => 'nullable|string',
                'links.*.is_scan' => 'nullable|in:0,1,2',
                'links.*.note' => 'nullable|string',
                'links.*.link_or_post_id' => 'required|string',
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

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer',
                'title' => 'nullable|string',
                'time' => 'nullable|string',
                'content' => 'nullable|string',
                'comment_first' => 'nullable|string',
                'comment_second' => 'nullable|string',
                'data_first' => 'nullable|string',
                'data_second' => 'nullable|string',
                'emotion_first' => 'nullable|string',
                'emotion_second' => 'nullable|string',
                'is_scan' => 'nullable|in:0,1,2',
                'status' => 'nullable|in:0,1',
                'note' => 'nullable|string',
                'end_cursor' => 'nullable|string',
                'link_or_post_id' => 'nullable|string',
                'type' => 'nullable|in:0,1,2',
            ]);

            unset($data['id']);
            Link::where('id', $request->input('id'))->update($data);

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

    public function updateIsScanByLinkOrPostId(Request $request)
    {
        $data = $request->validate([
            'link_or_post_id' => 'required|array',
            'title' => 'nullable|string',
            'time' => 'nullable|string',
            'content' => 'nullable|string',
            'comment_first' => 'nullable|string',
            'comment_second' => 'nullable|string',
            'data_first' => 'nullable|string',
            'data_second' => 'nullable|string',
            'emotion_first' => 'nullable|string',
            'emotion_second' => 'nullable|string',
            'is_scan' => 'nullable|in:0,1,2',
            'status' => 'nullable|in:0,1',
            'note' => 'nullable|string',
            'end_cursor' => 'nullable|string',
            'type' => 'nullable|in:0,1,2',
        ]);

        $links = Link::whereIn('link_or_post_id', $data['link_or_post_id']);
        if ($links->count() === 0) {
            throw new Exception('link_or_post_id không tồn tại');
        }
        unset($data['link_or_post_id']);
        $links->update($data);

        return response()->json([
            'status' => 0,
        ]);
    }

    public function updateLinkByListLinkId(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'title' => 'nullable|string',
            'time' => 'nullable|string',
            'content' => 'nullable|string',
            'comment_first' => 'nullable|string',
            'comment_second' => 'nullable|string',
            'data_first' => 'nullable|string',
            'data_second' => 'nullable|string',
            'emotion_first' => 'nullable|string',
            'emotion_second' => 'nullable|string',
            'is_scan' => 'nullable|in:0,1,2',
            'status' => 'nullable|in:0,1',
            'note' => 'nullable|string',
            'delay' => 'nullable|string',
            'end_cursor' => 'nullable|string',
            'type' => 'nullable|in:0,1,2',
        ]);

        $links = Link::whereIn('id', $data['ids']);
        if ($links->count() === 0) {
            throw new Exception('Link không tồn tại');
        }
        unset($data['ids']);
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

    public function index()
    {
        return response()->json([
            'status' => 0,
            'links' => Link::all()
        ]);
    }
}
