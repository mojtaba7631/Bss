<?php

use App\Http\Controllers\admin\adminTicketController;
use App\Http\Controllers\admin\contractController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\exportController;
use App\Http\Controllers\admin\financialController;
use App\Http\Controllers\admin\projectController;
use App\Http\Controllers\admin\reportController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\LetterController;

use App\Http\Controllers\auth\loginController;
use App\Http\Controllers\auth\registerController;
use App\Http\Controllers\employer\contractController as employerContractController;
use App\Http\Controllers\employer\DashboardController as employerDashboardController;
use App\Http\Controllers\employer\employerAlertController;
use App\Http\Controllers\employer\employerLetterController;
use App\Http\Controllers\employer\employerTicketController as employerTicketController;
use App\Http\Controllers\employer\projectController as employerProjectController;
use App\Http\Controllers\employer\reportsController as employerReportController;
use App\Http\Controllers\general\generalController;
use App\Http\Controllers\legalUser\alertController as legalAlertController;
use App\Http\Controllers\legalUser\contractController as legalContractController;
use App\Http\Controllers\legalUser\dashboardController as legalDashboardController;
use App\Http\Controllers\legalUser\legalTicketController as legalTicketController;
use App\Http\Controllers\legalUser\legalUserLetterController;
use App\Http\Controllers\legalUser\projectController as legalProjectController;
use App\Http\Controllers\legalUser\reportsController as legalReportController;
use App\Http\Controllers\mainManager\contractController as mainManagerContractController;
use App\Http\Controllers\mainManager\DashboardController as mainManagerDashboardController;
use App\Http\Controllers\mainManager\exportController as mainExportController;
use App\Http\Controllers\mainManager\financialController as mainFinancialController;
use App\Http\Controllers\mainManager\mainManagerLetterController;
use App\Http\Controllers\mainManager\mainTicketController as mainTicketController;
use App\Http\Controllers\mainManager\reportController as mainManagerReportController;
use App\Http\Controllers\mainManager\payment_commandController as main_payment_commandController;
use App\Http\Controllers\mainManager\proceedingController as main_proceedingController;
use App\Http\Controllers\mainManager\DebtsContorller as main_DebtController;


use App\Http\Controllers\maliManager\contractController as maliManagerContractController;
use App\Http\Controllers\maliManager\dashboardController as maliManagerDashboardController;
use App\Http\Controllers\maliManager\exportController as maliExportController;
use App\Http\Controllers\maliManager\financialController as maliFinancialController;
use App\Http\Controllers\maliManager\maliManagerLetterController;
use App\Http\Controllers\maliManager\maliTicketController as maliTicketController;
use App\Http\Controllers\maliManager\payment_commandController as payment_commandController;
use App\Http\Controllers\maliManager\proceedingController as proceedingController;
use App\Http\Controllers\maliManager\DebtsContorller as maliDebtController;
use App\Http\Controllers\maliManager\maliPayableController;
use App\Http\Controllers\maliManager\leaveController as maliLeaveController;

use App\Http\Controllers\realUser\alertController as realAlertController;
use App\Http\Controllers\realUser\contractController as realContractController;
use App\Http\Controllers\realUser\dashboardController as realDashboardController;
use App\Http\Controllers\realUser\projectController as realProjectController;
use App\Http\Controllers\realUser\realTicketController as realTicketController;
use App\Http\Controllers\realUser\realUserLetterController;
use App\Http\Controllers\realUser\reportsController as realReportController;

use App\Http\Controllers\site\homeController;

use App\Http\Controllers\Supervisor\alertController as SupervisorAlertController;
use App\Http\Controllers\Supervisor\dashboardController as SupervisorDashboardController;
use App\Http\Controllers\Supervisor\reportsController as SupervisorReportController;
use App\Http\Controllers\Supervisor\superTicketController as superTicketController;
use App\Http\Controllers\Supervisor\supervisorLetterController;
use App\Http\Controllers\Supervisor\projectController as supervisorProjectController;

use App\Http\Controllers\tarhoBarnameManager\contractController as tarhoBarnameContractController;
use App\Http\Controllers\tarhoBarnameManager\dashboardController as tarho_BarnameManagerDashboardController;
use App\Http\Controllers\tarhoBarnameManager\exportController as tarhExportController;
use App\Http\Controllers\tarhoBarnameManager\financialController as tarhoBarnameFinancialController;
use App\Http\Controllers\tarhoBarnameManager\reportsController as tarhoBarnameReportController;
use App\Http\Controllers\tarhoBarnameManager\tarhoBarnameLetterController;
use App\Http\Controllers\tarhoBarnameManager\tarhPayableController;
use App\Http\Controllers\tarhoBarnameManager\tarhTicketController as tarhTicketController;
use App\Http\Controllers\tarhoBarnameManager\DebtsContorller as tarhDebtController;

use App\Http\Controllers\expert\expertLetterController as letter_expertController;
use App\Http\Controllers\expert\contractController as contract_expertController;
use App\Http\Controllers\expert\reportController as report_expertController;

use App\Http\Controllers\personnel\leaveController as leave_personnelController;
use App\Http\Controllers\personnel\dashboardController as dashboard_personnelController;
use App\Http\Controllers\personnel\missionController as mission_personnelController;

use App\Http\Controllers\deputy_plan_program\dashboardController as dashboard_deputy_planController;
use App\Http\Controllers\deputy_plan_program\leaveController as leave_deputy_planController;


use App\Http\Controllers\support_manager\dashboardController as dashboardSupportManagerController;
use App\Http\Controllers\support_manager\leaveController as leaveSupportManagerController;


use App\Http\Controllers\relations_manager\dashboardController as dashboardRelationsManagerController;
use App\Http\Controllers\relations_manager\leaveController as leaveRelationManagerController;

use App\Http\Controllers\support_expert\dashboardController as dashboardSupport_expertController;

use App\Http\Controllers\special_expert\dashboardController as dashboardSpecial_expertController;


use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [homeController::class, 'index'])->name('home');

Route::get('reg', function () {
    return view('auth.register');
})->name('reg');

Route::get('legal_reg', function () {
    return view('auth.legal_register');
})->name('legal_reg');

Route::get('logout', function () {
    auth()->logout();
    return redirect('login');
})->name('logout');

Route::get('register', [registerController::class, 'index'])->name('register');
Route::post('user/register', [registerController::class, 'register'])->name('user.register');

Route::get('login', [loginController::class, 'login'])->middleware('loginAuth')->name('login');
Route::post('verify', [loginController::class, 'verify'])->name('verify');

//general
Route::get('general/checkProjectsAlert', [generalController::class, 'checkProjectsAlert'])->name('checkProjectsAlert');
Route::get('general/checkSupervisorAlert', [generalController::class, 'checkSupervisorAlert'])->name('checkSupervisorAlert');
Route::get('general/checkEmployerAlert', [generalController::class, 'checkEmployerAlert'])->name('checkEmployerAlert');
Route::get('general/clearAlert', [generalController::class, 'clearAlert'])->name('clearAlert');

/*****************************************************Main Dashboard***************************************************/
Route::group(['prefix' => 'admin-access/dashboard', 'middleware' => 'adminAuth'], function () {

    Route::get('/color-donut-chart/{color?}', [DashboardController::class, 'colorDonutChartDetail'])->name('admin_colorDonutChartDetail');
    Route::post('/', [DashboardController::class, 'getBarChart2Data'])->name('admin_getBarChart2Data');

    Route::get('/', [DashboardController::class, 'index'])->name('admin_index');

    Route::group(['prefix' => 'users', 'middleware' => 'adminAuth'], function () {
        Route::get('/', [UserController::class, 'index'])->name('admin_user_index');
        Route::get('view/{user}', [UserController::class, 'view'])->name('admin_user_view');
        Route::get('legal_view/{user}', [UserController::class, 'legal_view'])->name('admin_user_legal_view');
        Route::post('isActive', [UserController::class, 'isActive'])->name('admin_user_isActive');
        Route::post('notActive/{user}', [UserController::class, 'notActive'])->name('admin_user_notActive');
        Route::get('add', [UserController::class, 'add'])->name('admin_user_add');
        Route::post('create', [UserController::class, 'create'])->name('admin_user_create');
        Route::post('delete', [UserController::class, 'delete'])->name('admin_user_delete');
        Route::post('admin-letter-access', [UserController::class, 'saveLetterAccess'])->name('admin_letter_access');
    });

    Route::group(['prefix' => 'contracts', 'middleware' => 'adminAuth'], function () {
        Route::get('Accept_contract', [contractController::class, 'Accept_contract'])->name('admin_Accept_contract');
        Route::get('signed_minot_financial/{project}', [contractController::class, 'signed_minot_admin'])->name('admin_contract_signed_minot');

    });

    /****************************** Main Dashboard Project *************************************************/
    Route::group(['prefix' => 'projects', 'middleware' => 'adminAuth'], function () {
        Route::get('/', [projectController::class, 'project_list'])->name('admin_project_list_index');
        Route::post('search_index', [projectController::class, 'search_index'])->name('admin_project_list_Search');
        Route::get('add', [projectController::class, 'add'])->name('admin_project_add');
        Route::post('create', [projectController::class, 'create'])->name('admin_project_create');
        Route::get('main_signed_minot/{project}', [projectController::class, 'signed_minot'])->name('admin_contract_signed_minot');
        Route::post('admin_delete', [projectController::class, 'delete_contract'])->name('admin_contract_delete');
        Route::post('admin_delete_pro', [projectController::class, 'delete_project'])->name('admin_project_delete');
    });
    /****************************** Main Dashboard Project *************************************************/

    /****************************** Main Dashboard Ticket *************************************************/
    Route::group(['prefix' => 'tickets', 'middleware' => 'adminAuth'], function () {
        Route::get('/{contact_id?}', [adminTicketController::class, 'index'])->name('admin_ticket_index');
        Route::post('send//{contact_id}', [adminTicketController::class, 'send'])->name('admin_ticket_send');
        Route::get('ticket/download-attachment/{ticket_id}', [adminTicketController::class, 'download'])->name('admin_ticket_download_ticket');
    });
    /****************************** Main Dashboard Ticket *************************************************/

    /****************************** Main Dashboard Profile *************************************************/
    Route::group(['prefix' => 'profile', 'middleware' => 'adminAuth'], function () {
        Route::get('/', [DashboardController::class, 'profile'])->name('admin_profile');
        Route::post('update', [DashboardController::class, 'update_profile'])->name('admin_profile_update');
        Route::post('change_pass', [DashboardController::class, 'change_pass'])->name('admin_change_pass');
    });
    /****************************** Main Dashboard Profile *************************************************/

    /****************************** Main Dashboard Report *************************************************/
    Route::group(['prefix' => 'reports', 'middleware' => 'adminAuth'], function () {
        Route::get('/', [reportController::class, 'index'])->name('admin_report_index');
        Route::post('search_index', [reportController::class, 'search_index'])->name('admin_report_search_index');
        Route::get('report/download/{report}', [reportController::class, 'download_file'])->name('admin_report_download_file');
    });
    /****************************** Main Dashboard Report *************************************************/

    /****************************** Main Dashboard financial  *************************************************/
    Route::group(['prefix' => 'financial', 'middleware' => 'adminAuth'], function () {
        Route::get('/', [financialController::class, 'index'])->name('admin_financial_index');
        Route::post('search_index_financial', [financialController::class, 'search_index_financial'])->name('admin_search_index_financial');
        Route::get('force', [financialController::class, 'force_index'])->name('admin_financial_force_index');
        Route::post('force_search_index', [financialController::class, 'force_search_index'])->name('admin_financial_force_index_search');
    });
    /****************************** Main Dashboard financial  *************************************************/

    /****************************** Main Dashboard Excell *************************************************/
    Route::post('/getPaymentExcel', [exportController::class, 'getPaymentExcel'])->name('admin_getPaymentExcel');
    /****************************** Main Dashboard Excell *************************************************/

    /********** Main Dashboard letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'adminAuth'], function () {
        Route::get('/sent', [LetterController::class, 'sent_index'])->name('admin_letter_sent_index');
        Route::get('/delivered', [LetterController::class, 'delivered_index'])->name('admin_letter_delivered_index');
        Route::get('/new', [LetterController::class, 'new'])->name('admin_letter_new');
        Route::post('/signature', [LetterController::class, 'signature'])->name('admin_letter_signature');
        Route::post('/add', [LetterController::class, 'add'])->name('admin_letter_add');
        Route::get('/preview/{letter_id}', [LetterController::class, 'preview'])->name('admin_letter_preview');
        Route::get('/view/{letter_id}', [LetterController::class, 'view'])->name('admin_letter_view');
        Route::post('/final-submit/{letter_id}', [LetterController::class, 'final_submit'])->name('admin_letter_final_submit');
    });
    /********** Main Dashboard letter *****************/
});
/*****************************************************Main Dashboard***************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/***************************************************LegalUser Dashboard************************************************/
Route::group(['prefix' => 'legalUser-access/dashboard', 'middleware' => ['legalUserAuth']], function () {

    Route::get('/', [legalDashboardController::class, 'index'])->name('legalUser_index');

    /****************************** LegalUser Profile *************************************************/
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [legalDashboardController::class, 'profile'])->name('legal_profile');
        Route::post('update', [legalDashboardController::class, 'update_profile'])->name('legal_profile_update');
        Route::post('change_pass', [legalDashboardController::class, 'change_pass'])->name('legal_change_pass');
    });
    /****************************** LegalUser profile *************************************************/

    /****************************** LegalUser Projects *************************************************/
    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [legalProjectController::class, 'index'])->name('legal_project_in_process');
        Route::post('search_index', [legalProjectController::class, 'search_index'])->name('legal_project_in_process_Search');
        Route::get('add', [legalProjectController::class, 'add'])->name('legal_project_add');
        Route::get('edit/{project}', [legalProjectController::class, 'edit'])->name('legal_project_edit');

        Route::post('create', [legalProjectController::class, 'create'])->name('legal_project_create');
        Route::post('update/{project}', [legalProjectController::class, 'update'])->name('legal_project_update');
        Route::get('error_message/{project}', [legalProjectController::class, 'error_message'])->name('legal_project_error_message');
        Route::get('completed_projects', [legalProjectController::class, 'completed_projects'])->name('legal_project_completed');
        Route::post('project_completed_Search', [legalProjectController::class, 'project_completed_Search'])->name('legal_project_completed_Search');

        Route::post('delete_pro', [legalProjectController::class, 'delete_project'])->name('legal_project_delete');
        Route::get('download_propusal/{project}', [legalProjectController::class, 'download_propusal'])->name('legal_download_propusal');
    });
    /****************************** LegalUser Projects *************************************************/

    /****************************** LegalUser Contract *************************************************/
    Route::group(['prefix' => 'contracts'], function () {
        Route::get('/', [legalContractController::class, 'index'])->name('legal_contract_index');
        Route::get('add/{project_id}', [legalContractController::class, 'add'])->name('legal_contract_add');
        Route::get('edit/{project_id}', [legalContractController::class, 'edit'])->name('legal_contract_edit');
        Route::get('contract_edit/{project}', [legalContractController::class, 'contract_edit'])->name('legal_project_contract_edit');
        Route::post('contract_update', [legalContractController::class, 'contract_update'])->name('legal_project_contract_update');
        Route::post('contract_create', [legalContractController::class, 'create'])->name('legal_contract_create');
        Route::get('minot/{project}', [legalContractController::class, 'minot'])->name('legal_contract_minot');
        Route::get('legal_signed_minot/{project}', [legalContractController::class, 'signed_minot'])->name('legal_contract_signed_minot');
        Route::get('send_sign/{project}', [legalContractController::class, 'send_sign'])->name('legal_contract_send_sign');
        Route::get('verify_sign/{project}', [legalContractController::class, 'verify_sign'])->name('legal_contract_verify_sign');
        Route::post('delete', [legalContractController::class, 'delete_contract'])->name('legal_contract_delete');
        Route::get('legal_view/{project}', [legalContractController::class, 'view'])->name('legal_contract_view');
    });
    /****************************** LegalUser Contract *************************************************/

    /****************************** LegalUser alerts *************************************************/
    Route::group(['prefix' => 'alerts'], function () {
        Route::get('/', [legalAlertController::class, 'index'])->name('legal_alerts_index');
    });
    /****************************** LegalUser alerts *************************************************/

    /****************************** LegalUser report *************************************************/
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', [legalReportController::class, 'index'])->name('legal_reports_index');
        Route::post('search_index_legal', [legalReportController::class, 'search_index_legal'])->name('legal_reports_search_index');
        Route::get('detail_report/{project}', [legalReportController::class, 'detail_report'])->name('legal_reports_details');
        Route::post('upload_report', [legalReportController::class, 'upload_report'])->name('legal_reports_upload');
        Route::get('report/download/{report}', [legalReportController::class, 'download_file'])->name('legal_reports_download_file');
        Route::get('report/download_finance_file/{report}', [legalReportController::class, 'download_finance_file'])->name('legal_reports_download_finance_file');
    });
    /****************************** LegalUser report *************************************************/

    /****************************** LegalUser Ticket *************************************************/
    Route::group(['prefix' => 'tickets'], function () {
        Route::get('/{contact_id?}', [legalTicketController::class, 'index'])->name('legal_ticket_index');
        Route::post('send//{contact_id}', [legalTicketController::class, 'send'])->name('legal_ticket_send');
        Route::get('ticket/download-attachment/{ticket_id}', [legalTicketController::class, 'download'])->name('legal_ticket_download_ticket');
    });
    /****************************** LegalUser Ticket *************************************************/

    /********** LegalUser letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'legalUserAuth'], function () {
        Route::get('/sent', [legalUserLetterController::class, 'sent_index'])->name('legalUser_letter_sent_index');
        Route::get('/delivered', [legalUserLetterController::class, 'delivered_index'])->name('legalUser_letter_delivered_index');
        Route::get('/new', [legalUserLetterController::class, 'new'])->name('legalUser_letter_new');
        Route::post('/signature', [legalUserLetterController::class, 'signature'])->name('legalUser_letter_signature');
        Route::post('/add', [legalUserLetterController::class, 'add'])->name('legalUser_letter_add');
        Route::get('/preview/{letter_id}', [legalUserLetterController::class, 'preview'])->name('legalUser_letter_preview');
        Route::get('/view/{letter_id}', [legalUserLetterController::class, 'view'])->name('legalUser_letter_view');
        Route::post('/final-submit/{letter_id}', [legalUserLetterController::class, 'final_submit'])->name('legalUser_letter_final_submit');
    });
    /********** LegalUser letter *****************/

});
/***************************************************LegalUser Dashboard************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/****************************************Tarh va Barnameh Manager Dashboard********************************************/
Route::group(['prefix' => 'tarhoBarname_manager-access/dashboard', 'middleware' => ['tarhoBarnameManagerAuth']], function () {

    Route::get('/', [tarho_BarnameManagerDashboardController::class, 'index'])->name('tarho_Barname_manager_index');
    Route::get('/color-donut-chart/{color?}', [tarho_BarnameManagerDashboardController::class, 'colorDonutChartDetail'])->name('tarho_Barname_manager_colorDonutChartDetail');

    Route::post('/', [tarho_BarnameManagerDashboardController::class, 'getBarChart2Data'])->name('tarho_Barname_getBarChart2Data');
    /****************************** Tarh va Barnameh Manager Project *************************************************/
//    Route::group(['prefix' => 'projects', 'middleware' => 'legalUserAuth'], function () {
//        Route::get('/', [legalProjectController::class, 'index'])->name('legal_project_index');
//        Route::get('add', [legalProjectController::class, 'add'])->name('legal_project_add');
//        Route::post('create', [legalProjectController::class, 'create'])->name('legal_project_create');
//    });
    /****************************** Tarh va Barnameh Manager Project *************************************************/

    /****************************** Tarh va Barnameh Manager Contract *************************************************/
    Route::group(['prefix' => 'contracts', 'middleware' => 'tarhoBarnameManagerAuth'], function () {
        Route::get('/gantt_chart/{employer_id?}', [tarhoBarnameContractController::class, 'gantt_chart'])->name('tarhoBarname_gantt_chart');

        Route::get('/', [tarhoBarnameContractController::class, 'index'])->name('tarhoBarname_contract_index');
        Route::post('search_index', [tarhoBarnameContractController::class, 'search_index'])->name('tarhoBarname_contract_search_index');
        Route::get('add/{project_id}', [tarhoBarnameContractController::class, 'add'])->name('tarhoBarname_contract_add');
        Route::post('create', [tarhoBarnameContractController::class, 'create'])->name('tarhoBarname_contract_create');
        Route::get('view/{project}', [tarhoBarnameContractController::class, 'view'])->name('tarhoBarname_contract_view');
        Route::post('verify', [tarhoBarnameContractController::class, 'tarhoBarname_verify'])->name('tarhoBarname_verify');
        Route::post('verify_sign', [tarhoBarnameContractController::class, 'tarhoBarname_verify_sign'])->name('tarhoBarname_verify_sign');
        Route::post('notActive/{project}', [tarhoBarnameContractController::class, 'notActive'])->name('tarhoBarname_notActive');
        Route::get('error_message/{project}', [tarhoBarnameContractController::class, 'error_message'])->name('tarhoBarname_error_message');
        Route::get('full_contract', [tarhoBarnameContractController::class, 'full_contract'])->name('tarhoBarname_full_contract');
        Route::post('full_searches', [tarhoBarnameContractController::class, 'full_search_index'])->name('tarhoBarname_contract_full_search_index');
        Route::get('signed_minot_financial/{project}', [tarhoBarnameContractController::class, 'signed_minot_tarh'])->name('tarhoBarname_contract_signed_minot');
        Route::get('project/download/{project}', [tarhoBarnameContractController::class, 'download_file'])->name('tarhoBarname_download_file');
        Route::post('delete', [tarhoBarnameContractController::class, 'delete_contract'])->name('tarhoBarname_contract_delete');
        Route::post('delete_pro', [tarhoBarnameContractController::class, 'delete_project'])->name('tarhoBarname_project_delete');
        Route::post('termination', [tarhoBarnameContractController::class, 'contract_termination'])->name('tarhoBarname_contract_termination');
        Route::post('supervisor_edit', [tarhoBarnameContractController::class, 'supervisor_edit'])->name('tarhoBarname_contract_supervisor_edit');
        Route::post('main_delete', [tarhoBarnameContractController::class, 'delete_contract'])->name('tarhoBarname_contract_delete');
        Route::post('main_delete_pro', [tarhoBarnameContractController::class, 'delete_project'])->name('tarhoBarname_project_delete');
    });
    /****************************** Tarh va Barnameh Manager Report *************************************************/
    Route::group(['prefix' => 'reports', 'middleware' => 'tarhoBarnameManagerAuth'], function () {
        Route::get('/', [tarhoBarnameReportController::class, 'index'])->name('tarhoBarname_report_index');
        Route::get('report_detail/{project}', [tarhoBarnameReportController::class, 'report_detail'])->name('tarhoBarname_report_detail');
        Route::post('report_update', [tarhoBarnameReportController::class, 'report_update'])->name('tarhoBarname_report_update');
        Route::get('report/download/{report}', [tarhoBarnameReportController::class, 'download_file'])->name('tarhoBarname_reports_download_file');
    });
    /****************************** Tarh va Barnameh Manager Report ************************************************/

    /****************************** Tarh va Barnameh Manager Financial ************************************************/
    Route::group(['prefix' => 'financial', 'middleware' => 'tarhoBarnameManagerAuth'], function () {
        // Route::get('/', [tarhoBarnameFinancialController::class, 'financial_index'])->name('tarhoBarname_financial_index');
        // Route::get('/detail/{user_id}', [tarhoBarnameFinancialController::class, 'index_detail'])->name('tarhoBarname_financial_index_detail');
        Route::get('/', [tarhoBarnameFinancialController::class, 'index_detail'])->name('tarhoBarname_financial_index');

        Route::post('search_index', [tarhoBarnameFinancialController::class, 'search_index'])->name('tarhoBarname_financial_search');
        Route::get('pay_detail/{project}', [tarhoBarnameFinancialController::class, 'report_detail'])->name('tarhoBarname_financial_detail');
        Route::get('pay_detail/force/{project}', [tarhoBarnameFinancialController::class, 'force_detail'])->name('tarhoBarname_financial_force_detail');
        Route::post('payment_order', [tarhoBarnameFinancialController::class, 'payment_order'])->name('tarhoBarname_Payment_order');
        Route::post('second_force_payment', [tarhoBarnameFinancialController::class, 'second_force_payment'])->name('tarhoBarname_second_force_payment');
        Route::get('force', [tarhoBarnameFinancialController::class, 'force_index'])->name('tarhoBarname_financial_force_index');
        Route::post('force_search_index', [tarhoBarnameFinancialController::class, 'force_search_index'])->name('tarhoBarname_financial_force_index_search');
        Route::post('force/payment', [tarhoBarnameFinancialController::class, 'force_payment'])->name('tarhoBarname_force_payment');
        Route::get('final_payments', [tarhoBarnameFinancialController::class, 'final_payments'])->name('tarhoBarname_final_payments');
        Route::post('search_final_payments', [tarhoBarnameFinancialController::class, 'search_final_payments'])->name('tarhoBarname_final_payments_index_search');

        //payable
        Route::get('payable', [tarhPayableController::class, 'index'])->name('tarhoBarname_payable_index');
        Route::get('payable/{id}', [tarhPayableController::class, 'detail'])->name('tarhoBarname_payable_detail');
        Route::post('payable/send-to-mali', [tarhPayableController::class, 'send_to_mali'])->name('tarhoBarname_send_to_mali');
    });
    /****************************** Tarh va Barnameh Manager Financial ************************************************/

    /****************************** Tarh va Barnameh Manager Profile *************************************************/
    Route::group(['prefix' => 'profile', 'middleware' => 'tarhoBarnameManagerAuth'], function () {
        Route::get('/', [tarho_BarnameManagerDashboardController::class, 'profile'])->name('tarhoBarname_profile');
        Route::post('update', [tarho_BarnameManagerDashboardController::class, 'update_profile'])->name('tarhoBarname_profile_update');
        Route::post('change_pass', [tarho_BarnameManagerDashboardController::class, 'change_pass'])->name('tarhoBarname_change_pass');
    });
    /****************************** Tarh va Barnameh Manager Profile *************************************************/

    /****************************** Tarh va Barnameh Ticket *************************************************/
    Route::group(['prefix' => 'tickets', 'middleware' => 'tarhoBarnameManagerAuth'], function () {
        Route::get('/{contact_id?}', [tarhTicketController::class, 'index'])->name('tarhoBarname_ticket_index');
        Route::post('send//{contact_id}', [tarhTicketController::class, 'send'])->name('tarhoBarname_ticket_send');
        Route::get('ticket/download-attachment/{ticket_id}', [tarhTicketController::class, 'download'])->name('tarhoBarname_ticket_download_ticket');
    });
    /****************************** Tarh va Barnameh Ticket *************************************************/

    /****************************** Tarh va Barnameh Excell *************************************************/
    Route::get('/getContractAsWord/{project_id}', [tarhExportController::class, 'getContractWord'])->name('tarhoBarname_get_contract_as_word');
    Route::post('/getPaymentExcel', [tarhExportController::class, 'getPaymentExcel'])->name('tarhoBarname_getPaymentExcel');
    Route::get('/getRemindingExcel', [tarhExportController::class, 'getRemindingExcel'])->name('tarhoBarname_getRemindingExcel');
    /****************************** Tarh va Barnameh Excell *************************************************/

    /********** Tarh va Barnameh letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'tarhoBarnameManagerAuth'], function () {
        Route::get('/sent', [tarhoBarnameLetterController::class, 'sent_index'])->name('tarhobarname_letter_sent_index');
        Route::get('/delivered', [tarhoBarnameLetterController::class, 'delivered_index'])->name('tarhobarname_letter_delivered_index');
        Route::get('/new', [tarhoBarnameLetterController::class, 'new'])->name('tarhobarname_letter_new');
        Route::post('/signature', [tarhoBarnameLetterController::class, 'signature'])->name('tarhobarname_letter_signature');
        Route::post('/add', [tarhoBarnameLetterController::class, 'add'])->name('tarhobarname_letter_add');
        Route::get('/preview/{letter_id}', [tarhoBarnameLetterController::class, 'preview'])->name('tarhobarname_letter_preview');
        Route::get('/view/{letter_id}', [tarhoBarnameLetterController::class, 'view'])->name('tarhobarname_letter_view');
        Route::post('/final-submit/{letter_id}', [tarhoBarnameLetterController::class, 'final_submit'])->name('tarhobarname_letter_final_submit');
    });
    /********** Tarh va Barnameh letter *****************/

    /********** Tarh va Barnameh debts ****************************************************************************/
    Route::get('debts', [tarhDebtController::class, 'index'])->name('tarhoBarname_get_debts');
    /********** Tarh va Barnameh debts ****************************************************************************/

});
/****************************************Tarh va Barnameh Manager Dashboard********************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/***************************************************Employer Dashboard************************************************/
Route::group(['prefix' => 'employer-access/dashboard', 'middleware' => 'employerAuth'], function () {
    Route::get('/', [employerDashboardController::class, 'index'])->name('employer_index');

    /****************************** Supervisor alert *************************************************/
    Route::group(['prefix' => 'alerts', 'middleware' => 'employerAuth'], function () {
        Route::get('/', [employerAlertController::class, 'index'])->name('employer_alert');
    });
    /****************************** Supervisor alert *************************************************/

    /****************************** Employer Project *************************************************/
    Route::group(['prefix' => 'projects', 'middleware' => 'employerAuth'], function () {
        Route::get('/', [employerProjectController::class, 'index'])->name('employer_project_index');
        Route::get('view/{project}', [employerProjectController::class, 'view'])->name('employer_project_view');
        Route::post('confirmation', [employerProjectController::class, 'confirmation'])->name('employer_project_confirmation');
        Route::post('notActive/{project}', [employerProjectController::class, 'notActive'])->name('employer_project_notActive');
        Route::get('error_message/{project}', [employerProjectController::class, 'error_message'])->name('employer_project_error_message');
        Route::get('completed_projects', [employerProjectController::class, 'completed_projects'])->name('employer_project_completed_projects');
        Route::post('full_search_index', [employerProjectController::class, 'full_search_index'])->name('employer_project_completed_search_index');
        Route::get('in_process_projects', [employerProjectController::class, 'in_process_projects'])->name('employer_project_in_process_projects');
        Route::post('search_index', [employerProjectController::class, 'search_index'])->name('employer_project_in_process_search_index');
        Route::get('signed_minot_financial/{project}', [employerProjectController::class, 'signed_minot_mali'])->name('employer_contract_signed_minot');
    });
    /****************************** Employer Project *************************************************/

    /****************************** Employer Contract *************************************************/
    Route::group(['prefix' => 'contracts', 'middleware' => 'employerAuth'], function () {
        Route::get('/', [employerContractController::class, 'index'])->name('employer_contract_index');
        Route::post('search_index', [employerContractController::class, 'search_index'])->name('employer_contract_search_index');
        Route::get('add/{project_id}', [employerContractController::class, 'add'])->name('employer_contract_add');
        Route::post('create', [employerContractController::class, 'create'])->name('employer_contract_create');
        Route::get('view/{project}', [employerContractController::class, 'view'])->name('employer_contract_view');
        Route::post('verify', [employerContractController::class, 'employer_verify'])->name('employer_verify');
        Route::post('notActive', [employerContractController::class, 'notActive'])->name('employer_contract_notActive');
    });
    /****************************** Employer Contract *************************************************/

    /****************************** Employer Report *************************************************/
    Route::group(['prefix' => 'reports', 'middleware' => 'employerAuth'], function () {
        Route::get('/', [employerReportController::class, 'index'])->name('employer_report_index');
        Route::post('search_index', [employerReportController::class, 'search_index'])->name('employer_report_search_index');
        Route::get('report_detail/{project}', [employerReportController::class, 'report_detail'])->name('employer_report_detail');
        Route::post('report_update', [employerReportController::class, 'report_update'])->name('employer_report_update');
        Route::get('report/download/{report}', [employerReportController::class, 'download_file'])->name('employer_report_download_file');
    });
    /****************************** Employer Report ************************************************/

    /****************************** Employer Profile *************************************************/
    Route::group(['prefix' => 'profile', 'middleware' => 'employerAuth'], function () {
        Route::get('/', [employerDashboardController::class, 'profile'])->name('employer_profile');
        Route::get('update', [employerDashboardController::class, 'update_profile'])->name('employer_profile_update');
    });
    /****************************** Employer Profile *************************************************/

    /****************************** Employer Ticket *************************************************/
    Route::group(['prefix' => 'tickets', 'middleware' => 'employerAuth'], function () {
        Route::get('/{contact_id?}', [employerTicketController::class, 'index'])->name('employer_ticket_index');
        Route::post('send//{contact_id}', [employerTicketController::class, 'send'])->name('employer_ticket_send');
        Route::get('ticket/download-attachment/{ticket_id}', [employerTicketController::class, 'download'])->name('employer_ticket_download_ticket');
    });
    /****************************** Employer Ticket *************************************************/

    /********** Employer letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'employerAuth'], function () {
        Route::get('/sent', [employerLetterController::class, 'sent_index'])->name('employer_letter_sent_index');
        Route::get('/delivered', [employerLetterController::class, 'delivered_index'])->name('employer_letter_delivered_index');
        Route::get('/new', [employerLetterController::class, 'new'])->name('employer_letter_new');
        Route::post('/signature', [employerLetterController::class, 'signature'])->name('employer_letter_signature');
        Route::post('/add', [employerLetterController::class, 'add'])->name('employer_letter_add');
        Route::get('/preview/{letter_id}', [employerLetterController::class, 'preview'])->name('employer_letter_preview');
        Route::get('/view/{letter_id}', [employerLetterController::class, 'view'])->name('employer_letter_view');
        Route::post('/final-submit/{letter_id}', [employerLetterController::class, 'final_submit'])->name('employer_letter_final_submit');
    });
    /********** Employer letter *****************/

});
/***************************************************Employer Dashboard************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/***********************************************Main Manager Dashboard************************************************/
Route::group(['prefix' => 'mainManager-access/dashboard', 'middleware' => 'mainManagerAuth'], function () {
    Route::get('/', [mainManagerDashboardController::class, 'index'])->name('main_manager_index');
    Route::get('/color-donut-chart/{color?}', [mainManagerDashboardController::class, 'colorDonutChartDetail'])->name('mainManager_colorDonutChartDetail');

    Route::post('/', [mainManagerDashboardController::class, 'getBarChart2Data'])->name('main_manager_getBarChart2Data');
    /****************************** Main Manager Contract ***************************supervisor_report_search_index**********************/
    Route::group(['prefix' => 'contracts', 'middleware' => 'mainManagerAuth'], function () {
        Route::get('/gantt_chart/{employer_id?}', [mainManagerContractController::class, 'gantt_chart'])->name('mainManager_gantt_chart');

        Route::get('/', [mainManagerContractController::class, 'index'])->name('mainManager_contract_index');
        Route::post('full_searches', [mainManagerContractController::class, 'search_index'])->name('mainManager_contract_search_index');
        Route::get('add/{project_id}', [mainManagerContractController::class, 'add'])->name('mainManager_contract_add');
        Route::post('create', [mainManagerContractController::class, 'create'])->name('mainManager_contract_create');
        Route::get('view/{project}', [mainManagerContractController::class, 'view'])->name('mainManager_contract_view');
        Route::post('verify', [mainManagerContractController::class, 'mainManager_verify'])->name('mainManager_verify');
        Route::post('notActive/{project}', [mainManagerContractController::class, 'notActive'])->name('mainManager_notActive');
        Route::get('Accept_contract', [mainManagerContractController::class, 'Accept_contract'])->name('mainManager_Accept_contract');
        Route::get('signed_minot_financial/{project}', [mainManagerContractController::class, 'signed_minot_mali'])->name('mainManager_contract_signed_minot');
        Route::post('full_search', [mainManagerContractController::class, 'search_accept_main'])->name('mainManager_contract_accept_search');
//        Route::get('project/download/{peyment}', [mainManagerContractController::class, 'download_file'])->name('mainManager_download_file');
        Route::post('main_delete', [mainManagerContractController::class, 'delete_contract'])->name('main_manager_contract_delete');
        Route::post('main_delete_pro', [mainManagerContractController::class, 'delete_project'])->name('main_manager_project_delete');
    });
    /****************************** Main Manager Contract *************************************************/

    /****************************** Main Manager Report *************************************************/
    Route::group(['prefix' => 'reports', 'middleware' => 'mainManagerAuth'], function () {
        Route::get('/', [mainManagerReportController::class, 'index'])->name('mainManager_report_index');
        Route::post('search_index', [mainManagerReportController::class, 'search_index'])->name('mainManager_report_search_index');
        Route::get('report_detail/{project}', [mainManagerReportController::class, 'report_detail'])->name('mainManager_report_detail');
        Route::post('report_update', [mainManagerReportController::class, 'report_update'])->name('mainManager_report_update');
        Route::get('report/download/{report}', [mainManagerReportController::class, 'download_file'])->name('mainManager_reports_download_file');
    });
    /****************************** Main Manager Report *************************************************/

    /****************************** Main Manager Profile *************************************************/
    Route::group(['prefix' => 'profile', 'middleware' => 'mainManagerAuth'], function () {
        Route::get('/', [mainManagerDashboardController::class, 'profile'])->name('mainManager_profile');
        Route::post('update', [mainManagerDashboardController::class, 'update_profile'])->name('mainManager_profile_update');
        Route::post('change_pass', [mainManagerDashboardController::class, 'change_pass'])->name('mainManager_change_pass');
    });
    /****************************** Main Manager Profile *************************************************/

    /****************************** Main Manager Ticket *************************************************/
    Route::group(['prefix' => 'tickets', 'middleware' => 'mainManagerAuth'], function () {
        Route::get('/{contact_id?}', [mainTicketController::class, 'index'])->name('mainManager_ticket_index');
        Route::post('send//{contact_id}', [mainTicketController::class, 'send'])->name('mainManager_ticket_send');
        Route::get('ticket/download-attachment/{ticket_id}', [mainTicketController::class, 'download'])->name('mainManager_ticket_download_ticket');
    });
    /****************************** Main Manager Ticket *************************************************/

    /****************************** Main Manager Excell *************************************************/
    Route::get('/getContractAsWord/{project_id}', [mainExportController::class, 'getContractWord'])->name('mainManager_get_contract_as_word');
    Route::post('/getPaymentExcel', [mainExportController::class, 'getPaymentExcel'])->name('mainManager_getPaymentExcel');
    Route::get('/getProceedingWord/{payment_id}', [mainExportController::class, 'getProceedingWord'])->name('mainManager_getProceedingWord');
    Route::get('/getRemindingExcel', [mainExportController::class, 'getRemindingExcel'])->name('mainManager_getRemindingExcel');
    /****************************** Main Manager Excell *************************************************/

    /****************************** Main Manager financial  *************************************************/
    Route::group(['prefix' => 'financial', 'middleware' => 'mainManagerAuth'], function () {
        Route::get('/', [mainFinancialController::class, 'index'])->name('mainManager_financial_index');
        Route::post('search_index', [mainFinancialController::class, 'search_index'])->name('mainManager_financial_search');
        Route::get('force', [mainFinancialController::class, 'force_index'])->name('mainManager_financial_force_index');
        Route::post('force_search_index', [mainFinancialController::class, 'force_search_index'])->name('mainManager_financial_force_index_search');
        Route::get('fullPayments', [mainFinancialController::class, 'fullPayments'])->name('mainManager_fullPayments');
        Route::post('full_search_index', [mainFinancialController::class, 'full_search_index'])->name('mainManager_fullPayments_search');
        Route::get('proposal/download/{project}', [mainFinancialController::class, 'download_file'])->name('mainManager_download_file');
        Route::get('payment/download/{payment}', [mainFinancialController::class, 'download_file_payment'])->name('mainManager_download_file_payment');
    });
    /****************************** Main Manager financial  *************************************************/

    /****************************** Main Manager Proceeding *************************************************/
    Route::get('/proceeding/{payment_id}', [main_proceedingController::class, 'index'])->name('mainManager_proceeding');
    /****************************** Main Manager Proceeding *************************************************/

    /****************************** Main Manager payment_command *************************************************/
    Route::get('/payment_command/{payment_id}', [main_payment_commandController::class, 'index'])->name('mainManager_payment_command');
    /****************************** Main Manager payment_command *************************************************/

    /********** Main Manager letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'mainManagerAuth'], function () {
        Route::get('/sent', [mainManagerLetterController::class, 'sent_index'])->name('mainManager_letter_sent_index');
        Route::get('/delivered', [mainManagerLetterController::class, 'delivered_index'])->name('mainManager_letter_delivered_index');
        Route::get('/new', [mainManagerLetterController::class, 'new'])->name('mainManager_letter_new');
        Route::post('/signature', [mainManagerLetterController::class, 'signature'])->name('mainManager_letter_signature');
        Route::post('/add', [mainManagerLetterController::class, 'add'])->name('mainManager_letter_add');
        Route::get('/preview/{letter_id}', [mainManagerLetterController::class, 'preview'])->name('mainManager_letter_preview');
        Route::get('/view/{letter_id}', [mainManagerLetterController::class, 'view'])->name('mainManager_letter_view');
        Route::post('/final-submit/{letter_id}', [mainManagerLetterController::class, 'final_submit'])->name('mainManager_letter_final_submit');
    });
    /********** Main Manager letter *****************/

    /********** Main Manager debts ****************************************************************************/
    Route::get('debts', [main_DebtController::class, 'index'])->name('mainManager_get_debts');
    /********** Main Manager debts ****************************************************************************/

});
/***********************************************Main Manager Dashboard************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/********************************************Supervisor Dashboard******************************************************/
Route::group(['prefix' => 'supervisor-access/dashboard', 'middleware' => 'SupervisorAuth'], function () {
    Route::get('/', [SupervisorDashboardController::class, 'index'])->name('Supervisor_index');

    /****************************** Supervisor alert *************************************************/
    Route::group(['prefix' => 'projects', 'middleware' => 'SupervisorAuth'], function () {
        Route::get('/allProjects', [supervisorProjectController::class, 'all_projects'])->name('supervisor_allProjects');
        Route::get('signed_minot_financial/{project}', [supervisorProjectController::class, 'signed_minot_mali'])->name('supervisor_contract_signed_minot');
        Route::post('full_search_index', [supervisorProjectController::class, 'full_search_index'])->name('supervisor_project_completed_search_index');
    });
    /****************************** Supervisor alert *************************************************/

    /****************************** Supervisor alert *************************************************/
    Route::group(['prefix' => 'alerts', 'middleware' => 'SupervisorAuth'], function () {
        Route::get('/', [SupervisorAlertController::class, 'index'])->name('supervisor_alert');
    });
    /****************************** Supervisor alert *************************************************/

    /****************************** Supervisor Report *************************************************/
    Route::group(['prefix' => 'reports', 'middleware' => 'SupervisorAuth'], function () {
        Route::get('/', [SupervisorReportController::class, 'index'])->name('supervisor_report_index');
        Route::post('search_index', [SupervisorReportController::class, 'search_index'])->name('supervisor_report_search_index');
        Route::get('report_detail/{project}', [SupervisorReportController::class, 'report_detail'])->name('supervisor_report_detail');
        Route::post('report_accept', [SupervisorReportController::class, 'report_accept'])->name('supervisor_report_update');
        Route::post('report_reject', [SupervisorReportController::class, 'report_reject'])->name('supervisor_report_reject');
        Route::get('report/download/{report}', [SupervisorReportController::class, 'download_file'])->name('supervisor_report_download_file');
        Route::get('Accept_report', [SupervisorReportController::class, 'Accept_report'])->name('supervisor_report_Accept');
        Route::get('fullReport', [SupervisorReportController::class, 'fullReport'])->name('supervisor_fullReport');
        Route::post('fullReport', [SupervisorReportController::class, 'searchFullReport'])->name('supervisor_search_fullReport');
    });
    /****************************** Supervisor Report *************************************************/

    /****************************** Supervisor Profile *************************************************/
    Route::group(['prefix' => 'profile', 'middleware' => 'SupervisorAuth'], function () {
        Route::get('/', [SupervisorDashboardController::class, 'profile'])->name('supervisor_profile');
        Route::post('update', [SupervisorDashboardController::class, 'update_profile'])->name('supervisor_profile_update');
        Route::post('change_pass', [SupervisorDashboardController::class, 'change_pass'])->name('supervisor_change_pass');
    });
    /****************************** Supervisor Profile *************************************************/

    /****************************** Supervisor Ticket *************************************************/
    Route::group(['prefix' => 'tickets', 'middleware' => 'SupervisorAuth'], function () {
        Route::get('/{contact_id?}', [superTicketController::class, 'index'])->name('super_ticket_index');
        Route::post('send//{contact_id}', [superTicketController::class, 'send'])->name('super_ticket_send');
        Route::get('ticket/download-attachment/{ticket_id}', [superTicketController::class, 'download'])->name('super_ticket_download_ticket');
    });
    /****************************** Supervisor Ticket *************************************************/

    /********** Supervisor letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'SupervisorAuth'], function () {
        Route::get('/sent', [supervisorLetterController::class, 'sent_index'])->name('supervisor_letter_sent_index');
        Route::get('/delivered', [supervisorLetterController::class, 'delivered_index'])->name('supervisor_letter_delivered_index');
        Route::get('/new', [supervisorLetterController::class, 'new'])->name('supervisor_letter_new');
        Route::post('/signature', [supervisorLetterController::class, 'signature'])->name('supervisor_letter_signature');
        Route::post('/add', [supervisorLetterController::class, 'add'])->name('supervisor_letter_add');
        Route::get('/preview/{letter_id}', [supervisorLetterController::class, 'preview'])->name('supervisor_letter_preview');
        Route::get('/view/{letter_id}', [supervisorLetterController::class, 'view'])->name('supervisor_letter_view');
        Route::post('/final-submit/{letter_id}', [supervisorLetterController::class, 'final_submit'])->name('supervisor_letter_final_submit');
    });
    /********** Supervisor letter *****************/
});
/********************************************Supervisor Dashboard******************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/**************************************************Mali Dashboard******************************************************/
Route::group(['prefix' => 'maliManager-access/dashboard', 'middleware' => 'maliManagerAuth'], function () {
    Route::get('/', [maliManagerDashboardController::class, 'index'])->name('maliManager_index');
    Route::get('/color-donut-chart/{color?}', [maliManagerDashboardController::class, 'colorDonutChartDetail'])->name('maliManager_colorDonutChartDetail');
    Route::post('/', [maliManagerDashboardController::class, 'getBarChart2Data'])->name('maliManager_getBarChart2Data');

    /****************************** Mali Contract *************************************************/
    Route::group(['prefix' => 'contracts', 'middleware' => 'maliManagerAuth'], function () {
        Route::get('/gantt_chart/{employer_id?}', [maliManagerContractController::class, 'gantt_chart'])->name('maliManager_gantt_chart');
        Route::get('index', [maliManagerContractController::class, 'index'])->name('maliManager_contract_index');
        Route::post('full_searches', [maliManagerContractController::class, 'search_index'])->name('maliManager_contract_search_index');
        Route::get('add/{project_id}', [maliManagerContractController::class, 'add'])->name('maliManager_contract_add');
        Route::post('create', [maliManagerContractController::class, 'create'])->name('maliManager_contract_create');
        Route::get('view/{project}', [maliManagerContractController::class, 'view'])->name('maliManager_contract_view');
        Route::post('verify', [maliManagerContractController::class, 'mainManager_verify'])->name('maliManager_verify');
        Route::get('sign/{project}', [maliManagerContractController::class, 'sign'])->name('maliManager_sign');
        Route::post('accept', [maliManagerContractController::class, 'accept'])->name('maliManager_accept');
        Route::get('full_accept/{project}', [maliManagerContractController::class, 'full_accept'])->name('maliManager_full_accept');
        Route::get('minot/{project}', [maliManagerContractController::class, 'minot'])->name('maliManager_minot');
        Route::get('final_minot/{project}', [maliManagerContractController::class, 'final_minot'])->name('maliManager_final_minot');
        Route::get('sign_list', [maliManagerContractController::class, 'sign_list'])->name('maliManager_sign_list');
        Route::post('sign_list_search', [maliManagerContractController::class, 'sign_list_search'])->name('maliManager_sign_list_search');
        Route::post('notActive/{project}', [maliManagerContractController::class, 'notActive'])->name('maliManager_notActive');
        Route::post('Accept_contract', [maliManagerContractController::class, 'Accept_contract'])->name('maliManager_Accept_contract');
        Route::get('signed_minot_financial/{project}', [maliManagerContractController::class, 'signed_minot_mali'])->name('maliManager_contract_signed_minot');
        Route::post('full_search', [maliManagerContractController::class, 'search_accept_mali'])->name('maliManager_contract_accept_search');
    });
    /****************************** Mali Contract *************************************************/

    /****************************** Mali financial  *************************************************/
    Route::group(['prefix' => 'financial', 'middleware' => 'maliManagerAuth'], function () {
        Route::get('/', [maliFinancialController::class, 'index'])->name('maliManager_financial_index');
        Route::post('search_index_financial', [maliFinancialController::class, 'search_index_financial'])->name('maliManager_search_index_financial');
        Route::get('fullPayments', [maliFinancialController::class, 'fullPayments'])->name('maliManager_fullPayments');
        Route::post('full_search_index', [maliFinancialController::class, 'full_search_index'])->name('maliManager_fullPayments_search');
        Route::get('pay_detail/{project}', [maliFinancialController::class, 'pay_detail'])->name('maliManager_financial_detail');
        Route::post('payment/{report}', [maliFinancialController::class, 'payment'])->name('maliManager_Payment');
        Route::get('add/{report}', [maliFinancialController::class, 'add'])->name('maliManager_financial_add');
        Route::post('Payments', [maliFinancialController::class, 'Payments'])->name('maliManager_financial_Payments');
        Route::post('financial_doc', [maliFinancialController::class, 'financial_doc'])->name('maliManager_financial_doc');
        Route::post('get_data', [maliFinancialController::class, 'get_data'])->name('maliManager_get_data');
        Route::get('payment/download/{peyment}', [maliFinancialController::class, 'download_file'])->name('maliManager_download_file');
        Route::get('check_list', [maliFinancialController::class, 'check_list'])->name('maliManager_checks');
        Route::post('took_reciept', [maliFinancialController::class, 'took_reciept'])->name('maliManager_took_reciept');

        //payable
        Route::get('payable', [maliPayableController::class, 'index'])->name('maliManager_payable_index');
        Route::post('payable/add', [maliPayableController::class, 'add'])->name('maliManager_payable_add');
    });
    /****************************** Mali financial  *************************************************/

    /****************************** Mali Profile *************************************************/
    Route::group(['prefix' => 'profile', 'middleware' => 'maliManagerAuth'], function () {
        Route::get('/', [maliManagerDashboardController::class, 'profile'])->name('maliManager_profile');
        Route::post('update', [maliManagerDashboardController::class, 'update_profile'])->name('maliManager_profile_update');
        Route::post('change_pass', [maliManagerDashboardController::class, 'change_pass'])->name('maliManager_change_pass');
    });
    /****************************** Mali Profile *************************************************/

    /****************************** Mali Manager Ticket *************************************************/
    Route::group(['prefix' => 'tickets', 'middleware' => 'maliManagerAuth'], function () {
        Route::get('/{contact_id?}', [maliTicketController::class, 'index'])->name('maliManager_ticket_index');
        Route::post('send//{contact_id}', [maliTicketController::class, 'send'])->name('maliManager_ticket_send');
        Route::get('ticket/download-attachment/{ticket_id}', [maliTicketController::class, 'download'])->name('maliManager_ticket_download_ticket');
    });
    /****************************** Mali Manager Ticket *************************************************/

    /****************************** Mali Manager Excell *************************************************/
    Route::get('/getContractAsWord/{project_id}', [maliExportController::class, 'getContractWord'])->name('maliManager_get_contract_as_word');
    Route::post('/getPaymentExcel', [maliExportController::class, 'getPaymentExcel'])->name('maliManager_getPaymentExcel');
    Route::get('/getRemindingExcel', [maliExportController::class, 'getRemindingExcel'])->name('maliManager_getRemindingExcel');
    /****************************** Mali Manager Excell *************************************************/

    /****************************** Mali Manager Proceeding *************************************************/
    Route::get('/proceeding/{payment_id}', [proceedingController::class, 'index'])->name('maliManager_proceeding');
    Route::get('/download_proceeding/{payment_id}', [maliExportController::class, 'getProceedingWord'])->name('maliManager_getProceedingWord');
    /****************************** Mali Manager Proceeding *************************************************/

    /****************************** Mali Manager payment_command *************************************************/
    Route::get('/payment_command/{payment_id}', [payment_commandController::class, 'index'])->name('maliManager_payment_command');
    /****************************** Mali Manager payment_command *************************************************/

    /********** Mali Manager letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'maliManagerAuth'], function () {
        Route::get('/sent', [maliManagerLetterController::class, 'sent_index'])->name('maliManager_letter_sent_index');
        Route::get('/delivered', [maliManagerLetterController::class, 'delivered_index'])->name('maliManager_letter_delivered_index');
        Route::get('/new', [maliManagerLetterController::class, 'new'])->name('maliManager_letter_new');
        Route::post('/signature', [maliManagerLetterController::class, 'signature'])->name('maliManager_letter_signature');
        Route::post('/add', [maliManagerLetterController::class, 'add'])->name('maliManager_letter_add');
        Route::get('/preview/{letter_id}', [maliManagerLetterController::class, 'preview'])->name('maliManager_letter_preview');
        Route::get('/view/{letter_id}', [maliManagerLetterController::class, 'view'])->name('maliManager_letter_view');
        Route::post('/final-submit/{letter_id}', [maliManagerLetterController::class, 'final_submit'])->name('maliManager_letter_final_submit');
    });
    /********** Mali Manager letter *****************/

    /********** Mali Manager debts ****************************************************************************/
    Route::get('debts', [maliDebtController::class, 'index'])->name('maliManager_get_debts');
    /********** Mali Manager debts ****************************************************************************/


    /********** Mali Manager leave ****************************************************************************/

    Route::group(['prefix' => 'leave', 'middleware' => 'maliManagerAuth'], function () {
        Route::get('finance_leave', [maliLeaveController::class, 'index'])->name('maliManager_leave_index');
        Route::get('finance_confirmation', [maliLeaveController::class, 'confirmation'])->name('maliManager_leave_confirmation');
        Route::get('finance_leave_create', [maliLeaveController::class, 'create'])->name('maliManager_leave_create');
        Route::post('finance_leave_store', [maliLeaveController::class, 'store'])->name('maliManager_leave_store');
        Route::post('maliManager_leave_agreement', [maliLeaveController::class, 'agreement'])->name('maliManager_leave_agreement');
    });

    /********** Mali Manager leave ****************************************************************************/
});
/**************************************************Mali Dashboard******************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/**********************************************RealUser Dashboard******************************************************/
Route::group(['prefix' => 'realUser-access/dashboard', 'middleware' => 'realUserAuth'], function () {
    Route::get('/', [realDashboardController::class, 'index'])->name('realUser_index');

    /****************************** RealUser Project *************************************************/
    Route::group(['prefix' => 'projects', 'middleware' => 'realUserAuth'], function () {
        Route::get('/', [realProjectController::class, 'index'])->name('real_project_in_process');
        Route::post('search_index', [realProjectController::class, 'search_index'])->name('real_project_in_process_Search');
        Route::get('add', [realProjectController::class, 'add'])->name('real_project_add');
        Route::post('create', [realProjectController::class, 'create'])->name('real_project_create');
        Route::get('completed_projects', [realProjectController::class, 'completed_projects'])->name('real_project_completed');
        Route::post('project_completed_Search', [realProjectController::class, 'project_completed_Search'])->name('real_project_completed_Search');
        Route::get('error_message/{project}', [realProjectController::class, 'error_message'])->name('real_project_error_message');
        Route::post('delete_pro', [realProjectController::class, 'delete_project'])->name('real_project_delete');

        Route::get('edit/{project}', [realProjectController::class, 'edit'])->name('real_project_edit');

        Route::post('update/{project}', [realProjectController::class, 'update'])->name('real_project_update');
    });
    /****************************** RealUser Project *************************************************/

    /****************************** RealUser Profile *************************************************/
    Route::group(['prefix' => 'profile', 'middleware' => 'realUserAuth'], function () {
        Route::get('/', [realDashboardController::class, 'profile'])->name('real_profile');
        Route::post('update', [realDashboardController::class, 'update_profile'])->name('real_profile_update');
        Route::post('real_download_propusal/{project}', [realDashboardController::class, 'download_propusal'])->name('real_download_propusal');
        Route::post('change_pass', [realDashboardController::class, 'change_pass'])->name('real_change_pass');
    });
    /****************************** RealUser Profile *************************************************/

    /****************************** RealUser Contract *************************************************/
    Route::group(['prefix' => 'contracts', 'middleware' => 'realUserAuth'], function () {
        Route::get('/', [realContractController::class, 'index'])->name('real_contract_index');
        Route::get('add/{project_id}', [realContractController::class, 'add'])->name('real_contract_add');
        Route::post('contract_create', [realContractController::class, 'create'])->name('real_contract_create');
        Route::get('edit/{project_id}', [realContractController::class, 'edit'])->name('real_contract_edit');
        Route::post('contract_update', [realContractController::class, 'contract_update'])->name('real_project_contract_update');
        Route::get('minot/{project}', [realContractController::class, 'minot'])->name('real_contract_minot');
        Route::get('send_sign/{project}', [realContractController::class, 'send_sign'])->name('real_contract_send_sign');
        Route::get('verify_sign/{project}', [realContractController::class, 'verify_sign'])->name('real_contract_send_sign');
        Route::get('real_signed_minot/{project}', [realContractController::class, 'signed_minot'])->name('real_contract_signed_minot');
        Route::get('verify_sign/{project}', [realContractController::class, 'verify_sign'])->name('real_contract_verify_sign');
        Route::post('delete', [realContractController::class, 'delete_contract'])->name('real_contract_delete');
        Route::get('real_view/{project}', [realContractController::class, 'view'])->name('real_contract_view');

    });
    /****************************** RealUser Contract *************************************************/

    /****************************** RealUser report *************************************************/
    Route::group(['prefix' => 'reports', 'middleware' => 'realUserAuth'], function () {
        Route::get('/', [realReportController::class, 'index'])->name('real_reports_index');
        Route::post('search_index_real', [realReportController::class, 'search_index_real'])->name('real_reports_search_index');
        Route::get('detail_report/{project}', [realReportController::class, 'detail_report'])->name('real_reports_details');
        Route::post('upload_report', [realReportController::class, 'upload_report'])->name('real_reports_upload');
        Route::get('report/download/{report}', [realReportController::class, 'download_file'])->name('real_reports_download_file');
        Route::get('report/download_finance_file/{report}', [realReportController::class, 'download_finance_file'])->name('real_reports_download_finance_file');
        Route::get('finance_docs/get/{phase_id}', [realReportController::class, 'real_get_finance_docs'])->name('real_get_finance_docs');
        Route::get('finance_doc/download/{payment_id}', [realReportController::class, 'download_finance_doc'])->name('real_finance_doc_download');
    });
    /****************************** RealUser report *************************************************/

    /****************************** RealUser Ticket *************************************************/
    Route::group(['prefix' => 'tickets', 'middleware' => 'realUserAuth'], function () {
        Route::get('/{contact_id?}', [realTicketController::class, 'index'])->name('real_ticket_index');
        Route::post('send//{contact_id}', [realTicketController::class, 'send'])->name('real_ticket_send');
        Route::get('ticket/download-attachment/{ticket_id}', [realTicketController::class, 'download'])->name('real_ticket_download_ticket');
    });
    /****************************** RealUser Ticket *************************************************/

    /****************************** RealUser alerts *************************************************/
    Route::group(['prefix' => 'alerts'], function () {
        Route::get('/', [realAlertController::class, 'index'])->name('real_alerts_index');
    });
    /****************************** RealUser alerts *************************************************/

    /********** RealUser letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'realUserAuth'], function () {
        Route::get('/sent', [realUserLetterController::class, 'sent_index'])->name('real_letter_sent_index');
        Route::get('/delivered', [realUserLetterController::class, 'delivered_index'])->name('real_letter_delivered_index');
        Route::get('/new', [realUserLetterController::class, 'new'])->name('real_letter_new');
        Route::post('/signature', [realUserLetterController::class, 'signature'])->name('real_letter_signature');
        Route::post('/add', [realUserLetterController::class, 'add'])->name('real_letter_add');
        Route::get('/preview/{letter_id}', [realUserLetterController::class, 'preview'])->name('real_letter_preview');
        Route::get('/view/{letter_id}', [realUserLetterController::class, 'view'])->name('real_letter_view');
        Route::post('/final-submit/{letter_id}', [realUserLetterController::class, 'final_submit'])->name('real_letter_final_submit');
    });
    /********** RealUser letter *****************/

});
/**********************************************RealUser Dashboard******************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/*****************************************************************************************************************/
/**********************************************Expert Dashboard******************************************************/
Route::group(['prefix' => 'expert-access/dashboard', 'middleware' => 'expert'], function () {
    Route::get('/', [letter_expertController::class, 'index'])->name('expert_index');

    /********** Expert letter *****************/
    Route::group(['prefix' => 'letter', 'middleware' => 'expert'], function () {
        Route::get('/sent', [letter_expertController::class, 'sent_index'])->name('expert_letter_sent_index');
        Route::get('/delivered', [letter_expertController::class, 'delivered_index'])->name('expert_letter_delivered_index');
        Route::get('/new', [letter_expertController::class, 'new'])->name('expert_letter_new');
        Route::post('/signature', [letter_expertController::class, 'signature'])->name('expert_letter_signature');
        Route::post('/add', [letter_expertController::class, 'add'])->name('expert_letter_add');
        Route::get('/preview/{letter_id}', [letter_expertController::class, 'preview'])->name('expert_letter_preview');
        Route::get('/view/{letter_id}', [letter_expertController::class, 'view'])->name('expert_letter_view');
        Route::post('/final-submit/{letter_id}', [letter_expertController::class, 'final_submit'])->name('expert_letter_final_submit');
    });
    /********** Expert letter *****************/

    /********** Expert contract *****************/
    Route::group(['prefix' => 'contract', 'middleware' => 'expert'], function () {
        Route::get('/expertContract', [contract_expertController::class, 'index'])->name('expert_contract_index');
        Route::get('expert_signed_minot/{project}', [contract_expertController::class, 'signed_minot'])->name('expert_contract_signed_minot');
        Route::get('expert_reports/{project}', [report_expertController::class, 'index'])->name('expert_reports');
        Route::get('expert_report/{report}', [report_expertController::class, 'download_file'])->name('expert_report_download_file');
        Route::post('search_index', [contract_expertController::class, 'search_index'])->name('expert_project_in_process_Search');
        Route::get('expert_view/{project}', [contract_expertController::class, 'view'])->name('expert_contract_view');
        Route::get('download_propusal/{project}', [contract_expertController::class, 'download_propusal'])->name('expert_download_propusal');
    });
    /********** Expert contract *****************/
});
/**********************************************Expert Dashboard******************************************************/


/**********************************************Personnel Dashboard******************************************************/
Route::group(['prefix' => 'personnel-access/dashboard', 'middleware' => 'personnel'], function () {
    Route::get('/', [dashboard_personnelController::class, 'index'])->name('personnel_index');
    Route::get('leave_personnel', [leave_personnelController::class, 'index'])->name('leave_personnel_index');
    Route::get('leave_personnel_create', [leave_personnelController::class, 'create'])->name('leave_personnel_create');
    Route::post('leave_personnel_store', [leave_personnelController::class, 'store'])->name('leave_personnel_store');
    Route::get('mission_personnel', [mission_personnelController::class, 'index'])->name('mission_personnel_index');
    Route::get('mission_personnel_create', [mission_personnelController::class, 'create'])->name('mission_personnel_create');
});
/**********************************************Personnel Dashboard******************************************************/

/**********************************************deputy_plan_program Dashboard******************************************************/

Route::group(['prefix' => 'deputy-plan-program-access/dashboard', 'middleware' => 'deputy_plan_program'], function () {
    Route::get('/', [dashboard_deputy_planController::class, 'index'])->name('deputy_plan_program_index');
    Route::get('leave_deputy', [leave_deputy_planController::class, 'index'])->name('leave_deputy_index');
    Route::get('leave_deputy_confirmation', [leave_deputy_planController::class, 'confirmation'])->name('leave_deputy_confirmation');
    Route::get('leave_deputy_create', [leave_deputy_planController::class, 'create'])->name('leave_deputy_create');
    Route::post('leave_deputy_store', [leave_deputy_planController::class, 'store'])->name('leave_deputy_store');
    Route::post('deputy_leave_agreement', [leave_deputy_planController::class, 'agreement'])->name('deputy_leave_agreement');

});

/**********************************************deputy_plan_program Dashboard******************************************************/


/**********************************************support_manager Dashboard******************************************************/

Route::group(['prefix' => 'support-manager-access/dashboard', 'middleware' => 'support_manager'], function () {
    Route::get('/', [dashboardSupportManagerController::class, 'index'])->name('support_manager_index');
    Route::get('leave_support_manager', [leaveSupportManagerController::class, 'index'])->name('leave_support_manager_index');
    Route::get('leave_support_confirmation', [leaveSupportManagerController::class, 'confirmation'])->name('leave_support_confirmation');
    Route::get('leave_support_create', [leaveSupportManagerController::class, 'create'])->name('leave_support_create');
    Route::post('leave_support_store', [leaveSupportManagerController::class, 'store'])->name('leave_support_store');
    Route::post('support_leave_agreement', [leaveSupportManagerController::class, 'agreement'])->name('support_leave_agreement');

});

/**********************************************support_manager Dashboard******************************************************/


/**********************************************relations_manager Dashboard******************************************************/

Route::group(['prefix' => 'relations-manager-access/dashboard', 'middleware' => 'relations_manager'], function () {
    Route::get('/', [dashboardRelationsManagerController::class, 'index'])->name('relations_manager_index');
    Route::get('leave_relations_manager', [leaveRelationManagerController::class, 'index'])->name('leave_relations_manager_index');
    Route::get('leave_relations_manager_confirmation', [leaveRelationManagerController::class, 'confirmation'])->name('leave_relations_manager_confirmation');
    Route::get('leave_relations_manager_create', [leaveRelationManagerController::class, 'create'])->name('leave_relations_manager_create');
    Route::post('leave_relations_manager_store', [leaveRelationManagerController::class, 'store'])->name('leave_relations_manager_store');
//    Route::post('deputy_leave_agreement',[leave_deputy_planController::class,'agreement'])->name('deputy_leave_agreement');

});

/**********************************************relations_manager Dashboard******************************************************/


/**********************************************support_expert Dashboard******************************************************/

Route::group(['prefix' => 'support_expert-access/dashboard', 'middleware' => 'support_expert'], function () {
    Route::get('/', [dashboardSupport_expertController::class, 'index'])->name('support_expert_index');
//    Route::get('leave_deputy',[leave_deputy_planController::class,'index'])->name('leave_deputy_index');
//    Route::get('leave_deputy_confirmation', [leave_deputy_planController::class, 'confirmation'])->name('leave_deputy_confirmation');
//    Route::get('leave_deputy_create',[leave_deputy_planController::class,'create'])->name('leave_deputy_create');
//    Route::post('leave_deputy_store',[leave_deputy_planController::class,'store'])->name('leave_deputy_store');
//    Route::post('deputy_leave_agreement',[leave_deputy_planController::class,'agreement'])->name('deputy_leave_agreement');

});

/**********************************************support_expert Dashboard******************************************************/


/**********************************************special_expert Dashboard******************************************************/

Route::group(['prefix' => 'special_expert-access/dashboard', 'middleware' => 'special_expert'], function () {
    Route::get('/', [dashboardSpecial_expertController::class, 'index'])->name('special_expert_index');
//    Route::get('leave_deputy',[leave_deputy_planController::class,'index'])->name('leave_deputy_index');
//    Route::get('leave_deputy_confirmation', [leave_deputy_planController::class, 'confirmation'])->name('leave_deputy_confirmation');
//    Route::get('leave_deputy_create',[leave_deputy_planController::class,'create'])->name('leave_deputy_create');
//    Route::post('leave_deputy_store',[leave_deputy_planController::class,'store'])->name('leave_deputy_store');
//    Route::post('deputy_leave_agreement',[leave_deputy_planController::class,'agreement'])->name('deputy_leave_agreement');

});

/**********************************************special_expert Dashboard******************************************************/
