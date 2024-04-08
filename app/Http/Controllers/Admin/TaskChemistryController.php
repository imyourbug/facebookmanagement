<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskChemistry;
use Illuminate\Http\Request;
use Throwable;

class TaskChemistryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'unit' => 'required|string',
            'kpi' => 'required|numeric',
            'task_id' => 'required|numeric',
            'chemistry_id' => 'required|numeric',
        ]);
        try {
            TaskChemistry::create($data);
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
                'chemistry_id' => 'required|numeric',
            ]);
            unset($data['id']);
            TaskChemistry::where('id', $request->input('id'))->update($data);
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
            'taskChemistries' => TaskChemistry::with(['task', 'chemistry'])->where('task_id', $request->id)->get(),
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'status' => 0,
            'taskChemistry' => TaskChemistry::with(['task', 'chemistry'])->firstWhere('id', $id),
        ]);
    }

    public function destroy($id)
    {
        try {
            TaskChemistry::firstWhere('id', $id)->delete();

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
