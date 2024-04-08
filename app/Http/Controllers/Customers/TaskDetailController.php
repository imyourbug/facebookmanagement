<?php

namespace App\Http\Controllers\Customers;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Chemistry;
use App\Models\Contract;
use App\Models\Item;
use App\Models\Map;
use App\Models\Solution;
use App\Models\TaskDetail;
use App\Models\TaskMap;
use App\Models\Type;
use App\Models\User;

class TaskDetailController extends Controller
{
    public function show($id)
    {
        return view('customer.taskdetail.edit', [
            'title' => 'Chi tiết nhiệm vụ',
            'taskDetail' => TaskDetail::with(['task.type'])->firstWhere('id', $id),
            'staffs' => User::with('staff')->where('role', GlobalConstant::ROLE_STAFF)->get(),
            'types' => Type::all(),
            'chemistries' => Chemistry::all(),
            'solutions' => Solution::all(),
            'items' => Item::all(),
            'maps' => Map::all(),
            'contracts' => Contract::with(['branch'])->get(),
            'taskMaps' => TaskMap::with(['task', 'map'])->where('task_id', $id)->get(),
        ]);
    }
}
