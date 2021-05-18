<?php

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


/*
|--------------------------------------------------------------------------
| AUTHENTICATION WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the MAIN DASHBOARD.
|
*/


Auth::routes();

Route::get('/', function () {
    //return view('welcome');
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| MAIN DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the MAIN DASHBOARD.
|
*/

Route::group([
    'namespace' => 'Main',
    'prefix' => 'main',
    'middleware' => 'auth'],
    function () {

    Route::get('/master', function () {
        return view('main.dashboard');
    });
    Route::get('/blank', function () {
        return view('main.blank');
    });

    Route::get('home', [App\Http\Controllers\Main\HomeController::class, 'index'])->name('main-home');


    //user
    Route::group([
        'prefix' => 'user'], function () {
        Route::get('list', [App\Http\Controllers\Main\UserController::class, 'index'])->name('main-user');
        Route::get('show/{id}', [App\Http\Controllers\Main\UserController::class, 'show'])->name('main-user-show');
        Route::post('store', [App\Http\Controllers\Main\UserController::class, 'store'])->name('main-user-store');
        Route::post('update/{id}', [App\Http\Controllers\Main\UserController::class, 'update'])->name('main-user-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\UserController::class, 'destroy'])->name('main-user-destroy');
        Route::post('avatar/{id}', [App\Http\Controllers\Main\UserController::class, 'updatePhoto'])->name('main-user-avatar');
        Route::get('sync/{id}', [App\Http\Controllers\Main\UserController::class, 'sync'])->name('main-user-sync');
        Route::post('change', [App\Http\Controllers\Main\UserController::class, 'changePassword'])->name('main-user-change-password');
        Route::post('change_unit', [App\Http\Controllers\Main\UserController::class, 'changeUnit'])->name('main-user-change-unit');
    });
    //user type
    Route::group([
        'prefix' => 'user-type'], function () {
        Route::get('list', [App\Http\Controllers\Main\UserTypeController::class, 'index'])->name('main-user-type');
        Route::post('store', [App\Http\Controllers\Main\UserTypeController::class, 'store'])->name('main-user-type-store');
        Route::post('update', [App\Http\Controllers\Main\UserTypeController::class, 'update'])->name('main-user-type-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\UserTypeController::class, 'destroy'])->name('main-user-type-destroy');
    });

    //eforms
    Route::group([
        'prefix' => 'eform'], function () {
        Route::get('list', [App\Http\Controllers\Main\EformController::class, 'index'])->name('main-eforms');
        Route::post('store', [App\Http\Controllers\Main\EformController::class, 'store'])->name('main-eforms-store');
        Route::post('update', [App\Http\Controllers\Main\EformController::class, 'update'])->name('main-eforms-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\EformController::class, 'destroy'])->name('main-eforms-destroy');
    });
    //eforms Category
    Route::group([
        'prefix' => 'eform_category'], function () {
        Route::get('list', [App\Http\Controllers\Main\EformCategoryController::class, 'index'])->name('main-eforms-category');
        Route::post('store', [App\Http\Controllers\Main\EformCategoryController::class, 'store'])->name('main-eforms-category-store');
        Route::post('update', [App\Http\Controllers\Main\EformCategoryController::class, 'update'])->name('main-eforms-category-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\EformCategoryController::class, 'destroy'])->name('main-eforms-category-destroy');
    });
    //status
    Route::group([
        'prefix' => 'status'], function () {
        Route::get('list', [App\Http\Controllers\Main\StatusController::class, 'index'])->name('main-status');
        Route::post('store', [App\Http\Controllers\Main\StatusController::class, 'store'])->name('main-status-store');
        Route::post('update', [App\Http\Controllers\Main\StatusController::class, 'update'])->name('main-status-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\StatusController::class, 'destroy'])->name('main-status-destroy');
    });
    //system logs
    Route::group([
        'prefix' => 'logs'], function () {
        Route::get('list', [App\Http\Controllers\Main\ActivityLogsController::class, 'index'])->name('main-logs');
        Route::get('show/{id}', [App\Http\Controllers\Main\ActivityLogsController::class, 'show'])->name('main-logs-show');
        Route::get('destroy/{id}', [App\Http\Controllers\Main\ActivityLogsController::class, 'destroy'])->name('main-logs-destroy');
    });
    //profile
    Route::group([
        'prefix' => 'profile'], function () {
        Route::get('list', [App\Http\Controllers\Main\ProfileController::class, 'index'])->name('main-profile');
        Route::post('store', [App\Http\Controllers\Main\ProfileController::class, 'store'])->name('main-profile-store');
        Route::post('update', [App\Http\Controllers\Main\ProfileController::class, 'update'])->name('main-profile-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\ProfileController::class, 'destroy'])->name('main-profile-destroy');
        Route::get('assignment', [App\Http\Controllers\Main\ProfileController::class, 'assignmentCreate'])->name('main-profile-assignment');
        Route::post('assignment/store', [App\Http\Controllers\Main\ProfileController::class, 'assignmentStore'])->name('main-profile-assignment-store');
        Route::get('delegation', [App\Http\Controllers\Main\ProfileController::class, 'delegationCreate'])->name('main-profile-delegation');
        Route::get('delegation/list', [App\Http\Controllers\Main\ProfileController::class, 'delegationList'])->name('main-profile-delegation-list');
        Route::get('delegation/show/on/behalf', [App\Http\Controllers\Main\ProfileController::class, 'delegationShowOnBehalf'])->name('main-profile-delegation-show-on-behalf');
        Route::post('delegation/store/on/behalf', [App\Http\Controllers\Main\ProfileController::class, 'delegationStoreOnBehalf'])->name('main-profile-delegation-store-on-behalf');
        Route::post('delegation/store', [App\Http\Controllers\Main\ProfileController::class, 'delegationStore'])->name('main-profile-delegation-store');
        Route::post('delegation/end/{id}', [App\Http\Controllers\Main\ProfileController::class, 'delegationEnd'])->name('main-profile-delegation-end');

    });
    //profile Permissions
    Route::group([
        'prefix' => 'profile-permissions'], function () {
        Route::get('list', [App\Http\Controllers\Main\ProfilePermissionsController::class, 'index'])->name('main-profile-permissions');
        Route::post('store', [App\Http\Controllers\Main\ProfilePermissionsController::class, 'store'])->name('main-profile-permission-store');
        Route::post('update', [App\Http\Controllers\Main\ProfilePermissionsController::class, 'update'])->name('main-profile-permission-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\ProfilePermissionsController::class, 'destroy'])->name('main-profile-permission-destroy');
    });
    //Position
    Route::group([
        'prefix' => 'position'], function () {
        Route::get('list', [App\Http\Controllers\Main\PositionController::class, 'index'])->name('main-position');
        Route::post('store', [App\Http\Controllers\Main\PositionController::class, 'store'])->name('main-position-store');
        Route::post('update', [App\Http\Controllers\Main\PositionController::class, 'update'])->name('main-position-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\PositionController::class, 'destroy'])->name('main-position-destroy');
        Route::get('sync', [App\Http\Controllers\Main\PositionController::class, 'sync'])->name('main-position-sync');
    });
    //User Unit
    Route::group([
        'prefix' => 'user-unit'], function () {
        Route::get('list', [App\Http\Controllers\Main\UserUnitController::class, 'index'])->name('main-user_unit');
        Route::post('store', [App\Http\Controllers\Main\UserUnitController::class, 'store'])->name('main-user_unit-store');
        Route::post('update', [App\Http\Controllers\Main\UserUnitController::class, 'update'])->name('main-user_unit-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\UserUnitController::class, 'destroy'])->name('main-user_unit-destroy');
        Route::get('sync', [App\Http\Controllers\Main\UserUnitController::class, 'sync'])->name('main-user_unit-sync');
    });
    //Directorate
    Route::group([
        'prefix' => 'directorate'], function () {
        Route::get('list', [App\Http\Controllers\Main\DirectoratesController::class, 'index'])->name('main-directorate');
        Route::post('store', [App\Http\Controllers\Main\DirectoratesController::class, 'store'])->name('main-directorate-store');
        Route::post('update', [App\Http\Controllers\Main\DirectoratesController::class, 'update'])->name('main-directorate-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\DirectoratesController::class, 'destroy'])->name('main-directorate-destroy');
        Route::get('sync', [App\Http\Controllers\Main\DirectoratesController::class, 'sync'])->name('main-directorate-sync');
    });
    //region
    Route::group([
        'prefix' => 'region'], function () {
        Route::get('list', [App\Http\Controllers\Main\RegionsController::class, 'index'])->name('main-region');
        Route::post('store', [App\Http\Controllers\Main\RegionsController::class, 'store'])->name('main-region-store');
        Route::post('update', [App\Http\Controllers\Main\RegionsController::class, 'update'])->name('main-region-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\RegionsController::class, 'destroy'])->name('main-region-destroy');
    });
    //grade
    Route::group([
        'prefix' => 'grade'], function () {
        Route::get('list', [App\Http\Controllers\Main\GradesController::class, 'index'])->name('main-grade');
        Route::post('store', [App\Http\Controllers\Main\GradesController::class, 'store'])->name('main-grade-store');
        Route::post('update', [App\Http\Controllers\Main\GradesController::class, 'update'])->name('main-grade-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\GradesController::class, 'destroy'])->name('main-grade-destroy');
        Route::get('sync', [App\Http\Controllers\Main\GradesController::class, 'sync'])->name('main-grade-sync');
    });
    //grade category
    Route::group([
        'prefix' => 'grade_category'], function () {
        Route::get('list', [App\Http\Controllers\Main\GradesCategoryController::class, 'index'])->name('main-grade-category');
        Route::post('store', [App\Http\Controllers\Main\GradesCategoryController::class, 'store'])->name('main-grade-category-store');
        Route::post('update', [App\Http\Controllers\Main\GradesCategoryController::class, 'update'])->name('main-grade-category-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\GradesCategoryController::class, 'destroy'])->name('main-grade-category-destroy');
        Route::get('sync', [App\Http\Controllers\Main\GradesCategoryController::class, 'sync'])->name('main-grade-category-sync');
    });
    //project
    Route::group([
        'prefix' => 'project'], function () {
        Route::get('list', [App\Http\Controllers\Main\ProjectsController::class, 'index'])->name('main-project');
        Route::post('store', [App\Http\Controllers\Main\ProjectsController::class, 'store'])->name('main-project-store');
        Route::post('update', [App\Http\Controllers\Main\ProjectsController::class, 'update'])->name('main-project-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\ProjectsController::class, 'destroy'])->name('main-project-destroy');
    });
    //account
    Route::group([
        'prefix' => 'account'], function () {
        Route::get('list', [App\Http\Controllers\Main\AccountsChartsController::class, 'index'])->name('main-account');
        Route::post('store', [App\Http\Controllers\Main\AccountsChartsController::class, 'store'])->name('main-account-store');
        Route::post('update', [App\Http\Controllers\Main\AccountsChartsController::class, 'update'])->name('main-account-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\AccountsChartsController::class, 'destroy'])->name('main-account-destroy');
    });
    //department
    Route::group([
        'prefix' => 'department'], function () {
        Route::get('list', [App\Http\Controllers\Main\DepartmentController::class, 'index'])->name('main-department');
        Route::post('store', [App\Http\Controllers\Main\DepartmentController::class, 'store'])->name('main-department-store');
        Route::post('update', [App\Http\Controllers\Main\DepartmentController::class, 'update'])->name('main-department-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\DepartmentController::class, 'destroy'])->name('main-department-destroy');
        Route::get('sync', [App\Http\Controllers\Main\DepartmentController::class, 'sync'])->name('main-department-sync');
    });
    //divisional_user_unit
    Route::group([
        'prefix' => 'divisional_user_unit'], function () {
        Route::get('list', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'index'])->name('main-divisional-user-unit');
        Route::post('store', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'store'])->name('main-divisional-user-unit-store');
        Route::post('update', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'update'])->name('main-divisional-user-unit-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'destroy'])->name('main-divisional-user-unit-destroy');
        Route::get('sync', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'sync'])->name('main-divisional-user-unit-sync');
    });
    //division
    Route::group([
        'prefix' => 'division'], function () {
        Route::get('list', [App\Http\Controllers\Main\DivisionsController::class, 'index'])->name('main-division');
        Route::post('store', [App\Http\Controllers\Main\DivisionsController::class, 'store'])->name('main-division-store');
        Route::post('update', [App\Http\Controllers\Main\DivisionsController::class, 'update'])->name('main-division-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\DivisionsController::class, 'destroy'])->name('main-division-destroy');
        Route::get('sync', [App\Http\Controllers\Main\DivisionsController::class, 'sync'])->name('main-division-sync');
    });
    //functional unit
    Route::group([
        'prefix' => 'functional_unit'], function () {
        Route::get('list', [App\Http\Controllers\Main\FunctionalUnitController::class, 'index'])->name('main-functional-unit');
        Route::post('store', [App\Http\Controllers\Main\FunctionalUnitController::class, 'store'])->name('main-functional-unit-store');
        Route::post('update', [App\Http\Controllers\Main\FunctionalUnitController::class, 'update'])->name('main-functional-unit-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\FunctionalUnitController::class, 'destroy'])->name('main-functional-unit-destroy');
        Route::get('sync', [App\Http\Controllers\Main\FunctionalUnitController::class, 'sync'])->name('main-functional-unit-sync');
    });

    //paypoint
    Route::group([
        'prefix' => 'pay_point'], function () {
        Route::get('list', [App\Http\Controllers\Main\PayPointController::class, 'index'])->name('main-pay-point');
        Route::post('store', [App\Http\Controllers\Main\PayPointController::class, 'store'])->name('main-pay-point-store');
        Route::post('update', [App\Http\Controllers\Main\PayPointController::class, 'update'])->name('main-pay-point-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\PayPointController::class, 'destroy'])->name('main-pay-point-destroy');
        Route::get('sync', [App\Http\Controllers\Main\PayPointController::class, 'sync'])->name('main-pay-point-sync');
    });

    //location
    Route::group([
        'prefix' => 'location'], function () {
        Route::get('list', [App\Http\Controllers\Main\LocationController::class, 'index'])->name('main-location');
        Route::post('store', [App\Http\Controllers\Main\LocationController::class, 'store'])->name('main-location-store');
        Route::post('update', [App\Http\Controllers\Main\LocationController::class, 'update'])->name('main-location-update');
        Route::post('destroy/{id}', [App\Http\Controllers\Main\LocationController::class, 'destroy'])->name('main-location-destroy');
        Route::get('sync', [App\Http\Controllers\Main\LocationController::class, 'sync'])->name('main-location-sync');
    });

        //totals
        Route::group([
            'prefix' => 'totals'], function () {
            Route::get('list', [App\Http\Controllers\Main\TotalsController::class, 'index'])->name('main-totals');
            Route::post('store', [App\Http\Controllers\Main\TotalsController::class, 'store'])->name('main-totals-store');
            Route::post('update', [App\Http\Controllers\Main\TotalsController::class, 'update'])->name('main-totals-update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\TotalsController::class, 'destroy'])->name('main-totals-destroy');
            Route::get('sync', [App\Http\Controllers\Main\TotalsController::class, 'sync'])->name('main-totals-sync');
        });


        //Files
        Route::group([
            'prefix' => 'files'], function () {
            Route::post('change', [App\Http\Controllers\HomeController::class, 'changeFile'])->name('attached-file-change');
            Route::post('add', [App\Http\Controllers\HomeController::class, 'addFile'])->name('attached-file-add');
        });




    });


/*
|--------------------------------------------------------------------------
| PETTY-CASH DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the PETTY-CASH DASHBOARD.
|
*/


Route::group([
    'namespace' => 'petty_cash',
    'prefix' => 'petty_cash',
    'middleware' => 'auth'],
    function () {

    Route::get('/master', function () {
        return view('main.dashboard');
    });
    Route::get('/blank', function () {
        return view('main.blank');
    });

    //petty cah routes
    Route::get('home', [App\Http\Controllers\EForms\PettyCash\HomeController::class, 'index'])->name('petty-cash-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'index'])->name('petty-cash-list');
    Route::get('create', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'create'])->name('petty-cash-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'show'])->name('petty-cash-show');
    Route::post('store', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'store'])->name('petty-cash-store');
    Route::post('approve', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'approve'])->name('petty-cash-approve');
    Route::post('update', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'update'])->name('petty-cash-update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'destroy'])->name('petty-cash-destroy');
    Route::get('reports/{value}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reports'])->name('petty-cash-report');
    Route::get('reportExport', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reportsExport'])->name('petty-cash-report-export');
    Route::get('reportSync', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reportsSync'])->name('petty-cash-report-sync');
    Route::post('reportExportUnmarkExported/{value}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reportsExportUnmarkExported'])->name('petty-cash-report-export-unmark-exported');
    Route::get('reportExportUnmarkExportedAll', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reportsExportUnmarkExportedAll'])->name('petty-cash-report-export-unmark-exported-all');
    Route::get('sync/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'sync'])->name('petty-cash-sync');
    Route::get('records/{value}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'records'])->name('petty-cash-record');
    Route::post('void/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'void'])->name('petty-cash-void');
    Route::post('reverse/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reverse'])->name('petty-cash-reverse');
    Route::post('search', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'search'])->name('petty-cash-search');

    Route::get('charts', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'charts'])->name('petty-cash-charts');
    Route::get('removeDuplicateAccountLines/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'markAccountLinesAsDuplicates'])->name('petty-cash-accounts-duplicate-remove');
    Route::get('showForm/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'showForm'])->name('petty-cash-reports-show');


    //REPORTS
    Route::group([
        'prefix' => 'report'
    ], function () {
        Route::get('directorates', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'index'])->name('petty-cash-reports-index');
        Route::get('syncDirectorates', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'syncDirectorates'])->name('petty-cash-reports-sync-directorates');
        Route::get('syncUserUnits', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'syncUserUnits'])->name('petty-cash-reports-sync-units');

    });
    Route::group([
        'prefix' => 'filtered/report'
    ], function () {
        Route::get('index', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'filteredReports'])->name('petty.cash.filtered.report');
        Route::get('get/{unit}/{status}/{start_date}/{end_date}', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'getFilteredReports'])->name('petty.cash.filtered.get');
    });


});






/*
|--------------------------------------------------------------------------
|GIFTS BENEFITS DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the GIFTS BENEFITS DASHBOARD.
|
*/


Route::group([
    'namespace' => 'gifts_benefits',
    'prefix' => 'gifts_benefits',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\GiftBenefitsRegister\HomeController::class, 'index'])->name('gifts-benefits-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'index'])->name('gifts-benefits-list');
    Route::get('create', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'create'])->name('gifts-benefits-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'show'])->name('gifts-benefits-show');
    Route::post('store', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'store'])->name('gifts-benefits-store');
    Route::post('approve', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'approve'])->name('gifts-benefits-approve');
    Route::post('update', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'update'])->name('gifts-benefits-update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'destroy'])->name('gifts-benefits-destroy');
    Route::get('reports', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'reports'])->name('gifts-benefits-report');
    Route::get('reportExport', [App\Http\Controllers\EForms\GiftBenefitsRegister\GiftBenefitsRegisterController::class, 'reportsExport'])->name('gifts-benefits-report-export');

});






/*
|--------------------------------------------------------------------------
|GIFTS DECLARATION DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the GIFTS DECLARATION DASHBOARD.
|
*/


Route::group([
    'namespace' => 'gifts_declaration',
    'prefix' => 'gifts_declaration',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\GiftDeclaration\HomeController::class, 'index'])->name('gifts-declaration-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'index'])->name('gifts-declaration-list');
    Route::get('create', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'create'])->name('gifts-declaration-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'show'])->name('gifts-declaration-show');
    Route::post('store', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'store'])->name('gifts-declaration-store');
    Route::post('approve', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'approve'])->name('gifts-declaration-approve');
    Route::post('update', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'update'])->name('gifts-declaration-update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'destroy'])->name('gifts-declaration-destroy');
    Route::get('reports', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reports'])->name('gifts-declaration-report');
    Route::get('reportExport', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reportsExport'])->name('gifts-declaration-report-export');

});


Route::group([
    'namespace' => 'vehicle_requisitioning_home',
    'prefix' => 'vehicle_requisitioning_home',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\GiftDeclaration\HomeController::class, 'index'])->name('vehicle-requisitioning-home');
//    Route::get('list/{value}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'index'])->name('gifts-declaration-list');
//    Route::get('create', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'create'])->name('gifts-declaration-create');
//    Route::post('show/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'show'])->name('gifts-declaration-show');
//    Route::post('store', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'store'])->name('gifts-declaration-store');
//    Route::post('approve', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'approve'])->name('gifts-declaration-approve');
//    Route::post('update', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'update'])->name('gifts-declaration-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'destroy'])->name('gifts-declaration-destroy');
//    Route::get('reports', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reports'])->name('gifts-declaration-report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reportsExport'])->name('gifts-declaration-report-export');

});

Route::group([
    'namespace' => 'hotel_accommodation_home',
    'prefix' => 'hotel/accommodation/home',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\GiftDeclaration\HomeController::class, 'index'])->name('hotel.accommodation.home');
//    Route::get('list/{value}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'index'])->name('gifts-declaration-list');
//    Route::get('create', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'create'])->name('gifts-declaration-create');
//    Route::post('show/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'show'])->name('gifts-declaration-show');
//    Route::post('store', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'store'])->name('gifts-declaration-store');
//    Route::post('approve', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'approve'])->name('gifts-declaration-approve');
//    Route::post('update', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'update'])->name('gifts-declaration-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'destroy'])->name('gifts-declaration-destroy');
//    Route::get('reports', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reports'])->name('gifts-declaration-report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reportsExport'])->name('gifts-declaration-report-export');

});


Route::group([
    'namespace' => 'salary',
    'prefix' => 'salary/advance',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\GiftDeclaration\HomeController::class, 'index'])->name('salary-advance-home');
//    Route::get('list/{value}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'index'])->name('gifts-declaration-list');
//    Route::get('create', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'create'])->name('gifts-declaration-create');
//    Route::post('show/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'show'])->name('gifts-declaration-show');
//    Route::post('store', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'store'])->name('gifts-declaration-store');
//    Route::post('approve', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'approve'])->name('gifts-declaration-approve');
//    Route::post('update', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'update'])->name('gifts-declaration-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'destroy'])->name('gifts-declaration-destroy');
//    Route::get('reports', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reports'])->name('gifts-declaration-report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reportsExport'])->name('gifts-declaration-report-export');

});









/*
|--------------------------------------------------------------------------
|SUBSISTENCE DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the SUBSISTENCE DASHBOARD.
|
*/

//
//Route::group([
//    'namespace' => 'subsistence',
//    'prefix' => 'subsistence',
//    'middleware' => 'auth'],
//    function () {
//    Route::get('home', [App\Http\Controllers\EForms\Subsistence1\HomeController::class, 'index'])->name('subsistence-home');
//    Route::get('list/{value}', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'index'])->name('subsistence-list');
//    Route::get('create', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'create'])->name('subsistence-create');
//    Route::post('show/{id}', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'show'])->name('subsistence-show');
//    Route::post('store', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'store'])->name('subsistence-store');
//    Route::post('approve', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'approve'])->name('subsistence-approve');
//    Route::post('update', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'update'])->name('subsistence-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'destroy'])->name('subsistence-destroy');
//    Route::get('reports', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'reports'])->name('subsistence-report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'reportsExport'])->name('subsistence-report-export');
//    Route::get('records/{value}', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'records'])->name('subsistence-record');
//    Route::post('void/{id}', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'void'])->name('subsistence-void');
//
//    Route::get('charts', [App\Http\Controllers\EForms\Subsistence1\SubsistenceController::class, 'charts'])->name('subsistence-charts');
//
//});



Route::group([
    'namespace' => 'subsistence',
    'prefix' => 'subsistence',
    'middleware' => 'auth'],
    function () {

        //subsistence routes
        Route::get('home', [App\Http\Controllers\EForms\Subsistence\HomeController::class, 'index'])->name('subsistence.home');
        Route::get('list/{value}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'index'])->name('subsistence.list');
        Route::get('create', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'create'])->name('subsistence.create');
        Route::post('show/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'show'])->name('subsistence.show');
        Route::post('store', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'store'])->name('subsistence.store');
        Route::post('approve', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'approve'])->name('subsistence.approve');
        Route::post('update', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'update'])->name('subsistence.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'destroy'])->name('subsistence.destroy');
        Route::get('reports/{value}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reports'])->name('subsistence.report');
        Route::get('reportExport', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsExport'])->name('subsistence.report.export');
        Route::get('reportSync', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsSync'])->name('subsistence.report.sync');
        Route::post('reportExportUnmarkExported/{value}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsExportUnmarkExported'])->name('subsistence.report.unmark.exported');
        Route::get('reportExportUnmarkExportedAll', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsExportUnmarkExportedAll'])->name('subsistence.report.export.unmark.exported.all');
        Route::get('sync/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'sync'])->name('subsistence.sync');
        Route::get('records/{value}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'records'])->name('subsistence.record');
        Route::post('void/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'void'])->name('subsistence.void');
        Route::post('reverse/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reverse'])->name('subsistence.reverse');
        Route::post('search', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'search'])->name('subsistence.search');

        Route::get('charts', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'charts'])->name('subsistence.charts');
        Route::get('removeDuplicateAccountLines/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'markAccountLinesAsDuplicates'])->name('subsistence.accounts.duplicate-remove');
        Route::get('showForm/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'showForm'])->name('subsistence.reports.show');


        //REPORTS
        Route::group([
            'prefix' => 'report'
        ], function () {
            Route::get('directorates', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'index'])->name('subsistence.reports.index');
            Route::get('syncDirectorates', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'syncDirectorates'])->name('subsistence.reports.sync.directorates');
            Route::get('syncUserUnits', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'syncUserUnits'])->name('subsistence.reports.sync.units');

        });
        Route::group([
            'prefix' => 'filtered/report'
        ], function () {
            Route::get('index', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'filteredReports'])->name('subsistence.filtered.report');
            Route::get('get/{unit}/{status}/{start_date}/{end_date}', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'getFilteredReports'])->name('subsistence.filtered.get');
        });


    });







/*
|--------------------------------------------------------------------------
|SUBSISTENCE DIRECTORS DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the SUBSISTENCE DIRECTORS DASHBOARD.
|
*/


Route::group([
    'namespace' => 'subsistence_directors',
    'prefix' => 'subsistence_directors',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\SubsistenceDirectors\HomeController::class, 'index'])->name('subsistence-directors-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'index'])->name('subsistence-directors-list');
    Route::get('create', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'create'])->name('subsistence-directors-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'show'])->name('subsistence-directors-show');
    Route::post('store', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'store'])->name('subsistence-directors-store');
    Route::post('approve', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'approve'])->name('subsistence-directors-approve');
    Route::post('update', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'update'])->name('subsistence-directors-update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'destroy'])->name('subsistence-directors-destroy');
    Route::get('reports', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'reports'])->name('subsistence-directors-report');
    Route::get('reportExport', [App\Http\Controllers\EForms\SubsistenceDirectors\SubsistenceDirectorsController::class, 'reportsExport'])->name('subsistence-directors-report-export');

});






/*
|--------------------------------------------------------------------------
|TEMPS AND CASUALS DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the TEMPS AND CASUALS DASHBOARD.
|
*/


Route::group([
    'namespace' => 'temps_casuals',
    'prefix' => 'temps_casuals',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\TempsCasuals\HomeController::class, 'index'])->name('temps-casuals-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'index'])->name('temps-casuals-list');
    Route::get('create', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'create'])->name('temps-casuals-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'show'])->name('temps-casuals-show');
    Route::post('store', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'store'])->name('temps-casuals-store');
    Route::post('approve', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'approve'])->name('temps-casuals-approve');
    Route::post('update', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'update'])->name('temps-casuals-update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'destroy'])->name('temps-casuals-destroy');
    Route::get('reports', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'reports'])->name('temps-casuals-report');
    Route::get('reportExport', [App\Http\Controllers\EForms\TempsCasuals\TempsCasualsController::class, 'reportsExport'])->name('temps-casuals-report-export');

});






/*
|--------------------------------------------------------------------------
|USER RECORDS REQUESTS DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the USER RECORDS REQUESTS DASHBOARD.
|
*/


Route::group([
    'namespace' => 'user_records_request',
    'prefix' => 'user_records_request',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\UserRecordsRequest\HomeController::class, 'index'])->name('user-records-request-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'index'])->name('user-records-request-list');
    Route::get('create', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'create'])->name('user-records-request-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'show'])->name('user-records-request-show');
    Route::post('store', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'store'])->name('user-records-request-store');
    Route::post('approve', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'approve'])->name('user-records-request-approve');
    Route::post('update', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'update'])->name('user-records-request-update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'destroy'])->name('user-records-request-destroy');
    Route::get('reports', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'reports'])->name('user-records-request-report');
    Route::get('reportExport', [App\Http\Controllers\EForms\UserRecordsRequest\UserRecordsRequestController::class, 'reportsExport'])->name('user-records-request-report-export');

});







/*
|--------------------------------------------------------------------------
| HOTEL-ACCOMMODATION DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the HOTEL-ACCOMMODATION DASHBOARD.
|
*/


/*
|--------------------------------------------------------------------------
| KILOMETER-ALLOWANCE DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the KILOMETER-ALLOWANCE DASHBOARD.
|
*/
Route::group([
    'namespace' => 'kilometer_allowance',
    'prefix' => 'kilometer_allowance',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\KilometerAllowance\HomeController::class, 'index'])->name('kilometer-allowance-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'index'])->name('kilometer-allowance-list');
    Route::get('create', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'create'])->name('kilometer-allowance-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'show'])->name('kilometer-allowance-show');
    Route::post('store', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'store'])->name('kilometer-allowance-store');
    Route::post('approve', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'approve'])->name('kilometer-allowance-approve');
//    Route::post('update', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'update'])->name('kilometer-allowance-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'destroy'])->name('kilometer-allowance-destroy');
//    Route::get('reports', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reports'])->name('kilometer-allowance-report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reportsExport'])->name('kilometer-allowance-report-export');
//
//    Route::get('charts', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'charts'])->name('kilometer-allowance-charts');

});




/*
|--------------------------------------------------------------------------
| VIREMENT DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the KILOMETER-ALLOWANCE DASHBOARD.
|
*/
Route::group([
    'namespace' => 'virement',
    'prefix' => 'virement',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\Virement\HomeController::class, 'index'])->name('virement-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\Virement\VirementController::class, 'index'])->name('virement-list');
    Route::get('create', [App\Http\Controllers\EForms\Virement\VirementController::class, 'create'])->name('virement-create');
//    Route::post('show/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'show'])->name('virement-show');
    Route::post('store', [App\Http\Controllers\EForms\Virement\VirementController::class, 'store'])->name('virement-store');
//    Route::post('approve', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'approve'])->name('virement-approve');
//    Route::post('update', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'update'])->name('viremenvirementt-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'destroy'])->name('virement-destroy');
    Route::get('reports', [App\Http\Controllers\EForms\Virement\VirementController::class, 'reports'])->name('virement-report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reportsExport'])->name('virement-report-export');
//
//    Route::get('charts', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'charts'])->name('virement-charts');

});


/*
|--------------------------------------------------------------------------
| DATA CENTER CRITICAL ASSETS REGISTER DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the DATA CENTER CRITICAL ASSETS REGISTER DASHBOARD.
|
*/
Route::group([
    'namespace' => 'datacenter_ca',
    'prefix' => 'datacenter_ca',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\DataCenterCA\HomeController::class, 'index'])->name('datacenter-ca-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'index'])->name('datacenter-ca-list');
    Route::get('create', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'create'])->name('datacenter-ca-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'show'])->name('datacenter-ca-show');
    Route::post('store', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'store'])->name('datacenter-ca-store');
//    Route::post('approve', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'approve'])->name('datacenter-ca-approve');
//    Route::post('update', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'update'])->name('datacenter-ca-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'destroy'])->name('datacenter-ca-destroy');
    Route::get('reports', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'reports'])->name('datacenter-ca-report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'reportsExport'])->name('datacenter-ca-report-export');
//
//    Route::get('charts', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'charts'])->name('datacenter-ca-charts');

});



/*
|--------------------------------------------------------------------------
| TRIP FORM DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the PETTY-CASH DASHBOARD.
|
*/

Route::group([
    'namespace' => 'trip',
    'prefix' => 'trip',
    'middleware' => 'auth'], function () {

    //petty cah routes
    Route::get('home', [App\Http\Controllers\EForms\Trip\HomeController::class, 'index'])->name('trip-home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\Trip\TripController::class, 'index'])->name('trip-list');
    Route::get('create', [App\Http\Controllers\EForms\Trip\TripController::class, 'create'])->name('trip-create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\Trip\TripController::class, 'show'])->name('trip-show');
    Route::post('store', [App\Http\Controllers\EForms\Trip\TripController::class, 'store'])->name('trip-store');
    Route::post('approve', [App\Http\Controllers\EForms\Trip\TripController::class, 'approve'])->name('trip-approve');
    Route::post('update', [App\Http\Controllers\EForms\Trip\TripController::class, 'update'])->name('trip-update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\Trip\TripController::class, 'destroy'])->name('trip-destroy');
    Route::get('reports', [App\Http\Controllers\EForms\Trip\TripController::class, 'reports'])->name('trip-report');
    Route::get('reportExport', [App\Http\Controllers\EForms\Trip\TripController::class, 'reportsExport'])->name('trip-report-export');

    Route::get('records/{value}', [App\Http\Controllers\EForms\Trip\TripController::class, 'records'])->name('trip-record');
    Route::post('void/{id}', [App\Http\Controllers\EForms\Trip\TripController::class, 'void'])->name('trip-void');

    Route::get('charts', [App\Http\Controllers\EForms\Trip\TripController::class, 'charts'])->name('trip-charts');

});



//config_work_flow
Route::group([
    'prefix' => 'work_flow'], function () {
    //sync all
    Route::get('sync', [App\Http\Controllers\Main\ConfigWorkFlowController::class, 'syncFromConfigUserUnits'])->name('workflow.sync');

    //config_work_flow for petty cash
    Route::group([
        'prefix' => 'petty_cash'], function () {
        Route::get('list', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'index'])->name('petty.cash.workflow');
        Route::post('store', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'store'])->name('petty.cash.workflow.store');
        Route::post('update/{code}', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'update'])->name('petty.cash.workflow.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'destroy'])->name('petty.cash.workflow.destroy');
        Route::post('search', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'search'])->name('petty.cash.workflow.search');
        Route::get('show/{id}/{code}', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'show'])->name('petty.cash.workflow.show');
        Route::get('sync', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'sync'])->name('petty.cash.workflow.sync');
    });

    //config_work_flow for accommodation
    Route::group([
        'prefix' => 'hotel/accommodation'], function () {
        Route::get('list', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'index'])->name('hotel.accommodation.workflow');
        Route::post('store', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'store'])->name('hotel.accommodation.workflow.store');
        Route::post('update/{code}', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'update'])->name('hotel.accommodation.workflow.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'destroy'])->name('hotel.accommodation.workflow.destroy');
        Route::post('search', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'search'])->name('hotel.accommodation.workflow.search');
        Route::get('show/{id}/{code}', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'show'])->name('hotel.accommodation.show');
        Route::get('sync', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'sync'])->name('hotel.accommodation.workflow.sync');
    });



    //config_work_flow for subsistence
    Route::group([
        'prefix' => 'subsistence'], function () {
        Route::get('list', [App\Http\Controllers\EForms\Subsistence1\WorkflowController::class, 'index'])->name('subsistence.workflow');
        Route::post('store', [App\Http\Controllers\EForms\Subsistence1\WorkFlowController::class, 'store'])->name('subsistence.workflow.store');
        Route::post('update/{code}', [App\Http\Controllers\EForms\Subsistence1\WorkFlowController::class, 'update'])->name('subsistence.workflow.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\Subsistence1\WorkFlowController::class, 'destroy'])->name('subsistence.workflow.destroy');
        Route::get('sync', [App\Http\Controllers\EForms\Subsistence1\WorkFlowController::class, 'sync'])->name('subsistence.workflow.sync');
        Route::get('show/{id}/{code}',  [App\Http\Controllers\EForms\Subsistence1\WorkFlowController::class, 'show'])->name('subsistence.workflow.show');
    });


});

/*
|--------------------------------------------------------------------------
|HOTEL ACCOMMODATION DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the DATA CENTER CRITICAL ASSETS REGISTER DASHBOARD.
|
*/



/*
|--------------------------------------------------------------------------
| HOTEL-ACCOMMODATION DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the HOTEL-ACCOMMODATION DASHBOARD.
|
*/



//Route::group([
//    'namespace' => 'hotel_accommodation',
//    'prefix' => 'hotel/accommodation',
//    'middleware' => 'auth'], function () {
//
//    Route::get('home', [App\Http\Controllers\EForms\HotelAccommodation\HomeController::class, 'index'])->name('hotel.accommodation.home');
//    Route::get('list/{value}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'index'])->name('hotel.accommodation.list');
//    Route::get('create', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class,'create'])->name('hotel.accommodation.create');
//    Route::post('show/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'show'])->name('hotel.accommodation.show');
//    Route::post('store', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'store'])->name('hotel.accommodation.store');
//    Route::post('approve/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'approve'])->name('hotel.accommodation.approve');
////    Route::post('update', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'update'])->name('hotel.accommodation.update');
////    Route::post('destroy/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'destroy'])->name('hotel.accommodation.destroy');
//    Route::get('reports', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reports'])->name('hotel.accommodation.report');
////    Route::get('reportExport', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reportsExport'])->name('hotel.accommodation.report.export');
////
////    Route::get('charts', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'charts'])->name('hotel.accommodation.charts');
//
//});



Route::group([
    'namespace' => 'hotel_accommodation',
    'prefix' => 'hotel/accommodation',
    'middleware' => 'auth'],
    function () {

        //petty cash routes
        Route::get('home', [App\Http\Controllers\EForms\HotelAccommodation\HomeController::class, 'index'])->name('hotel.accommodation.home');
        Route::get('list/{value}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'index'])->name('hotel.accommodation.list');
        Route::get('create', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'create'])->name('hotel.accommodation.create');
        Route::post('show/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'show'])->name('hotel.accommodation.show');
        Route::post('store', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'store'])->name('hotel.accommodation.store');
        Route::post('approve', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'approve'])->name('hotel.accommodation.approve');
        Route::post('update', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'update'])->name('hotel.accommodation.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'destroy'])->name('hotel.accommodation.destroy');
        Route::get('reports/{value}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reports'])->name('hotel.accommodation.report');
        Route::get('reportExport', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reportsExport'])->name('hotel.accommodation.report.export');
        Route::get('reportSync', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reportsSync'])->name('hotel.accommodation.-report-sync');
        Route::post('reportExportUnmarkExported/{value}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reportsExportUnmarkExported'])->name('hotel.accommodation.report.export.unmark.exported');
        Route::get('reportExportUnmarkExportedAll', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reportsExportUnmarkExportedAll'])->name('hotel.accommodation.report.export.unmark.exported.all');
        Route::get('sync/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'sync'])->name('hotel.accommodation.sync');
        Route::get('records/{value}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'records'])->name('hotel.accommodation.record');
        Route::post('void/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'void'])->name('hotel.accommodation.void');
        Route::post('reverse/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reverse'])->name('hotel.accommodation.reverse');
        Route::post('search', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'search'])->name('hotel.accommodation.search');

        Route::get('charts', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'charts'])->name('hotel.accommodation.charts');
        Route::get('removeDuplicateAccountLines/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'markAccountLinesAsDuplicates'])->name('hotel.accommodation.accounts.duplicate.remove');
        Route::get('showForm/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'showForm'])->name('hotel.accommodation.reports.show');

        //REPORTS
        Route::group([
            'prefix' => 'report'
        ], function () {
            Route::get('directorates', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'index'])->name('hotel.accommodation.reports.index');
            Route::get('syncDirectorates', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'syncDirectorates'])->name('hotel.accommodation.reports.sync.directorates');
            Route::get('syncUserUnits', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'syncUserUnits'])->name('hotel.accommodation.reports.sync.units');

        });

        Route::group([
            'prefix' => 'filtered/report'
        ], function () {
            Route::get('index', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'filteredReports'])->name('hotel.accommodation.filtered.report');
            Route::get('get/{unit}/{status}/{start_date}/{end_date}', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'getFilteredReports'])->name('hotel.accommodation.filtered.get');
        });


    });



