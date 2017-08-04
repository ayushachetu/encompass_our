<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('manager-form', 'FormController@getMemberForm');
Route::post('manager-form', 'FormController@getMemberFormSubmit');
Route::get('talent-form', 'FormController@getTalentForm');
Route::post('talent-form', 'FormController@getTalentFormSubmit');
Route::get('training-form', 'FormController@getTrainingForm');
Route::post('training-form', 'FormController@getTrainingFormSubmit');
Route::get('exit-interview-form', 'FormController@getExitInterviewForm');
Route::post('exit-interview-form', 'FormController@getExitInterviewFormSubmit');


// Authentication routes...
Route::get('admin', 'Auth\AuthController@getLogin');
Route::post('admin', 'Auth\AuthController@authenticate');
Route::get('admin/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::group(['middleware' => 'auth'], function () {
	Route::get('dashboard', 'MetricController@getIndex');	
});

Route::group(['middleware' => 'auth', 'middleware' => 'role:1'], function () {
	Route::get('users', 'UserController@getIndex');
	Route::get('employee', 'UserController@listEmployee');
	Route::get('user/create', 'UserController@create');
	Route::post('user/create', 'UserController@store');
	Route::get('user/edit/{id}', 'UserController@edit');
	Route::post('user/edit/{id}', 'UserController@update');
	Route::get('user/delete/{id}', 'UserController@delete');
	Route::post('user/delete/{id}', 'UserController@destroy');	
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('user/profile', 'UserController@profile');
	Route::post('user/profile', 'UserController@saveProfile');
});


Route::group(['middleware' => 'auth', 'middleware' => 'role:1'], function () {
	Route::get('data_dashboard', 'Data@getIndex');
	Route::get('data/process_billable_hours_data', 'Data@processBillableHours');
	Route::get('data/process_job_data', 'Data@processJob');
	Route::get('data/process_expense_data', 'Data@processExpense');
	Route::get('data/process_budget_data', 'Data@processBudget');
	Route::get('data/process_budget_data_all', 'Data@processBudgetData');
	Route::get('data/process_budget_gl', 'Data@processBudgetGL');
	Route::get('data/process_actual_gl', 'Data@processActualGL');
	Route::get('data/process_accounting_gl', 'Data@processAccountingGL');
	Route::get('data/process_budget_timekeeping_all', 'Data@processTimekeepingData');
	Route::get('data/process_taskcodes', 'Data@processTaskcodes');
	Route::get('data/process_taskcodes_detail', 'Data@processTaskcodesDetail');
	Route::get('data/process_taskcodes_category', 'Data@processTaskcodesCategory');
	Route::get('data/process_account_data', 'Data@processAccount');
	Route::get('data/process_user_data', 'Data@processUser');
	Route::get('data/process_user_inactive_data', 'Data@processUserInactive');
	Route::get('data/process_user_active_data', 'Data@processUserActive');
	Route::get('data/process_feet_data', 'Data@processSquareFeet');
	Route::get('data/process_labor_tax_data', 'Data@processLaborTax');
	Route::get('data/process_budget_monthly_data', 'Data@processBudgetMonthly');
	Route::get('data/process_vendor_data', 'Data@processVendor');
	Route::get('data/process_timekeeping', 'Data@processTimekeeping');
	Route::get('data/process_report_account_data', 'Data@processReportAccount');
	Route::get('data/process_wotc', 'Data@processWOTC');
	Route::get('data/view_pay_data', 'Data@viewPay');
	Route::get('data/view_billable_hours_data', 'Data@viewBillableHours');
	Route::get('data/view_job_data', 'Data@viewJob');
	Route::get('data/view_expense_data', 'Data@viewExpense');
	Route::get('data/view_budget_data', 'Data@viewBudget');
	Route::get('data/view_account_data', 'Data@viewAccount');

	//Payroll Functions
	Route::get('payroll-tools', 'ToolsController@getPayroll');
	Route::get('payroll-request', 'ToolsController@requestPayroll');
	Route::post('payroll-request-submit', 'ToolsController@submitPayroll');
	Route::get('payroll-get-file/{name_file}', 'ToolsController@getFile');
	Route::get('payroll-get-file-csv/{id}', 'ToolsController@getFileCSV');
	Route::get('payroll-get-file-job', 'ToolsController@getJobFile');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('metrics', 'MetricController@getIndex');
	Route::post('metrics/portfolio', 'MetricController@getMetrics');
	Route::get('metrics/detail/{job_site}/{job_portfolio}', 'MetricController@getMetrics');
});

Route::group(['middleware' => 'auth', 'middleware' => 'role:4'], function () {
	Route::get('metrics/manager/{manager_id}', 'MetricController@getMetricsManager');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('evaluations', 'EvaluationController@getIndex');
	Route::get('evaluations/history', 'EvaluationController@getHistory');
	Route::post('evaluation/submit', 'EvaluationController@submitEvaluation');
	Route::get('loadcomments', 'EvaluationController@loadComments');
});


Route::group(['middleware' => 'auth'], function () {
	Route::get('quality', 'QualityController@getIndex');
	Route::get('history-job', 'FormController@getHistoryJob');
	Route::get('history-job-download', 'FormController@getHistoryJobDownload');
	Route::get('history-job-view/{id}', 'FormController@getHistoryJobView');
	Route::get('history-talent-change', 'FormController@getHistoryTalentChange');
	Route::get('history-talent-download/{type}', 'FormController@getHistoryTalentChangeDownload');
	Route::get('history-training', 'FormController@getHistoryTraining');
	Route::get('history-training-view/{id}', 'FormController@getHistoryTrainingView');
	Route::get('history-exit-interview', 'FormController@getHistoryExitInterview');
	Route::get('history-exit-interview-view/{id}', 'FormController@getHistoryExitInterviewView');
	Route::get('history-talent-change/get-item/{id}', 'FormController@getTalentItemList');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('tools', 'ToolsController@getIndex');
	Route::get('announcement', 'ToolsController@getAnnouncement');
	Route::post('announcement', 'ToolsController@submitAnnouncement');
	Route::get('announcement-dashboard', 'ToolsController@getAnnouncementDashboard');
	Route::get('announcement/create', 'ToolsController@getAnnouncementCreate');
	Route::post('announcement/create', 'ToolsController@getAnnouncementSave');
	Route::get('announcement/edit/{id}', 'ToolsController@getAnnouncementEdit');
	Route::post('announcement/edit/{id}', 'ToolsController@getAnnouncementUpdate');
	Route::get('announcement/delete/{id}', 'ToolsController@getAnnouncementDelete');
	Route::post('announcement/delete/{id}', 'ToolsController@getAnnouncementDestroy');
	Route::get('financial', 'FinancialController@getIndex');
	Route::get('financial-request', 'FinancialController@request');
	Route::post('financial-call', 'FinancialController@call');
	Route::get('financial-view/{id}', 'FinancialController@view');
	Route::get('financial-export/{id}', 'FinancialController@export');
	Route::get('financial-mark-exported', 'FinancialController@mark_exported');
	Route::get('financial-structure', 'FinancialController@structure');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('quotes', 'QuoteController@manage');
	Route::get('quotes/create', 'QuoteController@create');
	Route::post('quotes/create', 'QuoteController@store');
	Route::get('quote/edit/{id}', 'QuoteController@edit');
	Route::post('quote/edit/{id}', 'QuoteController@update');
	Route::post('quote/edit_quote/{id}', 'QuoteController@update_quote');
	Route::get('quote/view/{id}', 'QuoteController@show');
	Route::post('quote/view/{id}', 'QuoteController@save');
	Route::get('quote/clone/{id}', 'QuoteController@clone_quote');
	Route::post('quote/delete', 'QuoteController@destroy');
	Route::get('quote/pdf/{id}', 'QuoteController@viewPdf');
	Route::get('quote/email/{id}', 'QuoteController@viewEmail');
	Route::post('quote/email/{id}', 'QuoteController@sendEmail');
	Route::get('quote/get-list/{type}/{id}', 'QuoteController@getItemList');
	Route::get('quotes/export', 'QuoteController@getListExport');
	Route::post('quotes/export', 'QuoteController@ListExport');
	Route::get('quote/filter-quote/{param1}/{param2}/{param3}/{param4}/{param0}', 'QuoteController@changeFilter');
	Route::get('quote/order-by-quote/{orderby1}/{orderby2}', 'QuoteController@changeOrder');
	Route::get('quotes/correlative', 'QuoteController@assignCorrelative');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('budget', 'BudgetController@getIndex');
	Route::get('budget/report', 'BudgetController@report');
	Route::get('budget/graphs', 'BudgetController@graphs');
	Route::post('budget/load_list', 'BudgetController@loadList');
	Route::get('budget/change-month/{type}/{value}', 'BudgetController@changeMonth');
	Route::get('budget/report-export/{type}', 'BudgetController@reportExport');


});

Route::group(['middleware' => 'auth'], function () {
	Route::get('phantom', 'ReportController@getPhantom');
	Route::get('reports', 'ReportController@getIndex');
	Route::get('reports/{type}', 'ReportController@getIndex');
	Route::get('reports/pdf/{type}', 'ReportController@getReportPdf');
	Route::get('report/sales-detail', 'ReportController@getReportSalesDetail');
	Route::get('report/ler-detail', 'ReportController@getReportLerDetail');
	Route::get('report/cost-budget-detail', 'ReportController@getReportCostDetail');
	Route::get('report/timekeeping-detail', 'ReportController@getReportTimekeepingDetail');
	Route::get('report/ytd-county', 'ReportController@getReportCounty');
	Route::get('report/ytd-industry', 'ReportController@getReportIndustry');
	Route::get('report/ytd-mayor-account', 'ReportController@getReportMayorAccount');
	Route::get('report/ytd-manager', 'ReportController@getReportManager');
	Route::get('report/actual-budget/{type}', 'ReportController@getReportActualBudget');
	Route::get('report/timekeeping/{type}', 'ReportController@getReportTimekeeping');
	Route::get('report/contract-strategic-sales-detail', 'ReportController@getReportContractStrategicSalesDetail');
	Route::get('report/contract-strategic-sales/{type}', 'ReportController@getReportContractStrategicSales');
	Route::get('report/ler-view/{type}', 'ReportController@getReportLer');
	Route::get('report/change-month/{type}/{month}', 'ReportController@changeMonth');
	Route::get('report/change-date/{type}/{date}', 'ReportController@changeDate');
	Route::get('report/change-sales-date/{type}/{date}', 'ReportController@changeSalesDate');
	Route::get('report/filter-values/{ini_month}/{end_month}/{year}', 'ReportController@changeFilterValues');
	Route::get('report/filter-date-values/{ini_date}/{end_date}', 'ReportController@changeFilterDate');
	Route::get('report/filter-date-sales-values/{ini_date}/{end_date}', 'ReportController@changeFilterSalesDate');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('sales', 'SalesController@getIndex');
	Route::get('sales-run', 'SalesController@runJob');
	Route::get('sales-run-pipeline', 'SalesController@runPipeline');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('map', 'MapsController@getIndex');
	Route::get('map/{industry}', 'MapsController@getIndex');	
});	

Route::group(['middleware' => 'auth', 'middleware' => 'role:4'], function () {
	// Question module routes
	Route::get('questions', 'QuestionsController@index');
	Route::post('add-question', 'QuestionsController@store');
	Route::post('store-industry-question', 'QuestionsController@storeIndustryQuestion');
	Route::post('delete-industry-question', 'QuestionsController@deleteIndustryQuestion');
	Route::post('update-priority', 'QuestionsController@updatePriority');
	Route::post('delete-question', 'QuestionsController@delete');
	Route::post('question-data', 'QuestionsController@getQuestionData');
	Route::post('edit-question', 'QuestionsController@edit');

	// Matrix option routes
	Route::get('matrix-options', 'MatrixOptionsController@index');
	Route::post('add-matrix-option', 'MatrixOptionsController@store');
	Route::post('delete-matrix-option', 'MatrixOptionsController@delete');
	Route::post('edit-matrix-option', 'MatrixOptionsController@edit');
	Route::post('restore-matrix-option', 'MatrixOptionsController@restore');

	// Survey module routes
	Route::get('survey', 'SurveyController@index');
	Route::post('survey/store', 'SurveyController@store');
	Route::post('survey/delete', 'SurveyController@delete');
	Route::post('survey/launch', 'SurveyController@launch');
	Route::post('survey/hold', 'SurveyController@hold');
	Route::post('survey/edit', 'SurveyController@edit');
	Route::get('survey/{id}', 'SurveyController@surveyDetails');
	Route::post('survey/get-industry-question', 'SurveyController@getIndustryQuestion');
	Route::post('survey/add-industry-question', 'SurveyController@addSurveyQuestion');
	Route::post('survey/delete-industry-question', 'SurveyController@deleteSurveyQuestion');

	Route::get('survey/form/{random_id}', 'SubmittedSurveyController@index');
	Route::post('survey/form/save-survey-question-data', 'SubmittedSurveyController@saveSurveyQuestionData');
	Route::post('survey/form/add-industry-question', 'SubmittedSurveyController@addSurveyQuestion');
	Route::post('survey/form/save-signature', 'SubmittedSurveyController@saveSignature');

	Route::get('survey/completed', 'SubmittedSurveyController@showCompleted');

	Route::post('quality/get-url', 'QualityController@getUrl');


	// Survey Reports Routes
	Route::get('survey-reports', 'SurveyReportsController@index');

	// Client Cumulative Report routes
	Route::get('survey-reports/client-cumulative', 'SurveyReportsController@clientCumulative');
	Route::post('survey-reports/client-cumulative/primary-filter', 'SurveyReportsController@primaryFilter');
	Route::post('survey-reports/client-cumulative/secondary-filter', 'SurveyReportsController@secondaryFilter');

	Route::get('survey-reports/client-per-survey', 'SurveyReportsController@clientPerSurvey');
	Route::post('survey-reports/client-per-survey/auditors', 'SurveyReportsController@clientPerSurveyAuditors');
	Route::post('survey-reports/client-per-survey/list', 'SurveyReportsController@clientPerSurveyList');

	Route::get('survey-reports/manager-cumulative', 'SurveyReportsController@index');
	Route::get('survey-reports/director-qa-cumulative', 'SurveyReportsController@index');
	Route::get('survey-reports/manager-supervisor-issue-notification', 'SurveyReportsController@index');


	// Report Scheduling Routes
	Route::get('/scheduled-jobs/list', 'SchedulingController@index');
	Route::post('/scheduled-jobs/create', 'SchedulingController@create');
	Route::post('/scheduled-jobs/delete', 'SchedulingController@delete');


	Route::get('/triggers', 'TriggersController@index');
	Route::get('/triggers/create', 'TriggersController@form');
	Route::post('/triggers/create', 'TriggersController@create');

});