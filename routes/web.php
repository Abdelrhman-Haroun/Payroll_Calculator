<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayrollController;

Route::get('/', function () {
    return redirect('/payroll');
});

Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');

Route::post('/payroll', [PayrollController::class, 'calculate'])->name('payroll.calculate');
