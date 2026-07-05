<?php

namespace App\Http\Controllers;

use App\Contracts\PayrollServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    /**
     * Validation rules for the calculate request.
     */
    private const VALIDATION_RULES = [
        'basic_salary'     => 'required|numeric|min:0',
        'allowances'       => 'nullable|numeric|min:0',
        'overtime_hours'   => 'nullable|numeric|min:0',
        'overtime_rate'    => 'nullable|numeric|min:1',
        'other_earnings'   => 'nullable|numeric|min:0',
        'insurance_salary' => 'required|numeric|min:0',
        'other_deductions' => 'nullable|numeric|min:0',
    ];

    /**
     * Defaults applied to optional fields when omitted from the request.
     */
    private const DEFAULTS = [
        'allowances'       => 0,
        'overtime_hours'   => 0,
        'overtime_rate'    => 1.5,
        'other_earnings'   => 0,
        'other_deductions' => 0,
    ];

    public function __construct(
        private readonly PayrollServiceInterface $payrollService
    ) {
    }

    public function index(): View
    {
        return view('payroll', [
            'result' => session('result'),
        ]);
    }

    public function calculate(Request $request): RedirectResponse
    {
        $validated = array_merge(
            self::DEFAULTS,
            $request->validate(self::VALIDATION_RULES)
        );

        $result = $this->payrollService->calculatePayroll([
            'basicSalary'     => (float) $validated['basic_salary'],
            'allowances'      => (float) $validated['allowances'],
            'insuranceSalary' => (float) $validated['insurance_salary'],
            'overtimeHours'   => (float) $validated['overtime_hours'],
            'overtimeRate'    => (float) $validated['overtime_rate'],
            'otherEarnings'   => (float) $validated['other_earnings'],
            'otherDeductions' => (float) $validated['other_deductions'],
        ]);

        return redirect()
            ->route('payroll.index')
            ->withInput()
            ->with('result', $result);
    }
}
