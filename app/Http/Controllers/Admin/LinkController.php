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

class LinkController extends Controller
{
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
                'title' => 'required|string',
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

    public function getAll(Request $request)
    {
        $user_id = $request->user_id;
        $link_id = $request->link_id;
        $is_scan = $request->is_scan;
        $type = (string)$request->type;

        $userLinks = UserLink::with(['link', 'user'])
            ->when($user_id, function ($q) use ($user_id) {
                return $q->where('user_id', $user_id);
            })
            ->when($link_id, function ($q) use ($link_id) {
                return $q->where('link_id', $link_id);
            })
            ->when($is_scan, function ($q) use ($is_scan) {
                return $q->whereHas('link', function ($q) use ($is_scan) {
                    $q->whereIn('is_scan', $is_scan);
                });
            })
            ->when(in_array($type, GlobalConstant::LINK_STATUS), function ($q) use ($type) {
                return $q->whereHas('link', function ($q) use ($type) {
                    $q->where('type', $type);
                });
            })
            ->get()?->toArray() ?? [];

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
}
