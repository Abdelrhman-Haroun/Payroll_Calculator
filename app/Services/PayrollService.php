<?php

namespace App\Services;

use App\Contracts\PayrollServiceInterface;

class PayrollService implements PayrollServiceInterface
{
    private const EMPLOYEE_INSURANCE_RATE = 0.11;
    private const Martyrs_Fund_RATE = 0.0005;
    private const ANNUAL_PERSONAL_EXEMPTION = 20000;
    private const WORK_DAYS_PER_MONTH = 30;
    private const WORK_HOURS_PER_DAY = 8;

    /**
     * Progressive annual income-tax brackets.
     * Each bracket taxes only the slice of income within its own limit.
     */
    private const TAX_BRACKETS = [
        ['limit' => 40000,       'rate' => 0.000],
        ['limit' => 15000,       'rate' => 0.100],
        ['limit' => 15000,       'rate' => 0.150],
        ['limit' => 200000,      'rate' => 0.200],
        ['limit' => 200000,      'rate' => 0.225],
        ['limit' => 300000,      'rate' => 0.250],
        ['limit' => 300000,      'rate' => 0.275],
        ['limit' => PHP_INT_MAX, 'rate' => 0.300],
    ];

    public function calculateEmployeeInsurance(float $insuranceSalary): float
    {
        return $insuranceSalary * self::EMPLOYEE_INSURANCE_RATE;
    }

    public function calculateMartyrs_Fund(float $grossSalary): float
    {
        return $grossSalary * self::Martyrs_Fund_RATE;
    }

    public function calculateTax(float $taxableSalary): float
    {
        $annualSalary = ($taxableSalary * 12) - self::ANNUAL_PERSONAL_EXEMPTION;

        if ($annualSalary <= 0) {
            return 0.0;
        }

        $tax = 0.0;

        foreach (self::TAX_BRACKETS as $bracket) {
            if ($annualSalary <= 0) {
                break;
            }

            $taxableAmount = min($annualSalary, $bracket['limit']);
            $tax += $taxableAmount * $bracket['rate'];
            $annualSalary -= $taxableAmount;
        }

        return round($tax / 12, 2);
    }

    public function calculateOvertime(float $basicSalary, float $hours, float $rate = 1.5): float
    {
        $hourlyRate = $basicSalary / self::WORK_DAYS_PER_MONTH / self::WORK_HOURS_PER_DAY;

        return $hourlyRate * $hours * $rate;
    }

    public function calculateNetSalary(array $data): float
    {
        return $data['grossSalary']
            - $data['employeeInsurance']
            - $data['tax']
            - $data['martyrs_Fund']
            - $data['otherDeductions'];
    }

    public function calculatePayroll(array $data): array
    {
        $overtime = $this->calculateOvertime(
            $data['basicSalary'],
            $data['overtimeHours'],
            $data['overtimeRate']
        );

        $grossSalary = $data['basicSalary']
            + $data['allowances']
            + $overtime
            + $data['otherEarnings'];

        $employeeInsurance = $this->calculateEmployeeInsurance($data['insuranceSalary']);

        $taxableSalary = $grossSalary - $employeeInsurance;
        $tax = $this->calculateTax($taxableSalary);
        $martyrs_Fund = $this->calculateMartyrs_Fund($grossSalary);
        $netSalary = $this->calculateNetSalary([
            'grossSalary'       => $grossSalary,
            'employeeInsurance' => $employeeInsurance,
            'tax'               => $tax,
            'martyrs_Fund'      => $martyrs_Fund,
            'otherDeductions'   => $data['otherDeductions'],
        ]);

        return [
            'basicSalary'       => $data['basicSalary'],
            'allowances'        => $data['allowances'],
            'overtime'          => $overtime,
            'otherEarnings'     => $data['otherEarnings'],
            'grossSalary'       => $grossSalary,
            'insuranceSalary'   => $data['insuranceSalary'],
            'taxableSalary'     => $taxableSalary,
            'employeeInsurance' => $employeeInsurance,
            'tax'               => $tax,
            'martyrs_Fund'      => $martyrs_Fund,
            'otherDeductions'   => $data['otherDeductions'],
            'netSalary'         => $netSalary,
        ];
    }
}
