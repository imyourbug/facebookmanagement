<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\TaskDetail;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use MPDF;
use Throwable;

class ExportController extends Controller
{

    public function dowload(Request $request)
    {
        $filename = $request->filename ?? '';
        if ($filename) {
            return response()->file(
                $filename
            );
        }
    }

    //
    public function plan(Request $request)
    {
        try {
            $data = $request->validate([
                'month' => 'required|numeric|between:1,12',
                'year' => 'required|numeric|min:1900',
                'type_report' => 'required|in:0,1',
                'contract_id' => 'required|numeric',
                'image_charts' => 'nullable|array',
                'image_trend_charts' => 'nullable|array',
                'image_annual_charts' => 'nullable|array',
                'user_id' => 'required|numeric',
                'display' => 'required|in:0,1',
            ]);

            $data['creator'] = User::with(['staff'])->firstWhere('id', $data['user_id'])->toArray();
            $pdf = null;
            $filename = '';
            switch ((int)$data['type_report']) {
                case 0:
                    $pdf = MPDF::loadView('pdf.report_plan', ['data' => array_merge($data, $this->getReportPlanByMonthAndYear($data['month'], $data['year'], $data['contract_id']))]);
                    $filename = 'Báo cáo kế hoạch ';
                    break;
                case 1:
                    $pdf = MPDF::loadView('pdf.report_result', ['data' =>
                    $data
                        + $this->getReportWorkByMonthAndYear($data['month'], $data['year'], $data['contract_id'])]);
                    $filename = 'Báo cáo kết quả ';
                    break;
                default:
                    break;
            }
            $filename .= 'tháng ' . $data['month'] . ' năm ' . $data['year'];
            $filename = Str::slug($filename) . '.pdf';
            // return $pdf->stream($filename);

            $path = storage_path() . '/app/public/pdf/';
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $pdf->save($path . $filename, 'F');

            return response()->json([
                'status' => 0,
                'url' => '/storage/pdf/' . $filename
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'url' => $e->getMessage()
            ]);
        }
    }

    public function getReportPlanByMonthAndYear($month, $year, $contract_id)
    {
        $contract = Contract::with([
            'customer',
            'branch',
            'tasks.type.parent',
            'tasks.details.taskMaps.map',
            'tasks.details.taskItems.item',
            'tasks.details.taskSolutions.solution',
            'tasks.details.taskChemitries.chemistry',
            'tasks.details.taskStaffs.user.staff',
        ])
            ->where('id', $contract_id)
            ->first()
            ->toArray();
        $tasks = $contract['tasks'];
        $result = [];
        $result['customer'] = $contract['customer'];
        $result['branch'] = $contract['branch'];
        foreach ($tasks as $task) {
            $tmp = [];
            foreach ($task['details'] as $detail) {
                $date = explode('-', $detail['plan_date']);
                if ((int)$date[0] === (int)$year && (int)$date[1] === (int)$month) {
                    $tmp[] = $detail;
                }
            }
            unset($task['details']);
            $result['tasks'][] = [
                'details' => $tmp,
                ...$task,
            ];
        }

        return $result;
    }

    public function getReportWorkByMonthAndYear($month, $year, $contract_id)
    {
        $contract = Contract::with([
            'customer',
            'branch',
            'tasks.type.parent',
            'tasks.details.taskMaps.map',
            'tasks.details.taskItems.item',
            'tasks.details.taskSolutions.solution',
            'tasks.details.taskChemitries.chemistry',
            'tasks.details.taskStaffs.user.staff',
        ])
            ->where('id', $contract_id)
            ->first()
            ->toArray();
        $tasks = $contract['tasks'];
        $result = [];
        $result['customer'] = $contract['customer'];
        $result['branch'] = $contract['branch'];
        foreach ($tasks as $task) {
            $tmp = [];
            foreach ($task['details'] as $detail) {
                $date = explode('-', $detail['plan_date']);
                if ((int)$date[0] === (int)$year && (int)$date[1] === (int)$month) {
                    $tmp[] = $detail;
                }
            }
            unset($task['details']);
            $result['tasks'][] = [
                'details' => $tmp,
                ...$task,
            ];
        }

        // dd($result);
        foreach ($result['tasks'] as $key => &$task) {
            foreach ($task['details'] as $keyDetail => &$detail) {
                $tmp = [];
                foreach ($detail['task_maps'] as $keyTaskMap => $taskMap) {
                    $tmp[substr($taskMap['code'], 0, 1)][] = $taskMap;
                }
                $detail['task_maps'] = $tmp;
            }
        }
        // dd($result);

        return $result;
    }

    public function getDataMapChart(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $contract_id = $request->contract_id;

        $task_details = TaskDetail::with(['task', 'taskMaps.map'])
            ->whereRaw('MONTH(plan_date) = ?', $month)
            ->whereRaw('YEAR(plan_date) = ?', $year)
            ->whereHas('task', function ($q) use ($contract_id) {
                $q->where('contract_id', $contract_id);
            })
            ->get();
        $result = [];
        foreach ($task_details as $key => $task_detail) {
            DB::enableQueryLog();
            $data_map = DB::table('task_maps')
                ->selectRaw('map_id, maps.code as code,
            SUM(CASE
                WHEN kpi is NULL THEN 0
                WHEN kpi = "" THEN 0
                ELSE kpi
            END) as all_kpi,
            SUM(CASE
                WHEN result is NULL THEN 0
                WHEN result = "" THEN 0
                ELSE result
            END) as all_result')
                ->join('maps', 'maps.id', '=', 'task_maps.map_id')
                ->whereRaw('task_id = ?', $task_detail->id)
                ->groupByRaw('map_id, code')
                ->orderBy('map_id')
                ->get()
                ?->toArray() ?? [];
            foreach ($data_map as $key => $data) {
                $data = (array)$data;
                if (isset($result[$task_detail->task->id][$data['map_id']])) {
                    $result[$task_detail->task->id][$data['map_id']]['all_kpi'] += $data['all_kpi'];
                    $result[$task_detail->task->id][$data['map_id']]['all_result'] += $data['all_result'];
                } else {
                    $result[$task_detail->task->id][$data['map_id']] = $data;
                }
            }
            $result[$task_detail->task->id]['task_id'] = $task_detail->task->id;
        }

        foreach ($result as $key => &$rs) {
            $tmp = [];
            foreach ($rs as $keyRs => $valueRs) {
                if (is_numeric($keyRs)) {
                    $tmp[substr($valueRs['code'], 0, 1)][$valueRs['map_id']] = $valueRs;
                }
            }
            $tmpTaskId = $rs['task_id'];
            $rs = $tmp;
            $rs['task_id'] = $tmpTaskId;
        }

        return [
            'status' => 0,
            'data' => $result,
        ];
    }

    public function getTrendDataMapChart(Request $request)
    {
        $contract_id = $request->contract_id;

        $contract = Contract::with(['tasks.details'])->firstWhere('id', $contract_id);
        $start = new DateTime($contract->start);
        $finish = new DateTime($contract->finish);
        $keys = [];
        for ($date = $start; $date <= $finish; $date->add(new DateInterval('P3W'))) {
            if (!key_exists($date->format('Y-m'), $keys)) {
                $keys[$date->format('Y-m')] = [
                    'month' => $date->format('m'),
                    'year' => $date->format('Y'),
                ];
            }
        }
        $result = [];
        foreach ($contract->tasks as $task) {
            $tmp = [];
            foreach ($keys as $key => $date) {
                $key_task_details = TaskDetail::with(['task', 'taskMaps.map'])
                    ->whereRaw('MONTH(plan_date) = ?', $date['month'])
                    ->whereRaw('YEAR(plan_date) = ?', $date['year'])
                    ->where('task_id', $task->id)
                    ->get()->pluck('id');
                $value = DB::table('task_maps')
                    ->selectRaw('SUM(CASE
                                    WHEN kpi is NULL THEN 0
                                    WHEN kpi = "" THEN 0
                                    ELSE kpi
                                END) as all_kpi,
                                SUM(CASE
                                    WHEN result is NULL THEN 0
                                    WHEN result = "" THEN 0
                                    ELSE result
                                END) as all_result')
                    ->join('maps', 'maps.id', '=', 'task_maps.map_id')
                    ->whereIn('task_id', $key_task_details)
                    ->first();

                $tmp[$key] = [
                    ...$date,
                    'kpi' => $value->all_kpi ?? 0,
                    'result' => $value->all_result ?? 0,
                ];
            }
            $result[$task->id] = [
                ...$tmp,
                'task_id' => $task->id
            ];
        }

        return [
            'status' => 0,
            'data' => $result,
        ];
    }

    public function getDataAnnualMapChart(Request $request)
    {
        $this_year = now()->format('Y');
        $last_year = now()->subYear(1)->format('Y');
        $contract = Contract::with(['tasks.details'])->firstWhere('id', $request->contract_id);
        $result = [];
        foreach ($contract->tasks as $task) {
            $tmp_this_year = [];
            $tmp_last_year = [];
            for ($month = 1; $month <= 12; $month++) {
                $tmp_this_year[$month] = $this->getDataByMonthAndYear($month, $this_year, $task->id);
                $tmp_last_year[$month] = $this->getDataByMonthAndYear($month, $last_year, $task->id);
            }
            $result[$task->id] = [
                'task_id' => $task->id,
                'last_year' => [
                    ...$tmp_last_year,
                    'year' => $last_year
                ],
                'this_year' => [
                    ...$tmp_this_year,
                    'year' => $this_year
                ],
            ];
        }

        return [
            'status' => 0,
            'data' => $result,
        ];
    }

    public function getDataByMonthAndYear($month, $year, $task_id)
    {
        $result = [];
        $key_task_details = TaskDetail::with(['task', 'taskMaps.map'])
            ->whereRaw('MONTH(plan_date) = ?', $month)
            ->whereRaw('YEAR(plan_date) = ?', $year)
            ->where('task_id',  $task_id)
            ->get()->pluck('id');
        $value = DB::table('task_maps')
            ->selectRaw('SUM(CASE
                            WHEN kpi is NULL THEN 0
                            WHEN kpi = "" THEN 0
                            ELSE kpi
                            END) as all_kpi,
                        SUM(CASE
                            WHEN result is NULL THEN 0
                            WHEN result = "" THEN 0
                            ELSE result
                            END) as all_result')
            ->join('maps', 'maps.id', '=', 'task_maps.map_id')
            ->whereIn('task_id', $key_task_details)
            ->first();
        $result = [
            'kpi' => $value->all_kpi ?? 0,
            'result' => $value->all_result ?? 0,
            'month' => $month,
            'year' => $year,
        ];

        return $result;
    }
}
