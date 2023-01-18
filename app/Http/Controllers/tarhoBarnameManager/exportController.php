<?php

namespace App\Http\Controllers\tarhoBarnameManager;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpWord\TemplateProcessor;

class exportController extends Controller
{
    private $words = [
        [
            "",
            "یک",
            "دو",
            "سه",
            "چهار",
            "پنج",
            "شش",
            "هفت",
            "هشت",
            "نه"
        ],
        [
            "ده",
            "یازده",
            "دوازده",
            "سیزده",
            "چهارده",
            "پانزده",
            "شانزده",
            "هفده",
            "هجده",
            "نوزده",
            "بیست"
        ],
        [
            "",
            "",
            "بیست",
            "سی",
            "چهل",
            "پنجاه",
            "شصت",
            "هفتاد",
            "هشتاد",
            "نود"
        ],
        [
            "",
            "یکصد",
            "دویست",
            "سیصد",
            "چهارصد",
            "پانصد",
            "ششصد",
            "هفتصد",
            "هشتصد",
            "نهصد"
        ],
        [
            '',
            " هزار ",
            " میلیون ",
            " میلیارد ",
            " بیلیون ",
            " بیلیارد ",
            " تریلیون ",
            " تریلیارد ",
            " کوآدریلیون ",
            " کادریلیارد ",
            " کوینتیلیون ",
            " کوانتینیارد ",
            " سکستیلیون ",
            " سکستیلیارد ",
            " سپتیلیون ",
            " سپتیلیارد ",
            " اکتیلیون ",
            " اکتیلیارد ",
            " نانیلیون ",
            " نانیلیارد ",
            " دسیلیون "
        ]
    ];
    private $splitter = " و ";

    //get contracts word
    function getContractWord($project_id)
    {
        $project_info = Project::query()
            ->where('id', $project_id)
            ->firstOrFail();

        $project_start_date = verta($project_info->start_date)->format('Y/m/d');
        $project_start_date_jalali = explode(' ', $project_start_date)[0];

        $project_end_date = verta($project_info->end_date)->format('Y/m/d');
        $project_end_date_jalali = explode(' ', $project_end_date)[0];

        $co_reg_date = verta($project_info->co_reg_date)->format('Y/m/d');
        $co_reg_date_jalali = explode(' ', $co_reg_date)[0];

        $user_info = User::query()
            ->where('id', $project_info['user_id'])
            ->firstOrFail();

        $supervisor_info = User::query()
            ->where('id', $project_info['supervisor_id'])
            ->firstOrFail();

        $account = Account::query()
            ->join('banks', 'banks.id', '=', 'accounts.bank')
            ->where('user_id', $project_info['user_id'])
            ->firstOrFail();

        if ($user_info['type'] == 1) {
            $TemplateProcessor = new TemplateProcessor('word_template/hoghooghi_template.docx');

            $TemplateProcessor->setValue('co_name', $user_info['co_name']);
            $TemplateProcessor->setValue('co_national_id', $user_info['co_national_id']);
            $TemplateProcessor->setValue('co_reg_number', $user_info['co_reg_number']);
            $TemplateProcessor->setValue('co_reg_date', $co_reg_date_jalali);
            $TemplateProcessor->setValue('co_post_code', $user_info['co_post_code']);
            $TemplateProcessor->setValue('co_national_id', $user_info['co_national_id']);
            $TemplateProcessor->setValue('ceo_full_name', $user_info['ceo_name'] . " " . $user_info['ceo_family']);
            $TemplateProcessor->setValue('subject', $project_info['subject']);
            $TemplateProcessor->setValue('contract_cost', $project_info['contract_cost']);
            $TemplateProcessor->setValue('persian_contract_cost', $this->convertNumberToWords($project_info['contract_cost'] / 10));
            $TemplateProcessor->setValue('shaba_number', $account['shaba_number']);
            $TemplateProcessor->setValue('bank_name', $account['bank_name']);
            $TemplateProcessor->setValue('project_end_date_jalali', $project_end_date_jalali);
            $TemplateProcessor->setValue('project_start_date_jalali', $project_start_date_jalali);
            $TemplateProcessor->setValue('address', $user_info['address']);
            $TemplateProcessor->setValue('phone', $user_info['phone']);
            $TemplateProcessor->setValue('co_post_code', $user_info['co_post_code']);
            $TemplateProcessor->setValue('service_description', strip_tags($project_info['service_description']));
            $TemplateProcessor->setValue('required_outputs', strip_tags($project_info['required_outputs']));
            $TemplateProcessor->setValue('supervisor_name', $supervisor_info['name'] . " " . $supervisor_info['family']);

            $file_name = time() . $user_info['co_name'] . ".docx";
        } else {
            $TemplateProcessor = new TemplateProcessor('word_template/haghighi.docx');

            $TemplateProcessor->setValue('name', $user_info['name']);
            $TemplateProcessor->setValue('family', $user_info['family']);
            $TemplateProcessor->setValue('national_code', $user_info['national_code']);
            $TemplateProcessor->setValue('subject', $project_info['subject']);
            $TemplateProcessor->setValue('contract_cost', $project_info['contract_cost']);
            $TemplateProcessor->setValue('persian_contract_cost', $this->convertNumberToWords($project_info['contract_cost'] / 10));
            $TemplateProcessor->setValue('shaba_number', $account['shaba_number']);
            $TemplateProcessor->setValue('project_end_date_jalali', $project_end_date_jalali);
            $TemplateProcessor->setValue('project_start_date_jalali', $project_start_date_jalali);
            $TemplateProcessor->setValue('address', $user_info['address']);
            $TemplateProcessor->setValue('phone', $user_info['phone']);
            $TemplateProcessor->setValue('co_post_code', $user_info['co_post_code']);
            $TemplateProcessor->setValue('service_description', strip_tags($project_info['service_description']));
            $TemplateProcessor->setValue('required_outputs', strip_tags($project_info['required_outputs']));

            $file_name = time() . $user_info['family'] . ".docx";
        }


        $TemplateProcessor->saveAs($file_name);
        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    function convertNumberToWords($input)
    {
        $zero = "صفر";
        if ($input == 0) {
            return $zero;
        }
        if (strlen($input) > 66) {
            return "خارج از محدوده";
        }
        //Split to sections
        $splittedNumber = $this->prepareNumber($input);
        $result = [];
        $splitLength = count($splittedNumber);
        for ($i = 0; $i < $splitLength; $i++) {
            $sectionTitle = $this->words[4][$splitLength - ($i + 1)];
            $converted = $this->threeNumbersToLetter($splittedNumber[$i]);
            if ($converted !== "") {
                array_push($result, $converted . $sectionTitle);
            }
        }
        return join($this->splitter, $result);
    }

    function prepareNumber($num)
    {
        if (gettype($num) == "integer" || gettype($num) == "double") {
            $num = (string)$num;
        }
        $length = strlen($num) % 3;
        if ($length == 1) {
            $num = "00" . $num;
        } else if ($length == 2) {
            $num = "0" . $num;
        }
        return str_split($num, 3);
    }

    function threeNumbersToLetter($num)
    {
        if ((int)preg_replace('/\D/', '', $num) == 0) {
            return "";
        }
        $parsedInt = (int)preg_replace('/\D/', '', $num);
        if ($parsedInt < 10) {
            return $this->words[0][$parsedInt];
        }
        if ($parsedInt <= 20) {
            return $this->words[1][$parsedInt - 10];
        }
        if ($parsedInt < 100) {
            $one = $parsedInt % 10;
            $ten = ($parsedInt - $one) / 10;
            if ($one > 0) {
                return $this->words[2][$ten] . $this->splitter . $this->words[0][$one];
            }
            return $this->words[2][$ten];
        }
        $one = $parsedInt % 10;
        $hundreds = ($parsedInt - $parsedInt % 100) / 100;
        $ten = ($parsedInt - (($hundreds * 100) + $one)) / 10;
        $out = [$this->words[3][$hundreds]];
        $secondPart = (($ten * 10) + $one);
        if ($secondPart > 0) {
            if ($secondPart < 10) {
                array_push($out, $this->words[0][$secondPart]);
            } else if ($secondPart <= 20) {
                array_push($out, $this->words[1][$secondPart - 10]);
            } else {
                array_push($out, $this->words[2][$ten]);
                if ($one > 0) {
                    array_push($out, $this->words[0][$one]);
                }
            }
        }
        return join($this->splitter, $out);
    }

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

    function getRemindingExcel(Request $request)
    {
        $type = $request->type;

        $user_info = User::query()
            ->select("*", "role.title as role_title", "users.id as user_id")
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->orderBy('user_role.roles')
            ->get();

        $data = [];
        array_push($data, [
            'user' => 'مجری',
            'total_project' => 'تعداد کل پروژه',
            'inprogress_projects' => 'تعداد پروژه های جاری',
            'finished_projects' => 'تعداد پروژه های تسویه شده',
            'contract_cost' => 'تعهد بابت مجموع قراردادها (ریال)',
            'reminding' => 'بدهی بابت مجموع قراردادها (ریال)',
        ]);

        foreach ($user_info as $user) {
            $user['pro_count'] = Project::query()
                ->where('user_id', $user->user_id)
                ->count();

            $user['current_projects'] = Project::query()
                ->where('user_id', $user->user_id)
                ->where('status', 8)
                ->count();

            $user['settled_projects'] = Project::query()
                ->where('user_id', $user->user_id)
                ->where('status', 9)
                ->count();

            $user['payment_total'] = $this->get_debt_projects($user->user_id);
            $user['total_price'] = $this->get_project_total_sum($user->user_id);

            array_push($data, [
                'user' => $user['type'] == 0 ?  $user['name'] . " " . $user['family'] : $user['co_name'],
                'total_project' => $user['pro_count'],
                'inprogress_projects' =>  $user['current_projects'] ,
                'finished_projects' => intval($user['settled_projects']),
                'contract_cost' => @number_format($user['payment_total']),
                'reminding' => @number_format($user['total_price']),
            ]);
        }

        $excelName = "جدول بدهی ها ----" . verta(date('Y-m-d H:i'))->formatWord('d F ساعت h و i') . '.xls';

        // headers for download
        $this->ExportExcel($data, $excelName, $type);
    }

    public function get_debt_projects($user_id)
    {
        $project_total_sum = Project::query()
            ->where('user_id', $user_id)
            ->sum("contract_cost");

        $project_paid_sum = Project::query()
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('payments', 'payments.phase_id', '=', 'phases.id')
            ->where('user_id', $user_id)
            ->where('payments.status', 1)
            ->sum("payments.price");

        $remaining = $project_total_sum - $project_paid_sum;

        return $remaining;
    }

    public function get_project_total_sum($user_id)
    {
        $project_total_sum = Project::query()
            ->where('user_id', $user_id)
            ->sum("contract_cost");
        return $project_total_sum;
    }
}
