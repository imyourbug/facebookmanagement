<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\Type;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;

class ContractController extends Controller
{
    public function create()
    {
        return view('admin.contract.add', [
            'title' => 'Thêm hợp đồng',
            'customers' => Customer::all(),
            'parent_types' => Type::where('parent_id', 0)
                ->get(),
        ]);
    }

    public function getRangeTime($type, $arrValue, $start, $finish)
    {
        $start = new DateTime($start);
        $finish = new DateTime($finish);
        $result = [];
        switch ($type) {
            case 'date':
                foreach ($arrValue as $value) {
                    switch ((int)$value) {
                        case 0:
                            for ($date = $start; $date <= $finish; $date->add(new DateInterval('P1W'))) {
                                if (!in_array($date->format('Y-m-t'), $result)) {
                                    $result[] = $date->format('Y-m-t');
                                }
                            }
                            break;
                        default:
                            for ($date = $start; $date <= $finish; $date->add(new DateInterval('P1D'))) {
                                if ($date->format('d') == $value) {
                                    $result[] = $date->format('Y-m-d');
                                }
                            }
                            break;
                    }
                };
                break;
            case 'day':
                foreach ($arrValue as $value) {
                    for ($date = $start; $date <= $finish; $date->add(new DateInterval('P1D'))) {
                        $weekday = date('l', strtotime($date->format('Y-m-d')));
                        if ($weekday == $value) {
                            $result[] = $date->format('Y-m-d');
                        }
                    }
                };
                break;
        }

        return $result;
    }

    public function createTask($rangeTime, $taskType, $contractId)
    {
        $data = [];
        $task = Task::create([
            'type_id' => $taskType,
            'contract_id' => $contractId,
        ]);
        foreach ($rangeTime as $time) {
            $data[] = [
                'plan_date' => $time,
                'task_id' => $task->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        };
        if (count($data) > 0) {
            TaskDetail::insert($data);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'customer_id' => 'required|numeric',
                'start' => 'required|date',
                'finish' => 'required|date',
                'content' => 'required|string',
                'attachment' => 'nullable|string',
                'branch_ids' => 'nullable|array',
                'branch_ids.*' => 'nullable|integer',
                'tasks' => 'required|array',
                'tasks.*.task_type' => 'nullable|numeric',
                'tasks.*.time_type' => 'nullable|string|in:date,day',
                'tasks.*.value_time_type' => 'nullable|array',
                'tasks.*.value_time_type.*' => 'nullable',
            ]);
            DB::beginTransaction();
            if (!empty($data['branch_ids'])) {
                foreach ($data['branch_ids'] as $branch_id) {
                    $contract = Contract::create([
                        'name' =>  $data['name'],
                        'customer_id' =>  $data['customer_id'],
                        'start' =>  $data['start'],
                        'finish' =>  $data['finish'],
                        'content' =>  $data['content'],
                        'attachment' =>  $data['attachment'],
                        'branch_id' =>  $branch_id,
                    ]);
                    if (!empty($data['tasks'])) {
                        foreach ($data['tasks'] as $info) {
                            $rangeTime = $this->getRangeTime($info['time_type'], $info['value_time_type'], $data['start'], $data['finish']);
                            $this->createTask($rangeTime, $info['task_type'], $contract->id);
                        }
                    }
                }
            } else {
                $contract = Contract::create([
                    'name' =>  $data['name'],
                    'customer_id' =>  $data['customer_id'],
                    'start' =>  $data['start'],
                    'finish' =>  $data['finish'],
                    'content' =>  $data['content'],
                    'attachment' =>  $data['attachment'],
                ]);
                if (!empty($data['tasks'])) {
                    foreach ($data['tasks'] as $info) {
                        $rangeTime = $this->getRangeTime($info['time_type'], $info['value_time_type'], $data['start'], $data['finish']);
                        $this->createTask($rangeTime, $info['task_type'], $contract->id);
                    }
                }
            }
            DB::commit();

            return [
                'status' => 0,
                'message' => 'Tạo hợp đồng thành công'
            ];
        } catch (Throwable $e) {
            DB::rollBack();

            return [
                'status' => 1,
                'message' => $e->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|numeric',
            'id' => 'required|numeric',
            'start' => 'required|date',
            'finish' => 'required|date',
            'content' => 'required|string',
            'attachment' => 'nullable|string',
            'name' => 'required|string',
        ]);
        unset($data['id']);
        $update = Contract::firstWhere('id', $request->input('id'))->update($data);
        if ($update) {
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.update'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function delete($id)
    {
        $delete = Contract::firstWhere('id', $id)->delete();
        if ($delete) {
            Toastr::success(__('message.success.delete'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.delete'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customer_id = $request->customer_id;
            $month = $request->month;
            $contracts = Contract::with(['customer', 'branch', 'tasks.details'])
                ->when($customer_id, function ($q) use ($customer_id) {
                    return $q->whereHas('customer', function ($q) use ($customer_id) {
                        $q->where('id', $customer_id);
                    });
                })
                ->when($month, function ($q) use ($month) {
                    return $q->whereHas('tasks.details', function ($q) use ($month) {
                        $q->whereRaw('MONTH(plan_date) = ?', $month);
                    });
                })
                ->get();
            return response()->json([
                'status' => 0,
                'contracts' => $contracts
            ]);
        }

        return view('admin.contract.list', [
            'title' => 'Danh sách hợp đồng',
            'contracts' => Contract::with('customer')->get(),
            'customers' => Customer::all(),
            'parent_types' => Type::where('parent_id', 0)
                ->get(),
        ]);
    }

    public function show($id)
    {
        return view('admin.contract.edit', [
            'title' => 'Cập nhật hợp đồng',
            'contract' => Contract::with(['tasks'])->firstWhere('id', $id),
            'customers' => Customer::all(),
        ]);
    }

    public function detail($id)
    {
        return view('admin.contract.detail', [
            'title' => 'Chi tiết hợp đồng',
            'contract' => Contract::with([
                'tasks.details', 'tasks.type',
            ])->firstWhere('id', $id),
            'customers' => Customer::all(),
            'users' => User::with(['staff'])->where('role', GlobalConstant::ROLE_STAFF)->get(),
            'types' => Type::all(),
            'contracts' => Contract::with(['branch'])->get(),
        ]);
    }

    public function destroy($id)
    {
        try {
            Contract::firstWhere('id', $id)->delete();

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
