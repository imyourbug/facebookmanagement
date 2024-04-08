<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskStaff;
use Illuminate\Http\Request;
use Throwable;

class TaskStaffController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'task_id' => 'required|numeric',
                'user_id' => 'required|numeric',
            ]);
            TaskStaff::create($data);
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
                'task_id' => 'required|numeric',
                'user_id' => 'required|numeric',
            ]);
            unset($data['id']);
            TaskStaff::where('id', $request->input('id'))->update($data);
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
            'taskStaff' => TaskStaff::with(['task', 'user.staff'])
                ->where('task_id', $request->id)
                ->get(),
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'status' => 0,
            'taskStaff' => TaskStaff::with(['task', 'user'])->firstWhere('id', $id),
        ]);
    }

    public function destroy($id)
    {
        try {
            TaskStaff::firstWhere('id', $id)->delete();

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
