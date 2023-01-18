<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bank;
use App\Models\LetterAccess;
use App\Models\Manager;
use App\Models\Role;
use App\Models\User;
use App\Rules\nationalCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class UserController extends Controller
{
    function index()
    {
        $users = User::query()
            ->select('*', 'users.id as user_id')
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->join('role', 'role.id', 'user_role.roles')
            ->latest('users.created_at')
            ->paginate(10);

        foreach ($users as $user) {
            $user['created_at_jalali'] = verta($user->created_at)->format('d/%B/Y');
        }

        return view('admin.users.index', ['users' => $users]);
    }

    function view(User $user)
    {
        $accounts = Account::query()->where('user_id', $user->id)->get();
        $created_at = verta($user->birth_date)->format('Y/m/d');
        $created_at_jalali = explode(' ', $created_at)[0];

        $other_users = User::query()
            ->where('id', '!=', $user->id)
            ->get();

        foreach ($accounts as $account) {
            $account['bank_info'] = Bank::query()
                ->where('id', $account->bank)
                ->first();
        }

        foreach ($other_users as $other_user) {
            $other_user['checked'] = LetterAccess::query()
                ->where('user_id', $user->id)
                ->where('contact', $other_user->id)
                ->count();
        }

        return view('admin.users.view', ['other_users' => $other_users, 'user' => $user, 'created_at_jalali' => $created_at_jalali, 'accounts' => $accounts]);
    }

    function legal_view(User $user)
    {
        $accounts = Account::query()->where('user_id', $user->id)->get();
        $managers = Manager::query()->where('user_id', $user->id)->get();
        $created_at = verta($user->co_reg_date)->format('Y/m/d');
        $created_at_jalali = explode(' ', $created_at)[0];

        $other_users = User::query()
            ->where('id', '!=', $user->id)
            ->get();

        foreach ($accounts as $account) {
            $account['bank_info'] = Bank::query()
                ->where('id', $account->bank)
                ->first();
        }
        foreach ($other_users as $other_user) {
            $other_user['checked'] = LetterAccess::query()
                ->where('user_id', $user->id)
                ->where('contact', $other_user->id)
                ->count();
        }

        return view('admin.users.legal_view', ['other_users' => $other_users,'user' => $user, 'created_at_jalali' => $created_at_jalali, 'accounts' => $accounts, 'managers' => $managers]);
    }

    function isActive(Request $request)
    {

        $user = User::query()
            ->where('id', $request->user_id)
            ->firstOrFail();

        $user->active = 1;
        $user->password = Hash::make($user->mobile);

        if ($user->type == 0) {
            $user->username = $user->national_code;
            $message = 'با سلام کاربر گرامی هم اکنون شناسه کاربری شما در سامانه ی نما فعال گردید. شما میتوانید با شناسه' . '  ' .  $user->national_code . '  ' . 'و رمز' . '  ' .  $user->mobile . '  ' .  'وارد سامانه بشوید.' . '  ' . 'https://snama.info';
        } elseif ($user->type == 1) {
            $user->username = $user->ceo_national_code;
            $message = 'با سلام مدیرکل محترم ، هم اکنون شناسه کاربری شما در سامانه ی نما فعال گردید. شما میتوانید با شناسه' . '  ' .  $user->ceo_national_code . '  ' . 'و رمز' . '  ' .  $user->mobile . '  ' .  'وارد سامانه بشوید.' . '  ' . 'https://snama.info';
        }

        $user->save();

        sms($user->mobile, $message);

        alert()->success('کاربر با موفقیت فعال گردید', 'با تشکر')->autoclose(9000);

        return redirect()->route('admin_user_index');
    }

    function notActive(User $user, Request $request)
    {
        $message = $request->message;
        $user->active = 0;
        $user->save();
        $status = sms($user->mobile, $message);
//        return "true";
//        return redirect()->route('admin_user_index');
        return response()->json([
            'error' => false,
            'errorMsg' => 'عدم تایید کاربر اعمال شد'
        ]);
    }

    function add()
    {
        return view('admin.users.add');
    }

    function create(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
            'family' => 'required|string',
            'mobile' => 'required|string',
            'roles' => 'required',
            'unique_code' => 'required',
            'national_code' => [new nationalCode, 'unique:users,national_code'],
            'Signature_img' => 'required|max:1024|mimes:png,jpg,jpeg',
//            'stamp_img' => 'required|max:1024|mimes:png,jpg,jpeg',
            'image' => 'required|max:1024|mimes:png,jpg,jpeg',
            'national_code_img' => 'required|max:1024|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            alert()->error($validator->errors()->first());
            return back()->withInput()->withErrors($validator->errors());
            //boro @error ha ro bezar va old ham bezar
        }

        $file = $request->file('image');
        $file_ext = $file->getClientOriginalExtension();
        $file_name = time() . '.' . $file_ext;
        $image = $file->move('images', $file_name);

        $file_Signature = $request->file('Signature_img');
        $file_Signature_ext = $file_Signature->getClientOriginalExtension();
        $file_Signature_name = time() . '.' . $file_Signature_ext;
        $image_Signature = $file_Signature->move('images/digital_sign', $file_Signature_name);

//        $file_stamp_img = $request->file('stamp_img');
//        $file_stamp_ext = $file_stamp_img->getClientOriginalExtension();
//        $file_stamp_name = time() . '.' . $file_stamp_ext;
//        $image_stamp_img = $file_stamp_img->move('images/stamp', $file_stamp_name);

        $file_national_code_img = $request->file('national_code_img');
        $file_national_code_img_ext = $file_national_code_img->getClientOriginalExtension();
        $file_national_code_img_name = time() . '.' . $file_national_code_img_ext;
        $image_national_code_img = $file_national_code_img->move('images/national_cards', $file_national_code_img_name);

        $user = new User();
        $user->name = $request->name;
        $user->family = $request->family;
        $user->mobile = $request->mobile;
        $user->national_code = $request->national_code;
        $user->unique_code = $request->unique_code;
        $user->Signature_img = $image_Signature;
//        $user->stamp_img = $image_stamp_img;
        $user->national_code_img = $image_national_code_img;
        $user->image = $image;
        $user->save();

        $roles = $request->roles;

        foreach ($roles as $role_id) {
            $role = new Role();
            $role->user_id = $user->id;
            $role->roles = $role_id;
            $role->save();
        }
        alert()->success('کاربر با موفقیت ثبت شد', 'با تشکر')->autoclose(9000);
        return redirect()->route('admin_user_index');
    }

    function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }

    function getExcel(Request $request)
    {
        $type = $request->type;

        if ($type == 'users') {
            $users = User::query()
                ->select('*', 'cities.name as city_name', 'provinces.name as province_name')
                ->leftJoin('cities', 'cities.id', '=', 'users.city')
                ->leftJoin('provinces', 'provinces.id', '=', 'users.province')
                ->get();

            $data = [];
            array_push($data, [
                'name' => 'نام',
                'family' => 'نام خانوادگی',
                'national_code' => 'کدملی',
                'mobile' => 'موبایل',
                'birth_date' => 'تاریخ تولد',
            ]);

            foreach ($users as $user) {
                $date = date('Y-m-d', strtotime($user['birth_date']));

                array_push($data, [
                    'name' => $user['name'],
                    'family' => $user['family'],
                    'national_code' => $user['national_code'],
                    'mobile' => $user['mobile'],
                    'birth_date' => $this->convertDateToJalali($date)
                ]);
            }

            $excelName = 'users report---' . date('Y-m-d H-i') . '.xls';
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

            if ($type == 'users') {
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

    function delete(Request $request)
    {
        $user_id = $request->user_id;
        User::query()
            ->where('id', $user_id)
            ->firstOrFail()->delete();

        alert()->success('کاربر با موفقیت حذف شد', '')->autoclose(9000);
        return back();
    }

    function saveLetterAccess(Request $request)
    {
        $input = $request->all();

        $user_id = $input['user_id'];
        $users = $input['users'];

        LetterAccess::query()
            ->where('user_id', $user_id)
            ->delete();

        foreach ($users as $user) {
            LetterAccess::create([
                'user_id' => $user_id,
                'contact' => $user,
            ]);
        }

        alert()->success('دسترسی نامه برای این کاربر با موفقیت ذخیره شد.');
        return back();
    }
}
