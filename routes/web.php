<?php
/**
 * Web Routes
 * Define your application routes here
 */

// Home/Landing Page
$router->get('', 'HomeController@index');
$router->get('/', 'HomeController@index');

// Authentication Routes
$router->get('admin/login', 'Auth\\AdminAuthController@showLogin');
$router->post('admin/login', 'Auth\\AdminAuthController@login');
$router->get('admin/logout', 'Auth\\AdminAuthController@logout');

$router->get('agent/login', 'Auth\\AgentAuthController@showLogin');
$router->post('agent/login', 'Auth\\AgentAuthController@login');
$router->get('agent/logout', 'Auth\\AgentAuthController@logout');

// Agent Forgot PIN Routes
$router->get('agent/forgot-pin', 'Auth\\AgentAuthController@showForgotPin');
$router->post('agent/forgot-pin', 'Auth\\AgentAuthController@processForgotPin');
$router->get('agent/forgot-pin-dev-link', 'Auth\\AgentAuthController@showDevResetLink');
$router->get('agent/reset-pin/{token}', 'Auth\\AgentAuthController@showResetPinToken');
$router->post('agent/reset-pin-token', 'Auth\\AgentAuthController@processResetPinToken');

$router->get('bank/login', 'Auth\\BankAuthController@showLogin');
$router->post('bank/login', 'Auth\\BankAuthController@login');
$router->get('bank/logout', 'Auth\\BankAuthController@logout');

// Admin Routes
$router->get('admin/dashboard', 'Admin\\DashboardController@index');
$router->get('admin/backup', 'Admin\\BackupController@index');
$router->post('admin/backup/create', 'Admin\\BackupController@create');

// Admin - Settings
$router->get('admin/settings/get', 'Admin\\SettingsController@getBranchSettings');
$router->post('admin/settings/update', 'Admin\\SettingsController@updateSetting');

// Admin - Agent Management
$router->get('admin/agents', 'Admin\\AgentController@index');
$router->get('admin/agents/add', 'Admin\\AgentController@create');
$router->post('admin/agents/add', 'Admin\\AgentController@store');
$router->get('admin/agents/edit/{id}', 'Admin\\AgentController@edit');
$router->post('admin/agents/edit/{id}', 'Admin\\AgentController@update');
$router->post('admin/agents/delete/{id}', 'Admin\\AgentController@delete');
$router->post('admin/agents/check-code', 'Admin\\AgentController@checkCode');

// Admin - Branch Management
$router->get('admin/branches', 'Admin\\BranchController@index');
$router->get('admin/branches/add', 'Admin\\BranchController@create');
$router->post('admin/branches/add', 'Admin\\BranchController@store');
$router->get('admin/branches/edit/{id}', 'Admin\\BranchController@edit');
$router->post('admin/branches/edit/{id}', 'Admin\\BranchController@update');
$router->post('admin/branches/delete/{id}', 'Admin\\BranchController@delete');
$router->post('admin/branches/check-code', 'Admin\\BranchController@checkCode');

// Admin - License Management
$router->get('admin/licenses', 'Admin\\LicenseController@index');
$router->get('admin/licenses/add', 'Admin\\LicenseController@create');
$router->post('admin/licenses/add', 'Admin\\LicenseController@store');
$router->get('admin/licenses/renew/{id}', 'Admin\\LicenseController@renew');
$router->post('admin/licenses/renew/{id}', 'Admin\\LicenseController@processRenew');

// Agent Routes
$router->get('agent/dashboard', 'Agent\\DashboardController@index');
$router->post('agent/collection/save', 'Agent\\PigmyController@save');
$router->post('agent/check-transaction', 'Agent\\PigmyController@checkTransaction');
$router->get('agent/success', 'Agent\\PigmyController@success');
$router->get('agent/change-pin', 'Agent\\ProfileController@showChangePin');
$router->post('agent/change-pin', 'Agent\\ProfileController@changePin');
$router->get('agent/reset-pin', 'Agent\\ProfileController@showResetPin');
$router->post('agent/reset-pin', 'Agent\\ProfileController@resetPin');

// Agent - Reports
$router->get('agent/reports', 'Agent\\ReportController@index');
$router->post('agent/reports', 'Agent\\ReportController@index');
$router->get('agent/reports/daywise', 'Agent\\ReportController@daywise');
$router->get('agent/reports/print-daywise', 'Agent\\ReportController@printDaywise');

// Agent - Resend Messages
$router->get('agent/resend', 'Agent\\ResendController@index');
$router->post('agent/resend/send', 'Agent\\ResendController@send');

// Agent - License Warning
$router->get('agent/license-warning', 'Agent\\LicenseController@showWarning');
$router->post('agent/license-warning', 'Agent\\LicenseController@continueLogin');

// Bank Routes
$router->get('bank/dashboard', 'Bank\\DashboardController@index');
$router->get('bank/profile', 'Bank\\ProfileController@index');
$router->get('bank/backup', 'Bank\\BackupController@index');
$router->get('bank/reset-password', 'Bank\\ProfileController@showResetPassword');
$router->post('bank/reset-password', 'Bank\\ProfileController@resetPassword');

// Bank - Agent Management
$router->get('bank/agents', 'Bank\\AgentController@index');
$router->post('bank/agents/toggle-status', 'Bank\\AgentController@toggleStatus');

// Bank - Reports
$router->get('bank/reports', 'Bank\\ReportController@index');
$router->get('bank/reports/agent-transactions', 'Bank\\ReportController@agentTransactions');
$router->get('bank/reports/detailed', 'Bank\\ReportController@detailedTransactions');
$router->get('bank/reports/detailed-transactions', 'Bank\\ReportController@detailedTransactions');
$router->get('bank/reports/export-agent', 'Bank\\ReportController@exportAgentReport');
$router->get('bank/reports/print-summary', 'Bank\\ReportController@printSummary');

// Customer Routes
$router->get('customer/transactions/{id}', 'CustomerController@transactions');

// API Routes (for AJAX calls)
$router->post('api/fetch-agent', 'Api\\FetchController@agent');
$router->post('api/fetch-branch', 'Api\\FetchController@branch');
$router->post('api/fetch-manager', 'Api\\FetchController@manager');

// 404 Handler
$router->notFound(function() {
    http_response_code(404);
    View::make('errors.404');
});
