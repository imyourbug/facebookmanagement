<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\SettingTaskItem;
use App\Models\TaskItem;
use Illuminate\Http\Request;
use Throwable;

class SettingTaskItemController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'unit' => 'required|string',
                'kpi' => 'required|numeric',
                'task_id' => 'required|numeric',
                'item_id' => 'required|numeric',
            ]);
            SettingTaskItem::create($data);
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
                'kpi' => 'required|numeric',
                'result' => 'nullable|numeric',
                'image' => 'nullable|string',
                'detail' => 'nullable|string',
                // 'task_id' => 'required|numeric',
                'item_id' => 'required|numeric',
            ]);
            unset($data['id']);
            SettingTaskItem::where('id', $request->input('id'))->update($data);
            return response()->json([
                'status' => 0,
                'message' => 'Cập nhật thành công'
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
            'taskItems' => SettingTaskItem::with(['task', 'item'])->where('task_id', $request->id)->get(),
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'status' => 0,
            'taskItem' => SettingTaskItem::with(['task', 'item'])->firstWhere('id', $id),
        ]);
    }

    public function destroy($id)
    {
        try {
            SettingTaskItem::firstWhere('id', $id)->delete();

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
