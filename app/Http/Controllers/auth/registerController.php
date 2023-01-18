<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Manager;
use App\Models\Role;
use App\Models\User;
use App\Rules\nationalCode;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class registerController extends Controller
{
    function index()
    {
        $banks = Bank::query()
            ->get();
        return view('auth.register',['banks' => $banks]);
    }

    function register(Request $request)
    {

        $input = $request->all();

        if (intval($input['type']) == 0) {
            $validator = Validator::make($input, [
                'type' => 'required|integer',
                'name' => 'required|string',
                'family' => 'required|string',
                'father_name' => 'required|string',
                'national_code' => [new nationalCode, 'unique:users,national_code'],
                'id_code' => 'required|string',
                'birth_date' => 'required|string',
                'national_code_img' => 'required|max:1024|mimes:png,jpg,jpeg',
                'Signature_img' => 'required|max:1024|mimes:png,jpg,jpeg',
                'evidence' => 'required|string',
                'field_study' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|string',
                'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
                'social_no' => 'required|string',
                'bank' => 'required|string',
                'account_number' => 'required|string',
                'shaba_number' => 'required|string',
                'rule_checkbox' => 'required',
                'image' => 'required|max:1024|mimes:png,jpg,jpeg',
            ]);

            if ($input['rule_checkbox'] != 'on') {
                $validator->errors()->add('rule_checkbox', 'پذیرفتن قوانین الزامی است.');
            }

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ]);
            }

            $file = $request->file('image');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $file_ext;
            $image = $file->move('images', $file_name);

            $file = $request->file('national_code_img');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $file_ext;
            $national_code_img = $file->move('images/national_cards', $file_name);

            $file = $request->file('Signature_img');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $file_ext;
            $Signature_img = $file->move('images/digital_sign', $file_name);


            $user = new User();
            $user->type = $request->type;
            $user->name = $request->name;
            $user->family = $request->family;
            $user->national_code = $request->national_code;
            $user->national_code_img = $national_code_img;
            $user->Signature_img = $Signature_img;
            $user->image = $image;
            $user->id_code = $request->id_code;
            $user->sex = $request->sex;
            $user->birth_date = $this->convertDateToGregorian($request->birth_date);
            $user->evidence = $request->evidence;
            $user->field_study = $request->field_study;
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->mobile = $request->mobile;
            $user->social_no = $request->social_no;
            $user->save();

            $account = new Account();
            $account->user_id = $user->id;
            $account->bank = $request->bank;
            $account->account_number = $request->account_number;
            $account->shaba_number = $request->shaba_number;
            $account->save();

            $role = new Role();
            $role->user_id = $user->id;
            $role->roles = 8;
            $role->save();


            if($request->sex == 1){
                $message = nl2br(' جناب آقای '.' '. $request->name .' '.$request->family .' '.'ثبت نام شما با موفقیت در سامانه نما انجام شد. نام کاربری و رمز عبور شما پس از تایید مدیر سامانه به شما اعلام میگردد.'.' '.'http://snama.info');

                sms($request->mobile, $message);

                $admin_mobile = '09128383357';
                $admin_message = nl2br(' جناب آقای '.' '. $request->name .' '.$request->family .' '.' با موفقیت در سامانه نما ثبت نام کرد. لطفا برای تایید به پنل مدیریت مراجعه فرمایید.'.' '.'http://snama.info');

                sms($admin_mobile, $admin_message);

                sms('09127132500', $admin_message);
            }
            else{
                $message = nl2br(' سرکار خانم '.' '. $request->name .' '.$request->family .' '.'ثبت نام شما با موفقیت در سامانه نما انجام شد. نام کاربری و رمز عبور شما پس از تایید مدیر سامانه به شما اعلام میگردد.'.' '.'http://snama.info');

                sms($request->mobile, $message);

                $admin_mobile = '09128383357';
                $admin_message = nl2br(' سرکار خانم '.' '. $request->name .' '.$request->family .' '.' با موفقیت در سامانه نما ثبت نام کرد. لطفا برای تایید به پنل مدیریت مراجعه فرمایید.'.' '.'http://snama.info');

                sms($admin_mobile, $admin_message);

                sms('09127132500', $admin_message);
            }



            return response()->json([
                'error' => false,
                'message' => 'ثبت نام شما با موفقیت انجام شد، لطفا منتظر بمانید...',
                'errors' => []
            ]);
        } else {
            $validator = Validator::make($input, [
                'type' => 'required|integer',
                'co_name' => 'required|string',
                'co_reg_number' => 'required|string',
                'co_national_id' => 'required|string',
                'co_reg_date' => 'required|string',
                'co_address' => 'required|string',
                'co_statute_image' => 'required|max:1024|mimes:png,jpg,jpeg,pdf',
                'Signature_img' => 'required|max:1024|mimes:png,jpg,jpeg',
                'stamp_img' => 'required|max:1024|mimes:png,jpg,jpeg',
                'image' => 'required|max:1024|mimes:png,jpg,jpeg',

                'ceo_name' => 'required|string',
                'ceo_family' => 'required|string',
                'ceo_national_code' => [new nationalCode, 'unique:users,ceo_national_code'],
                'ceo_id_code' => 'required|string',
                'ceo_mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
                'manager_name' => 'required|string',
                'manager_family' => 'required|string',
                // 'manager_national_code' => [new nationalCode, 'unique:managers,manager_national_code'],
                'manager_id_code' => 'required|string',
                'legal_bank' => 'required|string',
                'co_account_number' => 'required|string',
                'co_shaba_number' => 'required|string',

                'co_rule_checkbox' => 'required'
            ]);

            if ($input['co_rule_checkbox'] != 'on') {
                $validator->errors()->add('co_rule_checkbox', 'پذیرفتن قوانین الزامی است.');
            }

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ]);
            }

            $file = $request->file('image');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $file_ext;
            $image = $file->move('images', $file_name);

            $file = $request->file('co_statute_image');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $file_ext;
            $co_statute_image = $file->move('images/co_statute_image', $file_name);

            $file = $request->file('Signature_img');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $file_ext;
            $Signature_img = $file->move('images/digital_sign', $file_name);

            $file = $request->file('stamp_img');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = time() . '.' . $file_ext;
            $stamp_img = $file->move('images/stamp', $file_name);

            $user = new User();
            $user->type = $request->type;
            $user->co_name = $request->co_name;
            $user->co_reg_number = $request->co_reg_number;
            $user->address = $request->co_address;
            $user->co_national_id = $request->co_national_id;
            $user->co_statute_image = $co_statute_image;
            $user->Signature_img = $Signature_img;
            $user->stamp_img = $stamp_img;
            $user->image = $image;
            $user->ceo_name = $request->ceo_name;
            $user->ceo_family = $request->ceo_family;
            $user->co_reg_date = $this->convertDateToGregorian($request->co_reg_date);
            $user->ceo_national_code = $request->ceo_national_code;
            $user->ceo_id_code = $request->ceo_id_code;
            $user->ceo_name = $request->ceo_name;
            $user->ceo_family = $request->ceo_family;
            $user->ceo_national_code = $request->ceo_national_code;
            $user->ceo_id_code = $request->ceo_id_code;
            $user->phone = $request->co_phone;
            $user->co_post_code = $request->co_post_code;
            $user->mobile = $request->ceo_mobile;
            $user->save();

            $account = new Account();
            $account->user_id = $user->id;
            $account->bank = $request->legal_bank;
            $account->account_number = $request->co_account_number;
            $account->shaba_number = $request->co_shaba_number;
            $account->save();

            $manager = new Manager();
            $manager->user_id = $user->id;
            $manager->manager_name =$request->manager_name;
            $manager->manager_family = $request->manager_family;
            $manager->manager_national_code =$request->manager_national_code;
            $manager->manager_id_code = $request->manager_id_code;
            $manager->save();


            $role = new Role();
            $role->user_id = $user->id;
            $role->roles = 7;
            $role->save();

            $message = nl2br('مدیرعامل محترم شرکت'.' '. $request->co_name . ' ' .'ثبت نام شما با موفقیت در سامانه نما انجام شد. نام کاربری و رمز عبور شما پس از تایید مدیر سامانه به شما اعلام میگردد.'.' '.'http://snama.info');
            sms($request->ceo_mobile, $message);

            $admin_mobile = '09128383357';
            $admin_message = nl2br(' مدیرعامل محترم شرکت '.' '. $request->co_name .' با موفقیت در سامانه نما ثبت نام کرد. لطفا برای تایید به پنل مدیریت مراجعه فرمایید.'.' '.'http://snama.info');

            sms($admin_mobile, $admin_message);

            sms('09127132500', $admin_message);

            return response()->json([
                'error' => false,
                'message' => 'ثبت نام شما با موفقیت انجام شد، لطفا منتظر بمانید...',
                'errors' => []
            ]);
        }    }

    function convertDateToGregorian($date)
    {
        $date = explode('/', $date);
        $date = Verta::getGregorian($this->convertDigitsToEnglish($date[0]), $this->convertDigitsToEnglish($date[1]), $this->convertDigitsToEnglish($date[2]));
        return join('-', $date);
    }

    public function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }

    function convertDigitsToEnglish($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
}


