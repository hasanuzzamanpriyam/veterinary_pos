<?php

namespace App\Livewire\Employee;

use App\Models\Employee;
use App\Models\EmployeeLedger as ModelsEmployeeLedger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmployeeLedger extends Component
{
    public $employees = [];
    public $employee_info = null; // object or null
    public $employee_id = null;   // store selected employee id
    public $payment_method = null;
    public $remarks = null;
    public $amount = 0;
    public $balance = 0;
    public $date = null; // will store Y-m-d (DB friendly)
    public $payment_types = [];

    public function mount()
    {
        $this->employees = Employee::select('id','name','mobile','address','balance')->get();
        $this->date = now()->format('Y-m-d'); // internal format Y-m-d
        $this->payment_types = ['Cash', 'Bank', 'Mobile Banking']; // adjust as needed
    }

    public function rules()
    {
        return [
            'employee_id'    => 'required|exists:employees,id',
            'payment_method' => 'required|string',
            'amount'         => 'required|numeric|gt:0',
            'date'           => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required'    => 'Employee is required',
            'employee_id.exists'      => 'Selected employee not found',
            'payment_method.required' => 'Payment method is required',
            'amount.required'         => 'Amount is required',
            'amount.gt'               => 'Amount must be greater than zero',
            'date.required'           => 'Date is required',
            'date.date'               => 'Date must be a valid date',
        ];
    }

    /**
     * Called from JS when select2 changes.
     */
    public function searchEmployee($employee_id)
    {
        $this->employee_id = $employee_id;

        if ($employee_id) {
            $this->employee_info = Employee::select('id','name','mobile','address','designation','balance')
                ->find($employee_id);

            $this->balance = $this->employee_info->balance ?? 0;
        } else {
            $this->employee_info = null;
            $this->balance = 0;
        }

        // debug
        Log::debug('EmployeeLedger.searchEmployee', [
            'employee_id' => $this->employee_id,
            'has_info' => (bool)$this->employee_info,
            'balance' => $this->balance,
        ]);
    }

    /**
     * Live preview calculation of new balance as user types amount.
     * Accepts raw typed string (so we sanitize).
     */
    public function dueCalculation($amount)
    {
        $clean_number = preg_replace("/[^0-9.]/", "", $amount);
        $this->amount = $clean_number !== '' ? floatval($clean_number) : 0;

        $originalBalance = $this->employee_info->balance ?? 0;
        $this->balance = $originalBalance - $this->amount;

        Log::debug('EmployeeLedger.dueCalculation', [
            'typed' => $amount,
            'clean' => $this->amount,
            'original_balance' => $originalBalance,
            'new_balance' => $this->balance,
        ]);
    }

    /**
     * Accepts dd-mm-yyyy (from JS) or Y-m-d (if provided).
     * We will always normalize and store as Y-m-d internally.
     * This method can be called from JS: @this.set('date', 'convertedValue');
     */
    public function setDateFromPicker($dateString)
    {
        // dateString may be in dd-mm-yyyy (from datepicker)
        // try dd-mm-yyyy first, then fallback to Y-m-d
        try {
            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateString)) {
                $parsed = Carbon::createFromFormat('d-m-Y', $dateString);
                $this->date = $parsed->format('Y-m-d');
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                $this->date = Carbon::createFromFormat('Y-m-d', $dateString)->format('Y-m-d');
            } else {
                // try Carbon parse
                $this->date = Carbon::parse($dateString)->format('Y-m-d');
            }

            Log::debug('EmployeeLedger.setDateFromPicker', [
                'input' => $dateString,
                'stored_date' => $this->date,
            ]);
        } catch (\Throwable $e) {
            Log::error('EmployeeLedger.setDateFromPicker: failed to parse date', [
                'input' => $dateString,
                'error' => $e->getMessage(),
            ]);
            // keep previous date and set a flash message so developer can see it
            session()->flash('error', 'Invalid date format: ' . $dateString);
        }
    }

    public function store()
    {
        // debug: log before validation
        Log::debug('EmployeeLedger.store - incoming', [
            'employee_id' => $this->employee_id,
            'payment_method' => $this->payment_method,
            'amount' => $this->amount,
            'date' => $this->date,
            'remarks' => $this->remarks,
        ]);

        $this->validate();

        try {
            DB::transaction(function () {
                $employee = Employee::find($this->employee_id);

                if (! $employee) {
                    throw new \Exception('Employee not found when storing ledger.');
                }

                // decrement balance
                $employee->decrement('balance', $this->amount);

                // insert ledger entry - ensure fillable in model
                ModelsEmployeeLedger::create([
                    'employee_id'    => $employee->id,
                    'payment_method' => $this->payment_method,
                    'type'           => 'payment',
                    'amount'         => $this->amount,
                    'remarks'        => $this->remarks,
                    'date'           => Carbon::createFromFormat('Y-m-d', $this->date)->format('Y-m-d'),
                ]);
            });

            $notification = ['msg' => 'Transaction added successfully', 'alert-type' => 'success'];
            Log::info('EmployeeLedger.store - success', [
                'employee_id' => $this->employee_id,
                'amount' => $this->amount,
                'date' => $this->date,
            ]);

            return redirect()->route('employee.payment.list')->with($notification);

        } catch (\Throwable $e) {
            Log::error('EmployeeLedger.store - failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'state' => [
                    'employee_id' => $this->employee_id,
                    'amount' => $this->amount,
                    'date' => $this->date,
                ],
            ]);

            session()->flash('error', 'Failed to save transaction: ' . $e->getMessage());
            // don't rethrow so user sees friendly error; you may rethrow in dev
        }
    }

    public function render()
    {
        return view('livewire.employee.employee-ledger', [
            'employees' => $this->employees,
            'employee_info' => $this->employee_info,
            'balance' => $this->balance,
            'payment_types' => $this->payment_types,
        ])->extends('layouts.admin')->section('main-content');
    }
}
