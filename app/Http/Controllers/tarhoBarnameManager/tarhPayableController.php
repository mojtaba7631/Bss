<?php

namespace App\Http\Controllers\tarhoBarnameManager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentPeriod;
use App\Models\PaymentPeriodDetail;
use App\Models\Phase;
use App\Models\User;
use Illuminate\Http\Request;

class tarhPayableController extends Controller
{
    function index()
    {
        $periods = PaymentPeriod::query()
            ->where('status', 0)
            ->paginate(30);

        return view('tarhoBarname_manager.payable.index', compact('periods'));
    }

    function detail($id)
    {
        $period_detail = PaymentPeriod::query()
            ->where('id', $id)
            ->first();

        $period_detail->update([
            'seen' => 1
        ]);

        $fields = json_decode($period_detail['fields']);

        $records = PaymentPeriodDetail::query()
            ->select('*', 'phases.id as phase_id', 'phases.cost as phase_cost')
            ->join('phases', 'phases.id', '=', 'payment_period_detail.phase_id')
            ->join('projects', 'phases.project_id', '=', 'projects.id')
            ->where('pp_id', $id)
            ->get();

        foreach ($records as $record) {
            $record['supervisor'] = User::query()
                ->where('id', $record['supervisor_id'])
                ->first();

            $record['employer'] = User::query()
                ->where('id', $record['employer_id'])
                ->first();

            $record['user'] = User::query()
                ->where('id', $record['user_id'])
                ->first();

            $calculated = $this->calculate_total_payed_reminding($record['project_id'], intval($record['contract_cost']));

            $record['payed'] = $calculated[0];
            $record['reminding'] = $calculated[2];
        }

        return view('tarhoBarname_manager.payable.detail', compact('period_detail', 'records', 'fields'));
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

    function send_to_mali(Request $request)
    {
        $items = json_decode($request->items);
        $period_id = $request->period_id;

        PaymentPeriod::query()
            ->where('id', $period_id)
            ->firstOrFail()->update([
                'status' => 1
            ]);

        foreach ($items as $item) {
            $phase_id = intval($item->phase_id);

            $phase_info = Phase::query()
                ->where('id', $phase_id)
                ->firstOrFail();

            if ($phase_info['sent_to_tarh'] == 1) {
                $phase_price = intval($phase_info->cost);
                $price = intval($item->price);
                $remaining = $phase_price - $price;

                if ($price > 0) {
                    Payment::create([
                        'price' => $price,
                        'project_id' => $phase_info->project_id,
                        'phase_id' => $phase_id,
                        'is_force' => 0,
                        'status' => 0,
                    ]);
                }

                if ($remaining > 0) {
                    Payment::create([
                        'price' => $remaining,
                        'project_id' => $phase_info->project_id,
                        'phase_id' => $phase_id,
                        'is_force' => 1,
                        'status' => 0,
                    ]);
                }

                $phase_info->update([
                    'status' => 6,
                    'sent_to_tarh' => 2,
                ]);
            }
        }

        return response()->json([
            'error' => false,
            'errorMsg' => 'دستور واریز  صادر شد و به بخش مالی ارسال شد.',
        ]);
    }
}
