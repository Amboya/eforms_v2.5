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
    'namespace' => 'main',
    'prefix' => 'main',
    'middleware' => 'auth'],
    function () {

        Route::get('home_new', [App\Http\Controllers\Main\HomeController::class, 'index'])->name('main-home');
        Route::get('home', [App\Http\Controllers\Main\HomeController::class, 'index'])->name('main.home');
        Route::get('back', [App\Http\Controllers\Main\HomeController::class, 'back'])->name('main.back');

        //user
        Route::group([
            'prefix' => 'user'], function () {

            Route::get('list/all', [App\Http\Controllers\Main\UserController::class, 'list']);
            Route::get('list', [App\Http\Controllers\Main\UserController::class, 'index'])->name('main.user');
            Route::get('show/{id}', [App\Http\Controllers\Main\UserController::class, 'show'])->name('main.user.show');
            Route::post('search', [App\Http\Controllers\Main\UserController::class, 'search'])->name('main.user.search');
            Route::post('store', [App\Http\Controllers\Main\UserController::class, 'store'])->name('main.user.store');
            Route::post('update/{id}', [App\Http\Controllers\Main\UserController::class, 'update'])->name('main.user.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\UserController::class, 'destroy'])->name('main.user.destroy');
            Route::post('avatar/{id}', [App\Http\Controllers\Main\UserController::class, 'updatePhoto'])->name('main.user.avatar');
            Route::get('sync/{id}', [App\Http\Controllers\Main\UserController::class, 'sync'])->name('main.user.sync');
            Route::post('change', [App\Http\Controllers\Main\UserController::class, 'changePassword'])->name('main.user.change.password');
            Route::post('reset/{user}', [App\Http\Controllers\Main\UserController::class, 'resetPassword'])->name('main.user.reset.password');
            Route::post('change_unit', [App\Http\Controllers\Main\UserController::class, 'changeUnit'])->name('main.user.change.unit');
        });


        //user type
        Route::group([
            'prefix' => 'user/type'], function () {
            Route::get('list', [App\Http\Controllers\Main\UserTypeController::class, 'index'])->name('main.user.type');
            Route::post('store', [App\Http\Controllers\Main\UserTypeController::class, 'store'])->name('main.user.type.store');
            Route::post('update', [App\Http\Controllers\Main\UserTypeController::class, 'update'])->name('main.user.type.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\UserTypeController::class, 'destroy'])->name('main.user.type.destroy');
        });

        //eforms
        Route::group([
            'prefix' => 'eform'], function () {
            Route::get('list', [App\Http\Controllers\Main\EformController::class, 'index'])->name('main.eforms');
            Route::post('store', [App\Http\Controllers\Main\EformController::class, 'store'])->name('main.eforms.store');
            Route::post('update', [App\Http\Controllers\Main\EformController::class, 'update'])->name('main.eforms.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\EformController::class, 'destroy'])->name('main.eforms.destroy');
        });
        //eforms Category
        Route::group([
            'prefix' => 'eform/category'], function () {
            Route::get('list', [App\Http\Controllers\Main\EformCategoryController::class, 'index'])->name('main.eforms.category');
            Route::post('store', [App\Http\Controllers\Main\EformCategoryController::class, 'store'])->name('main.eforms.category.store');
            Route::post('update', [App\Http\Controllers\Main\EformCategoryController::class, 'update'])->name('main.eforms.category.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\EformCategoryController::class, 'destroy'])->name('main.eforms.category.destroy');
        });
        //status
        Route::group([
            'prefix' => 'status'], function () {
            Route::get('list', [App\Http\Controllers\Main\StatusController::class, 'index'])->name('main.status');
            Route::post('store', [App\Http\Controllers\Main\StatusController::class, 'store'])->name('main.status.store');
            Route::post('update', [App\Http\Controllers\Main\StatusController::class, 'update'])->name('main.status.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\StatusController::class, 'destroy'])->name('main.status.destroy');
        });
        //system logs
        Route::group([
            'prefix' => 'logs'], function () {
            Route::get('list', [App\Http\Controllers\Main\ActivityLogsController::class, 'index'])->name('main.logs');
            Route::get('show/{id}', [App\Http\Controllers\Main\ActivityLogsController::class, 'show'])->name('main.logs.show');
            Route::get('destroy/{id}', [App\Http\Controllers\Main\ActivityLogsController::class, 'destroy'])->name('main.logs.destroy');
        });
        //profile
        Route::group([
            'prefix' => 'profile'], function () {
            Route::get('list', [App\Http\Controllers\Main\ProfileController::class, 'index'])->name('main.profile');
            Route::post('store', [App\Http\Controllers\Main\ProfileController::class, 'store'])->name('main.profile.store');
            Route::post('update', [App\Http\Controllers\Main\ProfileController::class, 'update'])->name('main.profile.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\ProfileController::class, 'destroy'])->name('main.profile.destroy');
            Route::get('assignment', [App\Http\Controllers\Main\ProfileController::class, 'assignmentCreate'])->name('main.profile.assignment');
            Route::post('assignment/store', [App\Http\Controllers\Main\ProfileController::class, 'assignmentStore'])->name('main.profile.assignment.store');
            Route::post('assignment/store/single', [App\Http\Controllers\Main\ProfileController::class, 'assignmentStoreSingle'])->name('main.profile.assignment.store.single');
            Route::get('delegation', [App\Http\Controllers\Main\ProfileController::class, 'delegationCreate'])->name('main.profile.delegation');
            Route::get('delegation/list', [App\Http\Controllers\Main\ProfileController::class, 'delegationList'])->name('main.profile.delegation.list');
            Route::get('delegation/show/on/behalf', [App\Http\Controllers\Main\ProfileController::class, 'delegationShowOnBehalf'])->name('main.profile.delegation.show.on.behalf');
            Route::post('delegation/store/on/behalf', [App\Http\Controllers\Main\ProfileController::class, 'delegationStoreOnBehalf'])->name('main.profile.delegation.store.on.behalf');
            Route::post('delegation/store/on/user', [App\Http\Controllers\Main\ProfileController::class, 'delegationStoreUser'])->name('main.profile.delegation.store.on.behalf.user');
            Route::post('delegation/store', [App\Http\Controllers\Main\ProfileController::class, 'delegationStore'])->name('main.profile.delegation.store');
            Route::post('delegation/end/{id}', [App\Http\Controllers\Main\ProfileController::class, 'delegationEnd'])->name('main.profile.delegation.end');
            Route::post('delegation/remove', [App\Http\Controllers\Main\ProfileController::class, 'removeDelegation'])->name('main.profile.delegation.remove');
            Route::get('transfer/', [App\Http\Controllers\Main\ProfileController::class, 'transfer'])->name('main.profile.transfer');
            Route::post('transfer/create', [App\Http\Controllers\Main\ProfileController::class, 'transferCreate'])->name('main.profile.transfer.create');
            Route::get('remove/', [App\Http\Controllers\Main\ProfileController::class, 'remove'])->name('main.profile.remove');
            Route::post('remove/create', [App\Http\Controllers\Main\ProfileController::class, 'removeCreate'])->name('main.profile.remove.create');

        });
        //profile Permissions
        Route::group([
            'prefix' => 'profile/permissions'], function () {
            Route::get('list', [App\Http\Controllers\Main\ProfilePermissionsController::class, 'index'])->name('main.profile.permissions');
            Route::post('store', [App\Http\Controllers\Main\ProfilePermissionsController::class, 'store'])->name('main.profile.permission.store');
            Route::post('update', [App\Http\Controllers\Main\ProfilePermissionsController::class, 'update'])->name('main.profile.permission.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\ProfilePermissionsController::class, 'destroy'])->name('main.profile.permission.destroy');
        });
        //Position
        Route::group([
            'prefix' => 'position'], function () {
            Route::get('list', [App\Http\Controllers\Main\PositionController::class, 'index'])->name('main.position');
            Route::post('store', [App\Http\Controllers\Main\PositionController::class, 'store'])->name('main.position.store');
            Route::post('update', [App\Http\Controllers\Main\PositionController::class, 'update'])->name('main.position.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\PositionController::class, 'destroy'])->name('main.position.destroy');
            Route::get('sync', [App\Http\Controllers\Main\PositionController::class, 'sync'])->name('main.position.sync');
        });
        //User Unit
        Route::group([
            'prefix' => 'user/unit'], function () {
            Route::get('list', [App\Http\Controllers\Main\UserUnitController::class, 'index'])->name('main.user.unit');
            Route::post('store', [App\Http\Controllers\Main\UserUnitController::class, 'store'])->name('main.user.unit.store');
            Route::post('update', [App\Http\Controllers\Main\UserUnitController::class, 'update'])->name('main.user.unit.update');
            Route::post('destroy', [App\Http\Controllers\Main\UserUnitController::class, 'destroy'])->name('main.user.unit.destroy');
            Route::get('sync', [App\Http\Controllers\Main\UserUnitController::class, 'sync'])->name('main.user.unit.sync');
            Route::post('search', [App\Http\Controllers\Main\UserUnitController::class, 'search'])->name('main.user.unit.search');
            Route::post('search/{id}', [App\Http\Controllers\Main\UserUnitController::class, 'searchId'])->name('main.user.unit.search.profile');
            Route::get('users/{unit}/{profile}', [App\Http\Controllers\Main\HomeController::class, 'getMySuperiorAPI'])->name('main.user.unit.search.id');
            Route::get('many/users/{profile}', [App\Http\Controllers\Main\HomeController::class, 'getManySuperiorAPI'])->name('main.user.unit.search.many');
            Route::post('assign', [App\Http\Controllers\Main\UserUnitController::class, 'assign'])->name('main.user.unit.assign');
        });
        //Directorate
        Route::group([
            'prefix' => 'directorate'], function () {
            Route::get('list', [App\Http\Controllers\Main\DirectoratesController::class, 'index'])->name('main.directorate');
            Route::post('store', [App\Http\Controllers\Main\DirectoratesController::class, 'store'])->name('main.directorate.store');
            Route::post('update', [App\Http\Controllers\Main\DirectoratesController::class, 'update'])->name('main.directorate.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\DirectoratesController::class, 'destroy'])->name('main.directorate.destroy');
            Route::get('sync', [App\Http\Controllers\Main\DirectoratesController::class, 'sync'])->name('main.directorate.sync');
            Route::get('sync', [App\Http\Controllers\Main\DirectoratesController::class, 'syncOrganoGram'])->name('main.directorate.syncOrganoGram');
        });
        //region
        Route::group([
            'prefix' => 'region'], function () {
            Route::get('list', [App\Http\Controllers\Main\RegionsController::class, 'index'])->name('main.region');
            Route::post('store', [App\Http\Controllers\Main\RegionsController::class, 'store'])->name('main.region.store');
            Route::post('update', [App\Http\Controllers\Main\RegionsController::class, 'update'])->name('main.region.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\RegionsController::class, 'destroy'])->name('main.region.destroy');
        });
        //grade
        Route::group([
            'prefix' => 'grade'], function () {
            Route::get('list', [App\Http\Controllers\Main\GradesController::class, 'index'])->name('main.grade');
            Route::post('store', [App\Http\Controllers\Main\GradesController::class, 'store'])->name('main.grade.store');
            Route::post('update', [App\Http\Controllers\Main\GradesController::class, 'update'])->name('main.grade.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\GradesController::class, 'destroy'])->name('main.grade.destroy');
            Route::get('sync', [App\Http\Controllers\Main\GradesController::class, 'sync'])->name('main.grade.sync');
        });
        //grade category
        Route::group([
            'prefix' => 'grade/category'], function () {
            Route::get('list', [App\Http\Controllers\Main\GradesCategoryController::class, 'index'])->name('main.grade.category');
            Route::post('store', [App\Http\Controllers\Main\GradesCategoryController::class, 'store'])->name('main.grade.category.store');
            Route::post('update', [App\Http\Controllers\Main\GradesCategoryController::class, 'update'])->name('main.grade.category.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\GradesCategoryController::class, 'destroy'])->name('main.grade.category.destroy');
            Route::get('sync', [App\Http\Controllers\Main\GradesCategoryController::class, 'sync'])->name('main.grade.category.sync');
        });
        //project
        Route::group([
            'prefix' => 'project'], function () {
            Route::get('list', [App\Http\Controllers\Main\ProjectsController::class, 'index'])->name('main.project');
            Route::post('store', [App\Http\Controllers\Main\ProjectsController::class, 'store'])->name('main.project.store');
            Route::post('update', [App\Http\Controllers\Main\ProjectsController::class, 'update'])->name('main.project.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\ProjectsController::class, 'destroy'])->name('main.project.destroy');
        });
        //account
        Route::group([
            'prefix' => 'account'], function () {
            Route::get('list', [App\Http\Controllers\Main\AccountsChartsController::class, 'index'])->name('main.account');
            Route::post('store', [App\Http\Controllers\Main\AccountsChartsController::class, 'store'])->name('main.account.store');
            Route::post('update', [App\Http\Controllers\Main\AccountsChartsController::class, 'update'])->name('main.account.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\AccountsChartsController::class, 'destroy'])->name('main.account.destroy');
        });
        //department
        Route::group([
            'prefix' => 'department'], function () {
            Route::get('list', [App\Http\Controllers\Main\DepartmentController::class, 'index'])->name('main.department');
            Route::post('store', [App\Http\Controllers\Main\DepartmentController::class, 'store'])->name('main.department.store');
            Route::post('update', [App\Http\Controllers\Main\DepartmentController::class, 'update'])->name('main.department.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\DepartmentController::class, 'destroy'])->name('main.department.destroy');
            Route::get('sync', [App\Http\Controllers\Main\DepartmentController::class, 'sync'])->name('main.department.sync');
        });
        //divisional_user.unit
//        Route::group([
//            'prefix' => 'divisional_user.unit'], function () {
//            Route::get('list', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'index'])->name('main.divisional-user.unit');
//            Route::post('store', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'store'])->name('main.divisional-user.unit.store');
//            Route::post('update', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'update'])->name('main.divisional-user.unit.update');
//            Route::post('destroy/{id}', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'destroy'])->name('main.divisional-user.unit.destroy');
//            Route::get('sync', [App\Http\Controllers\Main\DivisionalUserUnitController::class, 'sync'])->name('main.divisional-user.unit.sync');
//        });
        //division
        Route::group([
            'prefix' => 'division'], function () {
            Route::get('list', [App\Http\Controllers\Main\DivisionsController::class, 'index'])->name('main.division');
            Route::post('store', [App\Http\Controllers\Main\DivisionsController::class, 'store'])->name('main.division.store');
            Route::post('update', [App\Http\Controllers\Main\DivisionsController::class, 'update'])->name('main.division.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\DivisionsController::class, 'destroy'])->name('main.division.destroy');
            Route::get('sync', [App\Http\Controllers\Main\DivisionsController::class, 'sync'])->name('main.division.sync');
        });
        //functional unit
        Route::group([
            'prefix' => 'functional/unit'], function () {
            Route::get('list', [App\Http\Controllers\Main\FunctionalUnitController::class, 'index'])->name('main.functional.unit');
            Route::post('store', [App\Http\Controllers\Main\FunctionalUnitController::class, 'store'])->name('main.functional.unit.store');
            Route::post('update', [App\Http\Controllers\Main\FunctionalUnitController::class, 'update'])->name('main.functional.unit.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\FunctionalUnitController::class, 'destroy'])->name('main.functional.unit.destroy');
            Route::get('sync', [App\Http\Controllers\Main\FunctionalUnitController::class, 'sync'])->name('main.functional.unit.sync');
        });

        //paypoint
        Route::group([
            'prefix' => 'pay/point'], function () {
            Route::get('list', [App\Http\Controllers\Main\PayPointController::class, 'index'])->name('main.pay.point');
            Route::post('store', [App\Http\Controllers\Main\PayPointController::class, 'store'])->name('main.pay.point.store');
            Route::post('update', [App\Http\Controllers\Main\PayPointController::class, 'update'])->name('main.pay.point.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\PayPointController::class, 'destroy'])->name('main.pay.point.destroy');
            Route::get('sync', [App\Http\Controllers\Main\PayPointController::class, 'sync'])->name('main.pay.point.sync');
        });

        //location
        Route::group([
            'prefix' => 'location'], function () {
            Route::get('list', [App\Http\Controllers\Main\LocationController::class, 'index'])->name('main.location');
            Route::post('store', [App\Http\Controllers\Main\LocationController::class, 'store'])->name('main.location.store');
            Route::post('update', [App\Http\Controllers\Main\LocationController::class, 'update'])->name('main.location.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\LocationController::class, 'destroy'])->name('main.location.destroy');
            Route::get('sync', [App\Http\Controllers\Main\LocationController::class, 'sync'])->name('main.location.sync');
        });

        //Tax
        Route::group([
            'prefix' => 'tax'], function () {
            Route::get('list', [App\Http\Controllers\Main\TaxController::class, 'index'])->name('main.tax');
            Route::post('store', [App\Http\Controllers\Main\TaxController::class, 'store'])->name('main.tax.store');
            Route::post('update', [App\Http\Controllers\Main\TaxController::class, 'update'])->name('main.tax.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\TaxController::class, 'destroy'])->name('main.tax.destroy');
            Route::get('sync', [App\Http\Controllers\Main\TaxController::class, 'sync'])->name('main.tax.sync');
        });


        //CONFIDENTIAL USERS
        Route::group([
            'prefix' => 'confidential-users'], function () {
            Route::get('edit', [App\Http\Controllers\Main\ConfidentialUsersController::class, 'edit'])->name('main.confidential.users.edit');
            Route::get('list', [App\Http\Controllers\Main\ConfidentialUsersController::class, 'index'])->name('main.confidential.users');
            Route::post('store', [App\Http\Controllers\Main\ConfidentialUsersController::class, 'store'])->name('main.confidential.users.store');
            Route::post('update', [App\Http\Controllers\Main\ConfidentialUsersController::class, 'update'])->name('main.confidential.users.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\ConfidentialUsersController::class, 'destroy'])->name('main.confidential.users.destroy');
            Route::get('sync', [App\Http\Controllers\Main\ConfidentialUsersController::class, 'sync'])->name('main.confidential.users.sync');
        });

        //operating units
        Route::group([
            'prefix' => 'operating-units'], function () {
            Route::get('list', [App\Http\Controllers\Main\OperatingUnitsController::class, 'index'])->name('main.operating.units');
            Route::post('store', [App\Http\Controllers\Main\OperatingUnitsController::class, 'store'])->name('main.operating.units.store');
            Route::post('update', [App\Http\Controllers\Main\OperatingUnitsController::class, 'update'])->name('main.operating.units.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\OperatingUnitsController::class, 'destroy'])->name('main.operating.units.destroy');
        });

        //totals
        Route::group([
            'prefix' => 'totals'], function () {
            Route::get('list', [App\Http\Controllers\Main\TotalsController::class, 'index'])->name('main.totals');
            Route::post('store', [App\Http\Controllers\Main\TotalsController::class, 'store'])->name('main.totals.store');
            Route::post('update', [App\Http\Controllers\Main\TotalsController::class, 'update'])->name('main.totals.update');
            Route::post('destroy/{id}', [App\Http\Controllers\Main\TotalsController::class, 'destroy'])->name('main.totals.destroy');
            Route::get('sync', [App\Http\Controllers\Main\TotalsController::class, 'sync'])->name('main.totals.sync');
        });


        //Files
        Route::group([
            'prefix' => 'files'], function () {
            Route::post('change', [App\Http\Controllers\Main\HomeController::class, 'changeFile'])->name('attached.file.change');
            Route::post('add', [App\Http\Controllers\Main\HomeController::class, 'addFile'])->name('attached.file.add');
            Route::post('add2', [App\Http\Controllers\Main\HomeController::class, 'addFile2'])->name('attached.file.add2');
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
    'middleware' => 'auth'],
    function () {

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

    Route::get('home', [App\Http\Controllers\EForms\GiftDeclaration\HomeController::class, 'index'])->name('vehicle.requisitioning.home');
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

//Route::group([
//    'namespace' => 'hotel_accommodation_home',
//    'prefix' => 'hotel/accommodation/home',
//    'middleware' => 'auth'], function () {
//
//    Route::get('home', [App\Http\Controllers\EForms\GiftDeclaration\HomeController::class, 'index'])->name('hotel.accommodation.home');
//    Route::get('list/{value}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'index'])->name('gifts-declaration-list');
//    Route::get('create', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'create'])->name('gifts-declaration-create');
//    Route::post('show/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'show'])->name('gifts-declaration-show');
//    Route::post('store', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'store'])->name('gifts-declaration-store');
//    Route::post('approve', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'approve'])->name('gifts-declaration-approve');
//    Route::post('update', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'update'])->name('gifts-declaration-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'destroy'])->name('gifts-declaration-destroy');
//    Route::get('reports', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reports'])->name('gifts-declaration-report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\GiftDeclaration\GiftDeclarationController::class, 'reportsExport'])->name('gifts-declaration-report-export');

//});


Route::group([
    'namespace' => 'salary',
    'prefix' => 'salary/advance',
    'middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\EForms\GiftDeclaration\HomeController::class, 'index'])->name('salary.advance.home');
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

//    Route::get('home', [App\Http\Controllers\EForms\TempsCasuals\HomeController::class, 'index'])->name('temps-casuals-home');
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
| KILOMETER-ALLOWANCE DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the KILOMETER-ALLOWANCE DASHBOARD.
|
*/

Route::group([
    'namespace' => 'kilometer_allowance',
    'prefix' => 'kilometer/allowance',
    'middleware' => 'auth'],
    function () {

        //kilometer allowance routes
        Route::get('home', [App\Http\Controllers\EForms\KilometerAllowance\HomeController::class, 'index'])->name('kilometer.allowance.home');
        Route::get('list/{value}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'index'])->name('kilometer.allowance.list');
        Route::get('create', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'create'])->name('kilometer.allowance.create');
        Route::post('show/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'show'])->name('kilometer.allowance.show');
        Route::post('store', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'store'])->name('kilometer.allowance.store');
        Route::post('approve', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'approve'])->name('kilometer.allowance.approve');
        Route::post('update', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'update'])->name('kilometer.allowance.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'destroy'])->name('kilometer.allowance.destroy');
        Route::get('reports/{value}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reports'])->name('kilometer.allowance.report');
        Route::get('reportExport', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reportsExport'])->name('kilometer.allowance.report.export');
        Route::get('reportExportUnmarkExportedAll', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsExportUnmarkExportedAll'])->name('kilometer.allowance.report.export.unmark.exported.all');
        Route::get('reportSync', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reportsSync'])->name('kilometer.allowance.report.sync');
        Route::post('reportExportUnmarkExported/{value}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reportsExportUnmarkExported'])->name('kilometer.allowance.report.unmark.exported');
        Route::get('sync/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'sync'])->name('kilometer.allowance.sync');
        Route::get('records/{value}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'records'])->name('kilometer.allowance.record');
        Route::post('void/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'void'])->name('kilometer.allowance.void');
        Route::post('reverse/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reverse'])->name('kilometer.allowance.reverse');
        Route::post('search', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'search'])->name('kilometer.allowance.search');

        Route::get('charts', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'charts'])->name('kilometer.allowance.charts');
        Route::get('removeDuplicateAccountLines/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'markAccountLinesAsDuplicates'])->name('kilometer.allowance.accounts.duplicate.remove');
        Route::get('showForm/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'showForm'])->name('kilometer.allowance.reports.show');

        //REPORTS
        Route::group([
            'prefix' => 'report'
        ], function () {
            Route::get('directorates', [App\Http\Controllers\EForms\KilometerAllowance\ReportsController::class, 'index'])->name('kilometer.allowance.reports.index');
            Route::get('syncDirectorates', [App\Http\Controllers\EForms\KilometerAllowance\ReportsController::class, 'syncDirectorates'])->name('kilometer.allowance.reports.sync.directorates');
            Route::get('syncUserUnits', [App\Http\Controllers\EForms\KilometerAllowance\ReportsController::class, 'syncUserUnits'])->name('kilometer.allowance.reports.sync.units');

        });
        Route::group([
            'prefix' => 'filtered/report'
        ], function () {
            Route::get('index', [App\Http\Controllers\EForms\KilometerAllowance\ReportsController::class, 'filteredReports'])->name('kilometer.allowance.filtered.report');
            Route::get('get/{unit}/{status}/{start_date}/{end_date}', [App\Http\Controllers\EForms\KilometerAllowance\ReportsController::class, 'getFilteredReports'])->name('kilometer.allowance.filtered.get');
        });


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

    Route::get('home', [App\Http\Controllers\EForms\Virement\HomeController::class, 'index'])->name('virement.home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\Virement\VirementController::class, 'index'])->name('virement.list');
    Route::get('create', [App\Http\Controllers\EForms\Virement\VirementController::class, 'create'])->name('virement.create');
//    Route::post('show/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'show'])->name('virement.show');
    Route::post('store', [App\Http\Controllers\EForms\Virement\VirementController::class, 'store'])->name('virement.store');
//    Route::post('approve', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'approve'])->name('virement.approve');
//    Route::post('update', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'update'])->name('viremenvirementt-update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'destroy'])->name('virement.destroy');
    Route::get('reports', [App\Http\Controllers\EForms\Virement\VirementController::class, 'reports'])->name('virement.report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'reportsExport'])->name('virement.report-export');
//
//    Route::get('charts', [App\Http\Controllers\EForms\KilometerAllowance\KilometerAllowanceController::class, 'charts'])->name('virement.charts');

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

    Route::get('home', [App\Http\Controllers\EForms\DataCenterCA\HomeController::class, 'index'])->name('datacenter.ca.home');
    Route::get('list/{value}', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'index'])->name('datacenter.ca.list');
    Route::get('create', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'create'])->name('datacenter.ca.create');
    Route::post('show/{id}', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'show'])->name('datacenter.ca.show');
    Route::post('store', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'store'])->name('datacenter.ca.store');
//    Route::post('approve', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'approve'])->name('datacenter.ca.approve');
//    Route::post('update', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'update'])->name('datacenter.ca.update');
//    Route::post('destroy/{id}', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'destroy'])->name('datacenter.ca.destroy');
    Route::get('reports', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'reports'])->name('datacenter.ca.report');
//    Route::get('reportExport', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'reportsExport'])->name('datacenter.ca.report-export');
//
//    Route::get('charts', [App\Http\Controllers\EForms\DataCenterCA\DataCenterCAController::class, 'charts'])->name('datacenter.ca.charts');

});



//config_work_flow
Route::group([
    'prefix' => 'work_flow'],
    function () {
    //sync all
    Route::get('sync', [App\Http\Controllers\Main\ConfigWorkFlowController::class, 'syncFromConfigUserUnits'])->name('workflow.sync');
        Route::post('details/{configWorkFlow}', [App\Http\Controllers\Main\ConfigWorkFlowController::class, 'show'])->name('workflow.show');
        Route::get('mine/{user_unit}', [App\Http\Controllers\Main\ConfigWorkFlowController::class, 'mine'])->name('workflow.mine');


    //config_work_flow for petty cash
    Route::group([
        'prefix' => 'petty_cash'], function () {
        Route::get('list', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'index'])->name('petty.cash.workflow');
        Route::post('store', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'store'])->name('petty.cash.workflow.store');
        Route::post('update/{code}', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'update'])->name('petty.cash.workflow.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'destroy'])->name('petty.cash.workflow.destroy');
        Route::post('search', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'search'])->name('petty.cash.workflow.search');
        Route::post('search2/{id}', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'search2'])->name('petty.cash.workflow.search2');
        Route::get('show/{id}/{code}', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'show'])->name('petty.cash.workflow.show');
        Route::get('sync', [App\Http\Controllers\EForms\PettyCash\WorkFlowController::class, 'sync'])->name('petty.cash.workflow.sync');
    });



//    //config_work_flow for accommodation
//    Route::group([
//        'prefix' => 'hotel/accommodation'], function () {
//        Route::get('list', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'index'])->name('hotel.accommodation.workflow');
//        Route::post('store', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'store'])->name('hotel.accommodation.workflow.store');
//        Route::post('update/{code}', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'update'])->name('hotel.accommodation.workflow.update');
//        Route::post('destroy/{id}', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'destroy'])->name('hotel.accommodation.workflow.destroy');
//        Route::post('search', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'search'])->name('hotel.accommodation.workflow.search');
//        Route::get('show/{id}/{code}', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'show'])->name('hotel.accommodation.show');
//        Route::get('sync', [App\Http\Controllers\EForms\HotelAccomodation\WorkFlowController::class, 'sync'])->name('hotel.accommodation.workflow.sync');
//    });


    //config_work_flow for subsistence
    Route::group([
        'prefix' => 'subsistence'], function () {

        Route::get('list', [App\Http\Controllers\EForms\Subsistence\WorkFlowController::class, 'index'])->name('subsistence.workflow');
        Route::post('store', [App\Http\Controllers\EForms\Subsistence\WorkFlowController::class, 'store'])->name('subsistence.workflow.store');
        Route::post('update/{code}', [App\Http\Controllers\EForms\Subsistence\WorkFlowController::class, 'update'])->name('subsistence.workflow.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\Subsistence\WorkFlowController::class, 'destroy'])->name('subsistence.workflow.destroy');
        Route::post('search', [App\Http\Controllers\EForms\Subsistence\WorkFlowController::class, 'search'])->name('subsistence.workflow.search');
        Route::get('show/{id}/{code}', [App\Http\Controllers\EForms\Subsistence\WorkFlowController::class, 'show'])->name('subsistence.workflow.show');
        Route::get('sync', [App\Http\Controllers\EForms\Subsistence\WorkFlowController::class, 'sync'])->name('subsistence.workflow.sync');
    });


    //config_work_flow for kilometer allowance
    Route::group([
        'prefix' => 'kilometer/allowance'], function () {
        Route::get('list', [App\Http\Controllers\EForms\KilometerAllowance\WorkFlowController::class, 'index'])->name('kilometer.allowance.workflow');
        Route::post('store', [App\Http\Controllers\EForms\KilometerAllowance\WorkFlowController::class, 'store'])->name('kilometer.allowance.workflow.store');
        Route::post('update/{code}', [App\Http\Controllers\EForms\KilometerAllowance\WorkFlowController::class, 'update'])->name('kilometer.allowance.workflow.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\KilometerAllowance\WorkFlowController::class, 'destroy'])->name('kilometer.allowance.workflow.destroy');
        Route::post('search', [App\Http\Controllers\EForms\KilometerAllowance\WorkFlowController::class, 'search'])->name('kilometer.allowance.workflow.search');
        Route::get('show/{id}/{code}', [App\Http\Controllers\EForms\KilometerAllowance\WorkFlowController::class, 'show'])->name('kilometer.allowance.workflow.show');
        Route::get('sync', [App\Http\Controllers\EForms\KilometerAllowance\WorkFlowController::class, 'sync'])->name('kilometer.allowance.workflow.sync');
    });

});


/*
|--------------------------------------------------------------------------
| HOTEL-ACCOMMODATION DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the HOTEL-ACCOMMODATION DASHBOARD.
|
*/

Route::group([
    'namespace' => 'hotel_accommodation',
    'prefix' => 'hotel/accommodation',
    'middleware' => 'auth'],
    function () {

        //hotel_accommodation routes
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
        Route::get('reportSync', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reportsSync'])->name('hotel.accommodation.report.sync');
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


/*
|--------------------------------------------------------------------------
|PURCHASE ORDER AMENDMENT DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the PURCHASE ORDER AMENDMENT DASHBOARD.
|
*/

Route::group([
    'namespace' => 'purchase_order',
    'prefix' => 'purchase/order',
    'middleware' => 'auth'],
    function () {

        //hotel_accommodation routes
        Route::get('home', [App\Http\Controllers\EForms\PurchaseOrder\HomeController::class, 'index'])->name('purchase.order.home');
        Route::get('create', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'create'])->name('purchase.order.create');
        Route::get('list/{value}', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'index'])->name('purchase.order.list');
        Route::post('show/{id}', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'show'])->name('purchase.order.show');
        Route::post('store', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'store'])->name('purchase.order.store');
//        Route::post('approve', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'approve'])->name('purchase.order..approve');
//        Route::post('update', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'update'])->name('purchase.order..update');
//        Route::post('destroy/{id}', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'destroy'])->name('purchase.order..destroy');
//        Route::get('reports/{value}', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'reports'])->name('purchase.order..report');
//        Route::get('reportExport', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'reportsExport'])->name('purchase.order..report.export');
//        Route::get('reportSync', [App\Http\Controllers\EForms\PurchaseOrder\PurchaseOrderController::class, 'reportsSync'])->name('purchase.order..report.sync');
//        Route::post('reportExportUnmarkExported/{value}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reportsExportUnmarkExported'])->name('purchase.order..report.export.unmark.exported');
//        Route::get('reportExportUnmarkExportedAll', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'reportsExportUnmarkExportedAll'])->name('purchase.order..report.export.unmark.exported.all');
//        Route::get('sync/{id}', [App\Http\Controllers\EForms\PurchaseOrder\HotelAccommodationController::class, 'sync'])->name('purchase.order.sync');
//        Route::get('records/{value}', [App\Http\Controllers\EForms\PurchaseOrder\HotelAccommodationController::class, 'records'])->name('purchase.order..record');
//        Route::post('void/{id}', [App\Http\Controllers\EForms\PurchaseOrder\HotelAccommodationController::class, 'void'])->name('purchase.order..void');
//        Route::post('reverse/{id}', [App\Http\Controllers\EForms\PurchaseOrder\HotelAccommodationController::class, 'reverse'])->name('purchase.order..reverse');
//        Route::post('search', [App\Http\Controllers\EForms\PurchaseOrder\HotelAccommodationController::class, 'search'])->name('purchase.order..search');
//
//        Route::get('charts', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'charts'])->name('purchase.order..charts');
//        Route::get('removeDuplicateAccountLines/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'markAccountLinesAsDuplicates'])->name('purchase.order..accounts.duplicate.remove');
//        Route::get('showForm/{id}', [App\Http\Controllers\EForms\HotelAccommodation\HotelAccommodationController::class, 'showForm'])->name('purchase.order..reports.show');

//        //REPORTS
//        Route::group([
//            'prefix' => 'report'
//        ], function () {
//            Route::get('directorates', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'index'])->name('hotel.accommodation.reports.index');
//            Route::get('syncDirectorates', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'syncDirectorates'])->name('hotel.accommodation.reports.sync.directorates');
//            Route::get('syncUserUnits', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'syncUserUnits'])->name('hotel.accommodation.reports.sync.units');
//
//        });
//
//        Route::group([
//            'prefix' => 'filtered/report'
//        ], function () {
//            Route::get('index', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'filteredReports'])->name('hotel.accommodation.filtered.report');
//            Route::get('get/{unit}/{status}/{start_date}/{end_date}', [App\Http\Controllers\EForms\HotelAccommodation\ReportsController::class, 'getFilteredReports'])->name('hotel.accommodation.filtered.get');
//        });


    });


Route::group([
    'namespace' => 'system_clients',
    'prefix' => 'system/clients'
], function () {

    //system
    Route::get('list', [App\Http\Controllers\LoginAPI\ClientSystemController::class, 'index'])->name('system.index');
    Route::post('store', [App\Http\Controllers\LoginAPI\ClientSystemController::class, 'store'])->name('system.store');
    Route::post('update', [App\Http\Controllers\LoginAPI\ClientSystemController::class, 'update'])->name('system.update');
    Route::post('update/key', [App\Http\Controllers\LoginAPI\ClientSystemController::class, 'updateKey'])->name('system.update.key');
//    Route::post('destroy/{id}', [App\Http\Controllers\LoginAPI\ClientSystemController::class, 'destroy'])->name('system.destroy');
//    Route::get('sync', [App\Http\Controllers\LoginAPI\ClientSystemController::class, 'sync'])->name('system.sync');

});
/*
|--------------------------------------------------------------------------
| VEHICLE-REQUISITIONING DASHBOARD WEB ROUTES
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the VEHICLE-REQUISITIONING DASHBOARD.
|
*/

Route::group([
    'namespace' => 'vehicle_requisitioning',
    'prefix' => 'vehicle/requisitioning',
    'middleware' => 'auth'],
    function () {

        //vehicle requisitioning routes
        Route::get('home', [App\Http\Controllers\EForms\VehicleRequisitioning\HomeController::class, 'index'])->name('vehicle.requisitioning.home');
        Route::get('list/{value}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'index'])->name('vehicle.requisitioning.list');
        Route::get('create', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'create'])->name('vehicle.requisitioning.create');
        Route::post('show/{id}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'show'])->name('vehicle.requisitioning.show');
        Route::post('store', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'store'])->name('vehicle.requisitioning.store');
        Route::post('approve', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'approve'])->name('vehicle.requisitioning.approve');
        Route::post('update', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'update'])->name('vehicle.requisitioning.update');
        Route::post('destroy/{id}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'destroy'])->name('vehicle.requisitioning.destroy');
        Route::get('reports/{value}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'reports'])->name('vehicle.requisitioning.report');
        Route::get('reportExport', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'reportsExport'])->name('vehicle.requisitioning.report.export');
        Route::get('reportExportUnmarkExportedAll', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'reportsExportUnmarkExportedAll'])->name('vehicle.requisitioning.report.export.unmark.exported.all');
        Route::get('reportSync', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'reportsSync'])->name('vehicle.requisitioning.report.sync');
        Route::post('reportExportUnmarkExported/{value}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'reportsExportUnmarkExported'])->name('vehicle.requisitioning.report.unmark.exported');
        Route::get('sync/{id}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'sync'])->name('vehicle.requisitioning.sync');
        Route::get('records/{value}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'records'])->name('vehicle.requisitioning.record');
        Route::post('void/{id}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'void'])->name('vehicle.requisitioning.void');
        Route::post('reverse/{id}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'reverse'])->name('vehicle.requisitioning.reverse');
        Route::post('search', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'search'])->name('vehicle.requisitioning.search');

        Route::get('charts', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'charts'])->name('vehicle.requisitioning.charts');
        Route::get('removeDuplicateAccountLines/{id}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'markAccountLinesAsDuplicates'])->name('vehicle.requisitioning.accounts.duplicate.remove');
        Route::get('showForm/{id}', [App\Http\Controllers\EForms\VehicleRequisitioning\VehicleRequisitioningController::class, 'showForm'])->name('vehicle.requisitioning.reports.show');

        //REPORTS
        Route::group([
            'prefix' => 'report'
        ], function () {
            Route::get('directorates', [App\Http\Controllers\EForms\VehicleRequisitioning\ReportsController::class, 'index'])->name('vehicle.requisitioning.reports.index');
            Route::get('syncDirectorates', [App\Http\Controllers\EForms\VehicleRequisitioning\ReportsController::class, 'syncDirectorates'])->name('vehicle.requisitioning.reports.sync.directorates');
            Route::get('syncUserUnits', [App\Http\Controllers\EForms\VehicleRequisitioning\ReportsController::class, 'syncUserUnits'])->name('vehicle.requisitioning.reports.sync.units');

        });
        Route::group([
            'prefix' => 'filtered/report'
        ], function () {
            Route::get('index', [App\Http\Controllers\EForms\VehicleRequisitioning\ReportsController::class, 'filteredReports'])->name('vehicle.requisitioning.filtered.report');
            Route::get('get/{unit}/{status}/{start_date}/{end_date}', [App\Http\Controllers\EForms\VehicleRequisitioning\ReportsController::class, 'getFilteredReports'])->name('vehicle.requisitioning.filtered.get');
        });


    });


