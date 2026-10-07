<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Payable;
use App\Models\Receivable;

class BusinessContextBuilder
{
    public function build(): string
    {
        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        $customers = Customer::with('receivables')->get();

        /*
        |--------------------------------------------------------------------------
        | Receivables
        |--------------------------------------------------------------------------
        */

        $receivables = Receivable::with('customer')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Payables
        |--------------------------------------------------------------------------
        */

        $payables = Payable::query()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Summary Calculations
        |--------------------------------------------------------------------------
        */

        $totalCustomers = $customers->count();

        $totalReceivables = $receivables
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('amount');

        $totalPayables = $payables
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('amount');

        $pendingReceivables = $receivables
            ->where('status', 'pending')
            ->sum('amount');

        $overdueReceivables = $receivables
            ->where('status', 'overdue')
            ->sum('amount');

        $pendingPayables = $payables
            ->where('status', 'pending')
            ->sum('amount');

        $overduePayables = $payables
            ->where('status', 'overdue')
            ->sum('amount');

        $netOutstanding = $totalReceivables - $totalPayables;

        /*
        |--------------------------------------------------------------------------
        | Customer Context
        |--------------------------------------------------------------------------
        */

        $customerContext = '';

        foreach ($customers as $customer) {
            $customerReceivables = $customer->receivables
                ->whereIn('status', ['pending', 'overdue'])
                ->sum('amount');

            $customerContext .= sprintf(
                "- %s | Company: %s | Email: %s | Phone: %s | Outstanding: Rs. %s\n",
                $customer->name,
                $customer->company ?? 'N/A',
                $customer->email ?? 'N/A',
                $customer->phone ?? 'N/A',
                number_format($customerReceivables, 2)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Receivable Context
        |--------------------------------------------------------------------------
        */

        $receivableContext = '';

        foreach ($receivables as $receivable) {
            $receivableContext .= sprintf(
                "- Customer: %s | Amount: Rs. %s | Status: %s | Due Date: %s | Description: %s\n",
                $receivable->customer?->name ?? 'Unknown',
                number_format($receivable->amount, 2),
                $receivable->status,
                $receivable->due_date?->format('Y-m-d') ?? 'N/A',
                $receivable->description ?? 'N/A'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payable Context
        |--------------------------------------------------------------------------
        */

        $payableContext = '';

        foreach ($payables as $payable) {
            $payableContext .= sprintf(
                "- Supplier: %s | Amount: Rs. %s | Status: %s | Due Date: %s | Description: %s\n",
                $payable->supplier_name,
                number_format($payable->amount, 2),
                $payable->status,
                $payable->due_date?->format('Y-m-d') ?? 'N/A',
                $payable->description ?? 'N/A'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Final Business Context
        |--------------------------------------------------------------------------
        */

        return <<<CONTEXT
BUSINESS INFORMATION

CURRENT BUSINESS SUMMARY
------------------------
Total Customers: {$totalCustomers}
Total Outstanding Receivables: Rs. {$this->formatAmount($totalReceivables)}
Total Outstanding Payables: Rs. {$this->formatAmount($totalPayables)}
Net Outstanding Position: Rs. {$this->formatAmount($netOutstanding)}

RECEIVABLE SUMMARY
------------------
Pending Receivables: Rs. {$this->formatAmount($pendingReceivables)}
Overdue Receivables: Rs. {$this->formatAmount($overdueReceivables)}

PAYABLE SUMMARY
---------------
Pending Payables: Rs. {$this->formatAmount($pendingPayables)}
Overdue Payables: Rs. {$this->formatAmount($overduePayables)}

CUSTOMERS
---------
{$customerContext}

RECEIVABLE DETAILS
------------------
{$receivableContext}

PAYABLE DETAILS
---------------
{$payableContext}

IMPORTANT INSTRUCTIONS
----------------------
This information represents the current business data available to the application.

Use this data when answering business-related questions.

Do not invent customers, amounts, suppliers, invoices, or financial information.

If the requested information is not available in the provided business context, clearly state that the information is not available.

CONTEXT;
    }

    private function formatAmount($amount): string
    {
        return number_format((float) $amount, 2);
    }
}
