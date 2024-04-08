<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskMap;
use Illuminate\Http\Request;
use Throwable;

class TaskMapController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'unit' => 'required|string',
                'kpi' => 'required|numeric',
                'task_id' => 'required|numeric',
                'map_id' => 'required|numeric',
            ]);
            TaskMap::create($data);
            return response()->json([
                'status' => 0,
                'message' => 'Tạo thành công'
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
                'id' => 'required|numeric',
                'unit' => 'required|string',
                'code' => 'required|string',
                'kpi' => 'required|numeric',
                'result' => 'nullable|numeric',
                'image' => 'nullable|string',
                'detail' => 'nullable|string',
                'task_id' => 'required|numeric',
                'map_id' => 'nullable|numeric',
            ]);
            unset($data['id']);
            TaskMap::where('id', $request->input('id'))->update($data);
            return response()->json([
                'status' => 0,
                'message' => 'Cập nhật nhiệm vụ thành công'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function index(Request $request)
    {
        return response()->json([
            'status' => 0,
            'taskMaps' => TaskMap::with(['task', 'map'])->where('task_id', $request->id)->get(),
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'status' => 0,
            'taskMap' => TaskMap::with(['task', 'map'])->firstWhere('id', $id),
        ]);
    }

    public function destroy($id)
    {
        try {
            TaskMap::firstWhere('id', $id)->delete();

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
            $data = $request->validate([
                'ids' => 'nullable|array',
            ]);
            TaskMap::whereIn('id', $data['ids'])->delete();

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
