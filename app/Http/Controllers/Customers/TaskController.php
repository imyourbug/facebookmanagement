<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\TaskDetail;
use App\Models\Type;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function show($id, Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $tasks = TaskDetail::with([
            'task',
        ])
            ->when($from, function ($q) use ($from) {
                return $q->where('created_at', '>=', $from . ' 00:00:00');
            })->when($to, function ($q) use ($to) {
                return $q->where('created_at', '<=', $to . ' 23:59:59');
            })->get();
        return view('customer.task.detail', [
            'title' => 'Danh sách chi tiết nhiệm vụ',
            'tasks' => $tasks,
            'contracts' => Contract::with(['branch'])->get(),
            'types' => Type::all(),
        ]);
    }
}
