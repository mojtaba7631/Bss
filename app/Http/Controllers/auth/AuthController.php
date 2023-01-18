<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\nationalCode;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register_real_step1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'family' => 'required',
            'national_code' => [new nationalCode, 'unique:users,national_code'],
            'id_code' => 'required',
            'birth_date' => 'required',
            'national_code_img' => 'required|max:1024',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'ورودی ها درست وارد نشده اند.',
                'errors' => $validator->errors()
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'اطلاعات با موفقیت ثبت شد.',
            ]);
        }
    }

    public function register_real_step2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evidence' => 'required',
            'field_study' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
            'social_no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'ورودی ها درست وارد نشده اند.',
                'errors' => $validator->errors()
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'اطلاعات با موفقیت ثبت شد.',
            ]);
        }
    }

    public function register_real_step3(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'family' => 'required',
            'national_code' => [new nationalCode, 'unique:users,national_code'],
            'id_code' => 'required',
            'birth_date' => 'required',
            'national_code_img' => 'required|max:1024|mimes:png,jpg,jpeg',
            'evidence' => 'required',
            'field_study' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
            'social_no' => 'required',
            'bank' => 'required',
            'account_number' => 'required',
            'shaba_number' => 'required',
            'checkbox' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'ورودی ها درست وارد نشده اند.',
                'errors' => $validator->errors()
            ]);
        } else {
            if ($request->checkbox != 'true') {
                return response()->json([
                    'status' => false,
                    'message' => 'ورودی ها درست وارد نشده اند.',
                    'errors' => ['checkbox' => ['قوانین را مطالعه و قبول نمایید']]
                ]);
            }

//            if ($request->has('national_code_img')) {
//                $user = new User();
//                $validation = Validator::make($request->all(), [
//                    'national_code_img' => 'required|max:1024',
//                ]);
//
//                if ($validation->fails()) {
//                    alert()->error($validation->errors()->first(), 'خطا');
//                    return back()->withErrors($validation->errors())->withInput();
//                }
//
//
//                $file = $request->file('national_code_img');
//                $file_ext = $file->getClientOriginalExtension();
//                $file_name = time().'.'.$file_ext;
//                $path_cover = $file->move('images',$file_name);
//
////                $file_name = time() . $request->file('national_code_img')->getClientOriginalName();
////                $path_cover = $request->file('national_code_img')->move('images', $file_name);
////                $user->national_code_img = $path_cover;
//            }

            $user = new User();
            $user->name = $request->name;
            $user->family = $request->family;
            $user->national_code = $request->national_code;
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

            return response()->json([
                'status' => true,
                'message' => 'اطلاعات با موفقیت ثبت شد.',
            ]);
        }
        return redirect()->route('main');
    }

    public function register_real_step4(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'co_name' => 'required',
            'co_reg_number' => 'required',
            'co_reg_date' => 'required',
            'co_national_id' => 'required',
            'co_statute_image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'ورودی ها درست وارد نشده اند.',
                'errors' => $validator->errors()
            ]);
        } else {

            return response()->json([
                'status' => true,
                'message' => 'اطلاعات با موفقیت ثبت شد.',
            ]);
        }
    }




    function convertDateToGregorian($date)
    {
        $date = explode('/', $date);
        $date = Verta::getGregorian(intval($date[0]), intval($date[1]), intval($date[2]));
        return join('-', $date);
    }

    public function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }




}
