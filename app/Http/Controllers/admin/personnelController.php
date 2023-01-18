<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class exportController extends Controller
{
    //get financial excel
    function getPaymentExcel(Request $request)
    {

        $type = $request->type;

        if ($type == 'payment') {
            $payments = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'phases.status as f_status', 'projects.status as p_status')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->where('phases.status', 5)
                ->where('projects.status', 8)
                ->get();

            $data = [];
            array_push($data, [
                'project' => 'پروژه',
                'phase' => 'فاز',
                'cost' => 'مبلغ',
                'employer' => 'کارفرما',
                'supervisor' => 'ناظر',
                'end_data' => 'تاریخ پایان',
            ]);

            foreach ($payments as $payment) {

                $date = date('Y-m-d', strtotime($payment['f_end_date']));
                $jalali_end_date = $this->convertDateToJalali($date);

                $employer = User::query()
                    ->where('id', $payment['employer_id'])
                    ->first();

                $payment['employer'] = $employer;

                $supervisor = User::query()
                    ->where('id', $payment->supervisor_id)
                    ->first();

                $payment['supervisor'] = $supervisor;
                $payment['id'] = $payment->id;

                array_push($data, [
                    'project' => $payment['p_title'],
                    'phase' => $payment['phase_number'] == 0 ? "پیش پرداخت" : $payment['phase_number'],
                    'cost' => @number_format($payment['cost']),
                    'employer' => $employer['name'] . " " . $employer['family'],
                    'supervisor' => $supervisor['name'] . " " . $supervisor['family'],
                    'end_data' => $jalali_end_date
                ]);
            }

            $excelName = "جدول پرداخت ----" . verta(date('Y-m-d H:i'))->formatWord('d F ساعت h و i') . '.xls';

        } elseif ($type == 'force_payment') {
            $payments = Payment::query()
                ->select('*', 'payments.id as payment_id', 'phases.end_date as f_end_date', 'phases.status as f_status', 'projects.title as p_title', 'projects.end_date as p_end_date',
                    'projects.status as p_status', 'payments.status as pa_status')
                ->join('phases', 'phases.id', '=', 'payments.phase_id')
                ->join('projects', 'projects.id', '=', 'payments.project_id')
                ->where('payments.is_force', 1)
                ->where('payments.status', 0)
                ->orderByDesc('projects.created_at')
                ->get();

            $data = [];
            array_push($data, [
                'project' => 'پروژه',
                'phase' => 'فاز',
                'cost' => 'مبلغ',
                'employer' => 'کارفرما',
                'supervisor' => 'ناظر',
                'end_data' => 'تاریخ پایان',
            ]);

            foreach ($payments as $payment) {

                $date = date('Y-m-d', strtotime($payment['f_end_date']));
                $jalali_end_date = $this->convertDateToJalali($date);

                $employer = User::query()
                    ->where('id', $payment['employer_id'])
                    ->first();

                $payment['employer'] = $employer;

                $supervisor = User::query()
                    ->where('id', $payment->supervisor_id)
                    ->first();

                $payment['supervisor'] = $supervisor;
                $payment['id'] = $payment->id;

                array_push($data, [
                    'project' => $payment['p_title'],
                    'phase' => $payment['phase_number'] == 0 ? "پیش پرداخت" : $payment['phase_number'],
                    'cost' => @number_format($payment['price']),
                    'employer' => $employer['name'] . " " . $employer['family'],
                    'supervisor' => $supervisor['name'] . " " . $supervisor['family'],
                    'end_data' => $jalali_end_date
                ]);
            }

            $excelName = "جدول پرداخت فوری ----" . verta(date('Y-m-d H:i'))->formatWord('d F ساعت h و i') . '.xls';
        }

        // headers for download
        $this->ExportExcel($data, $excelName, $type);
    }

    function ExportExcel($data, $excelName, $type)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()
                ->getDefaultColumnDimension()
                ->setWidth(25);

            if ($type == 'payment') {
                for ($i = 1; $i <= count($data); $i++) {
                    $spreadSheet->getActiveSheet()->getStyle('C' . $i)
                        ->getAlignment()
                        ->setHorizontal('right');
                }
            }

            $spreadSheet->getActiveSheet()->fromArray($data);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment;filename=' . $excelName);
            header('Cache-Control: max-age=0');

            $Excel_writer->save('php://output');
            ob_clean();

            exit();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }
}
