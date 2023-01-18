<?php

namespace App\Http\Controllers\maliManager;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Shared\Html;
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

        $phases = Phase::query()
            ->where('project_id', $project_id)
            ->where('phase_number', '!=', 0)
            ->get();

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

//        $service_description = strip_tags($project_info['service_description']);
//        $required_outputs = strip_tags($project_info['required_outputs']);


        $service_description = new TextRun();
        Html::addHtml($service_description, strip_tags($project_info['service_description']));

        $required_outputs = new TextRun();
        Html::addHtml($required_outputs, strip_tags($project_info['required_outputs']));

        if (file_exists($user_info['Signature_img']) and !is_dir($user_info['Signature_img'])) {
            $user_img = $user_info['Signature_img'];
        } else {
            $user_img = 'placeholder/signature_placeholder.png';
        }

        if (file_exists($supervisor_info['Signature_img']) and !is_dir($supervisor_info['Signature_img'])) {
            $supervisor_img = $supervisor_info['Signature_img'];
        } else {
            $supervisor_img = 'placeholder/signature_placeholder.png';
        }

        if ($user_info['type'] == 1) {
            $TemplateProcessor = new TemplateProcessor('word_template/legal_template.docx');

            $TemplateProcessor->setValue('co_name', $user_info['co_name']);
            $TemplateProcessor->setValue('co_national_id', $user_info['co_national_id']);
            $TemplateProcessor->setValue('co_reg_number', $user_info['co_reg_number']);
            $TemplateProcessor->setValue('co_reg_date', $co_reg_date_jalali);
            $TemplateProcessor->setValue('co_post_code', $user_info['co_post_code']);
            $TemplateProcessor->setValue('co_national_id', $user_info['co_national_id']);
            $TemplateProcessor->setValue('ceo_full_name', $user_info['ceo_name'] . " " . $user_info['ceo_family']);
            $TemplateProcessor->setValue('title', $project_info['title']);
            $TemplateProcessor->setValue('contract_cost', @number_format($project_info['contract_cost']));
            $TemplateProcessor->setValue('contract_cost_', @number_format($project_info['contract_cost']));
            $TemplateProcessor->setValue('prepayment_price', @number_format($project_info['prepayment']));
            $TemplateProcessor->setValue('persian_contract_cost', $this->convertNumberToWords($project_info['contract_cost'] / 10));
            $TemplateProcessor->setValue('shaba_number', $account['shaba_number']);
            $TemplateProcessor->setValue('bank_name', $account['bank_name']);
            $TemplateProcessor->setValue('project_end_date_jalali', $project_end_date_jalali);
            $TemplateProcessor->setValue('project_start_date_jalali', $project_start_date_jalali);
            $TemplateProcessor->setValue('address', $user_info['address']);
            $TemplateProcessor->setValue('phone', $user_info['phone']);
            $TemplateProcessor->setValue('co_post_code', $user_info['co_post_code']);
            $TemplateProcessor->setComplexValue('sd', $service_description);
            $TemplateProcessor->setComplexValue('ro', $required_outputs);
            $TemplateProcessor->setImageValue("user_signature", ['path' => $user_img, 'width' => 100, 'height' => 100]);
            $TemplateProcessor->setImageValue("supervisor_signature", ['path' => $supervisor_img, 'width' => 100, 'height' => 100]);

            $TemplateProcessor->setValue('supervisor_name', $supervisor_info['name'] . " " . $supervisor_info['family']);

            if ($project_info['prepayment'] > 0) {
                $TemplateProcessor->setValue('prepayment_date', $project_start_date);
            } else {
                $TemplateProcessor->setValue('prepayment_date', '');
            }

            for ($i = 0; $i < 10; $i++) {
                if (isset($phases[$i])) {
                    $start_date = verta($phases[$i]['start_date'])->format('d/m/Y');
                    $start_date_jalali = explode(' ', $start_date)[0];

                    $end_date = verta($phases[$i]['end_date'])->format('d/m/Y');
                    $end_date_jalali = explode(' ', $end_date)[0];

                    $TemplateProcessor->setValue('phase_title_' . $phases[$i]['phase_number'], $phases[$i]['description']);
                    $TemplateProcessor->setValue('phase_start_date_' . $phases[$i]['phase_number'], $start_date_jalali);
                    $TemplateProcessor->setValue('phase_end_date_' . $phases[$i]['phase_number'], $end_date_jalali);
                    $TemplateProcessor->setValue('phase_cost_' . $phases[$i]['phase_number'], @number_format($phases[$i]['cost']));
                } else {
                    $TemplateProcessor->setValue('phase_title_' . ($i + 1), '-');
                    $TemplateProcessor->setValue('phase_start_date_' . ($i + 1), '-');
                    $TemplateProcessor->setValue('phase_end_date_' . ($i + 1), '-');
                    $TemplateProcessor->setValue('phase_cost_' . ($i + 1), '-');
                }
            }


            $file_name = time() . $user_info['co_name'] . ".docx";
        } else {
            $TemplateProcessor = new TemplateProcessor('word_template/real_template.docx');

            $TemplateProcessor->setValue('name', $user_info['name']);
            $TemplateProcessor->setValue('family', $user_info['family']);
            $TemplateProcessor->setValue('user_name', $user_info['name'] . $user_info['family']);
            $TemplateProcessor->setValue('father_name', $user_info['father_name']);
            $TemplateProcessor->setValue('national_code', $user_info['national_code']);
            $TemplateProcessor->setValue('co_reg_number', $user_info['co_reg_number']);
            $TemplateProcessor->setValue('title', $project_info['title']);
            $TemplateProcessor->setValue('contract_cost', @number_format($project_info['contract_cost']));
            $TemplateProcessor->setValue('contract_cost_', @number_format($project_info['contract_cost']));
            $TemplateProcessor->setValue('prepayment_price', @number_format($project_info['prepayment']));
            $TemplateProcessor->setValue('persian_contract_cost', $this->convertNumberToWords($project_info['contract_cost'] / 10));
            $TemplateProcessor->setValue('shaba_number', $account['shaba_number']);
            $TemplateProcessor->setValue('bank_name', $account['bank_name']);
            $TemplateProcessor->setValue('project_end_date_jalali', $project_end_date_jalali);
            $TemplateProcessor->setValue('project_start_date_jalali', $project_start_date_jalali);
            $TemplateProcessor->setValue('address', $user_info['address']);
            $TemplateProcessor->setValue('phone', $user_info['phone']);
            $TemplateProcessor->setValue('co_post_code', $user_info['co_post_code']);
            $TemplateProcessor->setComplexValue('sd', $service_description);
            $TemplateProcessor->setComplexValue('ro', $required_outputs);
            $TemplateProcessor->setImageValue("user_signature", ['path' => $user_img, 'width' => 100, 'height' => 100]);
            $TemplateProcessor->setImageValue("supervisor_signature", ['path' =>$supervisor_img, 'width' => 100, 'height' => 100]);

            $TemplateProcessor->setValue('supervisor_name', $supervisor_info['name'] . " " . $supervisor_info['family']);

            if ($project_info['prepayment'] > 0) {
                $TemplateProcessor->setValue('prepayment_date', $project_start_date);
            } else {
                $TemplateProcessor->setValue('prepayment_date', '');
            }

            for ($i = 0; $i < 10; $i++) {

                if (isset($phases[$i])) {
                    if ($phases[$i]['phase_number'] > 0) {
                        $start_date = verta($phases[$i]['start_date'])->format('d/m/Y');
                        $start_date_jalali = explode(' ', $start_date)[0];

                        $end_date = verta($phases[$i]['end_date'])->format('d/m/Y');
                        $end_date_jalali = explode(' ', $end_date)[0];

                        $TemplateProcessor->setValue('phase_title_' . ($i + 1), $phases[$i]['description']);
                        $TemplateProcessor->setValue('phase_start_date_' . ($i + 1), $start_date_jalali);
                        $TemplateProcessor->setValue('phase_end_date_' . ($i + 1), $end_date_jalali);
                        $TemplateProcessor->setValue('phase_cost_' . ($i + 1), @number_format($phases[$i]['cost']));
                    }
                } else {
                    $TemplateProcessor->setValue('phase_title_' . ($i + 1), '-');
                    $TemplateProcessor->setValue('phase_start_date_' . ($i + 1), '-');
                    $TemplateProcessor->setValue('phase_end_date_' . ($i + 1), '-');
                    $TemplateProcessor->setValue('phase_cost_' . ($i + 1), '-');
                }
            }

            $file_name = time() . $user_info['name'] . ".docx";
        }


        $TemplateProcessor->saveAs($file_name);
        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    function getProceedingWord($payment_id)
    {
        $payment = Payment::query()
            ->where('id', $payment_id)
            ->firstOrFail();

        $phases = Phase::query()
            ->where('id', $payment['phase_id'])
            ->firstOrFail();

        $project_info = Project::query()
            ->where('id', $phases['project_id'])
            ->firstOrFail();

        $phase_end_date = verta($phases->end_date)->format('Y/m/d');
        $phase_end_date_jalali = explode(' ', $phase_end_date)[0];

        $user_info = User::query()
            ->where('id', $project_info['user_id'])
            ->firstOrFail();

        $employer_info = User::query()
            ->where('id', $project_info['employer_id'])
            ->firstOrFail();

        $supervisor_info = User::query()
            ->where('id', $project_info['supervisor_id'])
            ->firstOrFail();

        if ($user_info['type'] == 1) {
            $TemplateProcessor = new TemplateProcessor('word_template/legal_proceeding.docx');

            $TemplateProcessor->setValue('phase_end_date_jalali', $phase_end_date_jalali);
            $TemplateProcessor->setValue('supervisor_name', $supervisor_info['name'] . " " . $supervisor_info['family']);
            $TemplateProcessor->setValue('co_name', $user_info['co_name']);
            $TemplateProcessor->setValue('ceo_name', $user_info['ceo_name']);
            $TemplateProcessor->setValue('phase_number', $phases['phase_number']);
            $TemplateProcessor->setValue('phase_cost', @number_format($phases['cost']));
            $TemplateProcessor->setImageValue("user_signature", ['path' => $user_info['Signature_img'], 'width' => 100, 'height' => 100]);
            $TemplateProcessor->setImageValue("user_stamp", ['path' => $user_info['stamp_img'], 'width' => 100, 'height' => 100]);
            $TemplateProcessor->setImageValue("employer_signature", ['path' => $employer_info['Signature_img'], 'width' => 100, 'height' => 100]);
            $TemplateProcessor->setImageValue("supervisor_signature", ['path' => $supervisor_info['Signature_img'], 'width' => 100, 'height' => 100]);

            $file_name = time() . $user_info['co_name'] . ".docx";
        } else {
            $TemplateProcessor = new TemplateProcessor('word_template/real_proceeding.docx');

            $TemplateProcessor->setValue('phase_end_date_jalali', $phase_end_date_jalali);
            $TemplateProcessor->setValue('supervisor_name', $supervisor_info['name'] . " " . $supervisor_info['family']);
            $TemplateProcessor->setValue('user_name', $user_info['name'] . " " . $user_info['family']);
            $TemplateProcessor->setValue('phase_number', $phases['phase_number']);
            $TemplateProcessor->setValue('phase_cost', @number_format($phases['cost']));
            $TemplateProcessor->setImageValue("user_signature", ['path' => $user_info['Signature_img'], 'width' => 100, 'height' => 100]);
            $TemplateProcessor->setImageValue("employer_signature", ['path' => $employer_info['Signature_img'], 'width' => 100, 'height' => 100]);
            $TemplateProcessor->setImageValue("supervisor_signature", ['path' => $supervisor_info['Signature_img'], 'width' => 100, 'height' => 100]);

            $file_name = time() . $user_info['name'] . ".docx";
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
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id',
                    'phases.status as f_status', 'phases.end_date as f_end_date', 'projects.status as p_status', 'reports.phases_id as ph_id', 'reports.id as report_id')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->join('reports', 'reports.phases_id', '=', 'phases.id')
                ->join('payments', 'payments.project_id', '=', 'projects.id')
                ->where('payments.is_force', 0)
                ->where('payments.status', 0)
                ->where('projects.status', 8)
                ->get();

            $projectsGroupByProject = [];
            foreach ($projects as $project) {
                $project['created_at'] = verta($project->created_at)->format('Y/m/d');
                $project['created_at_jalali'] = explode(' ', $project['created_at'])[0];

                if ($project->p_status == 8) {
                    $result = $this->check_project_phase($project->project_id);
                    $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];

                }

                $project['user_info_name'] = User::query()
                    ->where('id', $project->user_id)
                    ->first();

                if (!isset($projectsGroupByProject[$project['project_id']])) {

                    $projectsGroupByProject[$project['project_id']] = [];
                    $financial_res = $this->calculate_total_payed_reminding($project['project_id'], $project['contract_cost'], 0);
                    $projectsGroupByProject[$project['project_id']]['reminding'] = $financial_res[2];
                    $projectsGroupByProject[$project['project_id']]['total_price'] = floatval($project['contract_cost']);
                    $projectsGroupByProject[$project['project_id']]['payed'] = $financial_res[0];
                    $projectsGroupByProject[$project['project_id']]['user_id'] = $project['user_id'];
                    $projectsGroupByProject[$project['project_id']]['s_title'] = $project['s_title'];
                    $projectsGroupByProject[$project['project_id']]['p_title'] = $project['p_title'];
                    $projectsGroupByProject[$project['project_id']]['status_css'] = $project['status_css'];
                    $projectsGroupByProject[$project['project_id']]['project_id'] = $project['project_id'];
                    $projectsGroupByProject[$project['project_id']]['subject'] = $project['subject'];
                    $projectsGroupByProject[$project['project_id']]['project_unique_code'] = $project['project_unique_code'];
                    $projectsGroupByProject[$project['project_id']]['f_end_date'] = $project['f_end_date'];
                    $projectsGroupByProject[$project['project_id']]['employer_id'] = $project['employer_id'];
                    $projectsGroupByProject[$project['project_id']]['phase_number'] = $project['phase_number'];
                    $projectsGroupByProject[$project['project_id']]['supervisor'] = $project->supervisor->name . ' ' . $project->supervisor->family;
                    if ($project['user_info_name']->type == 1) {
                        $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->co_name;
                    } else {
                        $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->name . ' ' . $project['user_info_name']->family;
                    }
                }
                // else {
                //     $projectsGroupByProject[$project['project_id']]['total_price'] += intval($project['contract_cost']);
                // }
            }

            $data = [];
            array_push($data, [
                'project' => 'پروژه',
                'total_price' => 'قیمت کل پروژه',
                'payed' => 'جمع واریزی',
                'reminding' => 'باقیمانده',
                'employer' => 'کارفرما',
                'supervisor' => 'ناظر',
                'end_data' => 'تاریخ پایان',
            ]);

            foreach ($projectsGroupByProject as $payment) {

                $date = date('Y-m-d', strtotime($payment['f_end_date']));
                $jalali_end_date = $this->convertDateToJalali($date);

                $employer = User::query()
                    ->where('id', $payment['employer_id'])
                    ->first();

                $payment['employer'] = $employer;

                array_push($data, [
                    'project' => $payment['p_title'],
                    'total_price' => @number_format($payment['total_price']),
                    'payed' => @number_format($payment['payed']),
                    'reminding' => @number_format($payment['reminding']),
                    'employer' => $employer['name'] . " " . $employer['family'],
                    'supervisor' => $payment['supervisor'],
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

    function convertDateToJalali($date)
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

    function get_debt_projects($user_id)
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

    function get_project_total_sum($user_id)
    {
        $project_total_sum = Project::query()
            ->where('user_id', $user_id)
            ->sum("contract_cost");
        return $project_total_sum;
    }

    function check_project_phase($project_id)
    {
        $phase = Phase::query()
            ->where('project_id', $project_id)
            ->where('status', '!=', 7)
            ->first();

        if ($phase) {
            $status_info = phases_status::query()
                ->where('id', $phase->status)
                ->first();

            $phase_number = $phase['phase_number'];
            if ($phase_number == 0) {
                $phase_number = 'پیش پرداخت';
            } else {
                $phase_number = 'فاز ' . $phase_number;
            }
        } else {
            $status_info = phases_status::query()
                ->where('id', 7)
                ->first();

            $phase_number = 'فاز آخر';
        }

        $s_title = $status_info->title;
        return [$s_title, $phase_number];
    }

    function calculate_total_payed_reminding($project_id, $total, $is_force)
    {
        $payed = Payment::query()
            ->where('project_id', $project_id)
            ->where('status', 1)
            ->where('is_force', $is_force)
            ->sum('price');

        $reminding = floatval($total) - $payed;

        return [$payed, $total, $reminding];
    }
}
