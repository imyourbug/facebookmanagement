<?php

namespace App\Http\Controllers\Users;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\UserLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class LinkController extends Controller
{
    public function getAll(Request $request)
    {
        $user_id = $request->user_id;
        $link_id = $request->link_id;
        $is_scan = $request->is_scan;
        $type = $request->type;

        return response()->json([
            'status' => 0,
            'links' => UserLink::with(['link', 'user'])
                ->when($user_id, function ($q) use ($user_id) {
                    return $q->where('user_id', $user_id);
                })
                ->when($link_id, function ($q) use ($link_id) {
                    return $q->where('link_id', $link_id);
                })
                ->when($is_scan, function ($q) use ($is_scan) {
                    return $q->whereHas('link', function ($q) use ($is_scan) {
                        $q->where('is_can', $is_scan);
                    });
                })
                ->when(in_array($type, GlobalConstant::LINK_STATUS), function ($q) use ($type) {
                    return $q->whereHas('link', function ($q) use ($type) {
                        $q->where('type', $type);
                    });
                })
                ->get()
        ]);
    }

    public function store(Request $request)
    {
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
    }

    public function getLinkScanIsOn(Request $request)
    {
        $user_id = $request->user_id;
        $link_id = $request->link_id;

        return UserLink::with(['link', 'user'])
            ->where('user_id', $user_id)
            ->where('link_id', $link_id)
            ->get();
    }

    public function update(Request $request)
    {
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
            'note' => 'nullable|string',
            'link_or_post_id' => 'nullable|string',
            'type' => 'nullable|in:0,1,2',
        ]);

        unset($data['id']);
        $rs = Link::where('id', $request->input('id'))->update($data);

        if (!$rs) {
            return response()->json([
                'status' => GlobalConstant::STATUS_ERROR,
                'message' => 'Cập nhật thất bại',
            ]);
        }

        return response()->json([
            'status' => 0,
        ]);
    }
}
