<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\ScheduleEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ScheduleEmployeeController extends Controller
{
    public array $months = [
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    ];

    public function index(Request $request)
    {
        $month = $request->input('month');

        $data['months'] = $this->months;
        $data['monthName'] = '';
        $data['monthKey'] = '';

        if ($month) {
            $data['monthName'] = $this->months[$month];
            $data['monthKey'] = $month;

            $data['branches'] = Branch::query()->orderBy('id')->get()->toArray();
            $data['employees'] = Employee::query()->orderBy('id')->pluck('name', 'id')->toArray();

            $requestDate = Carbon::now()->month($month);
            $currentDate = Carbon::now();

            if ($month == 1 && $currentDate->month == 12) {
                $requestDate = $requestDate->addYear();
            }

            $data['year'] = $requestDate->year;
            $daysInMonth = $requestDate->daysInMonth;

            $from = $requestDate->year . '-' . $requestDate->month . '-01';
            $to = $requestDate->year . '-' . $requestDate->month . '-'. $daysInMonth;

            $schedules = ScheduleEmployee::query()
                ->with(['employee', 'branch'])
                ->whereBetween('date', [$from, $to])
                ->get();

            for ($i=1; $i <= $daysInMonth; $i++) {
                $day = $i < 10 ? '0'.$i : $i;
                $month = $requestDate->month < 10 ? '0'.$requestDate->month : $requestDate->month;
                $date = $requestDate->year .'-'.$month.'-'.$day;

                $data['dates'][$i] = [];

                if ($currentDate->month === $requestDate->month && $i < $requestDate->day) {
                    $data['dates'][$i]['readonly'] = true;
                }

                foreach ($schedules as $schedule) {
                    if($schedule->date === $date) {
                        $data['dates'][$i]['branches'][$schedule->branch->id]['branchName'] = $schedule->branch->name;

                        $data['dates'][$i]['branches'][$schedule->branch->id]['employees'][] = [
                            'employee' => $schedule->employee?->name,
                            'employeeId' => $schedule->employee?->id
                        ];
                    }
                }
            }
        }

        return view('schedule.index', $data);
    }

    public function save(Request $request)
    {
        $employees = $request->input('employee');
        $year = $request->input('year');
        $date = Carbon::now()->month($request->input('month'));

        if ($year) $date = $date->year($year);

        foreach ($employees as $branchId => $cashDesks) {
            foreach ($cashDesks as $number => $days) {
                foreach ($days as $day => $employee) {
                    $data = [];

                    if (isset($employee)) {
                        $data['employee_id'] = $employee;
                        $data['branch_id'] = $branchId;
                        $data['date'] = $date->day($day)->format('Y-m-d');
                        $data['number_cash_desk'] = $number+1;

                        ScheduleEmployee::query()->updateOrCreate([
                            'branch_id' => $branchId,
                            'date' => $date->day($day)->format('Y-m-d'),
                            'number_cash_desk' => $number+1
                        ], $data);
                    }
                }
            }
        }

        return redirect()->route('schedule', ['month' => $request->input('month')]);
    }
}
