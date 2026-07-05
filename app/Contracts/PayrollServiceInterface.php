<?php

namespace App\Contracts;

interface PayrollServiceInterface
{
    public function calculateEmployeeInsurance(float $insuranceSalary): float;

    public function calculateTax(float $taxableSalary): float;

    public function calculateOvertime(float $basicSalary, float $hours, float $rate = 1.5): float;

    public function calculateNetSalary(array $data): float;

    public function calculatePayroll(array $data): array;
}
