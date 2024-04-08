<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Chemistry;
use App\Models\Contract;
use App\Models\Item;
use App\Models\Map;
use App\Models\Solution;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\TaskMap;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;

class ReportController extends Controller
{
    public function reload($id, Request $request)
    {
        $taskMaps = TaskDetail::with(['taskMaps'])->firstWhere('id', $id)->taskMaps ?? [];
        foreach ($taskMaps as $taskMap) {
            $taskMap->update([
                'result' =>  $taskMap->fake_result
            ]);
        }

        Toastr::success('Tải lên kết quả mẫu thành công', __('title.toastr.success'));
        return redirect()->back();
    }

    public function index(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $tasks = Task::with([
            'contract.branch', 'type',
        ])
            ->when($from, function ($q) use ($from) {
                return $q->where('created_at', '>=', $from . ' 00:00:00');
            })->when($to, function ($q) use ($to) {
                return $q->where('created_at', '<=', $to . ' 23:59:59');
            })->get();

        return view('admin.report.list', [
            'title' => 'Báo cáo nhiệm vụ',
            'tasks' => $tasks,
            'contracts' => Contract::with(['branch'])->get(),
            'types' => Type::all(),
            'users' => User::with(['staff'])->where('role', GlobalConstant::ROLE_STAFF)->get(),
        ]);
    }

    public function task($id, Request $request)
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
            })
            ->where('task_id', $id)
            ->get();

        return view('admin.report.task.index', [
            'title' => 'Danh sách báo cáo chi tiết nhiệm vụ',
            'tasks' => $tasks,
            'contracts' => Contract::with(['branch'])->get(),
            'types' => Type::all(),
        ]);
    }

    public function detail($id, Request $request)
    {
        return view('admin.report.task.detail', [
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

    public function duplicate(Request $request)
    {
        try {
            $data = $request->validate([
                'month_from' => 'required|numeric',
                'month_to' => 'required|numeric',
                'year_from' => 'required|numeric',
                'year_to' => 'required|numeric',
                'contract_id' => 'required|numeric',
            ]);
            if ($data['month_from'] === $data['month_to'] && $data['year_from'] === $data['year_to']) {
                Toastr::error('Hãy chọn 2 tháng khác nhau', __('title.toastr.fail'));

                return redirect()->back();
            }
            $tasks = Task::with([
                'details.taskChemitries',
                'details.taskMaps',
                'details.taskSolutions',
                'details.taskItems',
                'details.taskStaffs',
            ])
                ->whereHas('details', function ($q) use ($data) {
                    $q->whereRaw('MONTH(plan_date) = ?', $data['month_from'])
                        ->whereRaw('YEAR(plan_date) = ?', $data['year_from']);
                })
                ->where('contract_id', $data['contract_id'])
                ->get();

            DB::beginTransaction();
            foreach ($tasks as $task) {
                foreach ($task->details as $detail) {
                    $month = $detail->plan_date->format('m');
                    $year = $detail->plan_date->format('Y');
                    if ($month == $data['month_to'] && $year == $data['year_to']) {
                        // taskChemitry
                        foreach ($detail->taskChemitries as $taskChemitry) {
                            $taskChemitry->delete();
                        }
                        // taskMap
                        foreach ($detail->taskMaps as $taskMap) {
                            $taskMap->delete();
                        }
                        // taskSolution
                        foreach ($detail->taskSolutions as $taskSolution) {
                            $taskSolution->delete();
                        }
                        // taskItem
                        foreach ($detail->taskItems as $taskItem) {
                            $taskItem->delete();
                        }
                        // taskStaff
                        foreach ($detail->taskStaffs as $taskStaff) {
                            $taskStaff->delete();
                        }
                    }
                    if ($month == $data['month_from'] && $year == $data['year_from']) {

                        $new_plan_date = $detail->plan_date->format($data['year_to'] . '-' . $data['month_to'] . '-d');
                        $date = explode('-', $new_plan_date);
                        if (checkdate((int) $date[1], (int) $date[2], (int) $date[0])) {
                            $newDetail = $detail->replicate()
                                ->fill([
                                    'plan_date' => $new_plan_date,
                                ]);
                            $newDetail->save();
                            // taskChemitry
                            foreach ($detail->taskChemitries as $taskChemitry) {
                                $taskChemitry->replicate()
                                    ->fill(['task_id' => $newDetail->id])
                                    ->save();
                            }
                            // taskMap
                            foreach ($detail->taskMaps as $taskMap) {
                                $taskMap->replicate()
                                    ->fill(['task_id' => $newDetail->id])
                                    ->save();
                            }
                            // taskSolution
                            foreach ($detail->taskSolutions as $taskSolution) {
                                $taskSolution->replicate()
                                    ->fill(['task_id' => $newDetail->id])
                                    ->save();
                            }
                            // taskItem
                            foreach ($detail->taskItems as $taskItem) {
                                $taskItem->replicate()
                                    ->fill(['task_id' => $newDetail->id])
                                    ->save();
                            }
                            // taskStaff
                            foreach ($detail->taskStaffs as $taskStaff) {
                                $taskStaff->replicate()
                                    ->fill(['task_id' => $newDetail->id])
                                    ->save();
                            }
                        }
                    }
                }
            }
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));

            return redirect()->back();
        }
        DB::commit();
        Toastr::success('Sao chép thành công', __('title.toastr.success'));

        return redirect()->back();
    }
}
