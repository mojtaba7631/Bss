<?php

namespace App\Http\Controllers\maliManager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentPeriod;
use App\Models\PaymentPeriodDetail;
use App\Models\Phase;
use App\Models\User;
use Illuminate\Http\Request;

class maliPayableController extends Controller
{
    function index()
    {
        $payments = Phase::query()
            ->select('*', 'phases.id as phase_id', 'projects.id as project_id', 'phases.cost as phase_cost')
            ->join('projects', 'projects.id', '=', 'phases.project_id')
            ->where('phases.status', 5)
            ->where('projects.status', 8)
            ->where('phases.sent_to_tarh', 0)
            ->where('projects.supervisor_id', '!=', null)
            ->get();

        foreach ($payments as $payment) {
            $payment['supervisor'] = User::query()
                ->where('id', $payment['supervisor_id'])
                ->first();

            $payment['employer'] = User::query()
                ->select('center_name')
                ->where('id', $payment['employer_id'])
                ->first();

            $payment['user'] = User::query()
                ->where('id', $payment['user_id'])
                ->first();

            $calculated = $this->calculate_total_payed_reminding($payment['project_id'], intval($payment['contract_cost']));

            $payment['payed'] = $calculated[0];
            $payment['reminding'] = $calculated[2];
        }

        $searched = false;
        return view('mali_manager.payable.index', compact('payments', 'searched'));
    }

    function add(Request $request)
    {
        $input = $request->all();

        $period = PaymentPeriod::create([
            'title' => $input['period_name'],
            'fields' => $input['fields'],
            'total' => intval($input['total']),
            'bank' => intval($input['bank']),
        ]);

        $phases_id = $input['phases_id'];
        Phase::query()
            ->whereIn('id', $phases_id)
            ->update([
                'sent_to_tarh' => 1
            ]);

        foreach ($phases_id as $id) {
            PaymentPeriodDetail::create([
                'pp_id' => $period['id'],
                'phase_id' => $id,
            ]);
        }

        return response()->json([
            'error' => false,
            'errorMsg' => 'ثبت دوره با موفقیت انجام شد'
        ]);
    }

    function calculate_total_payed_reminding($project_id, $total)
    {
        $payed = Payment::query()
            ->where('project_id', $project_id)
            ->where('status', 1)
            ->sum('price');

        $reminding = $total - $payed;

        return [$payed, $total, $reminding];
    }
}
