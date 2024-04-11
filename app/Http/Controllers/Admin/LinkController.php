<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Throwable;

class LinkController extends Controller
{
    public function getByType(Request $request)
    {
        return response()->json([
            'status' => 0,
            'links' => Link::where('type', $request->type)->get()
        ]);
    }

    public function getAll(Request $request)
    {
        return response()->json([
            'status' => 0,
            'links' => Link::all()
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
                'links.*.type' => 'required|in:0,1,2',
            ]);

            $data = array_map(function($item) {
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
}
