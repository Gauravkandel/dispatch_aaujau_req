<?php

namespace App\Exports;
use App\Model\AgentPayout;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
class AgentPayoutRequestListExport implements FromCollection, WithMapping, WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $agents = AgentPayout::with(['agent', 'currencyDetails', 'payoutOption', 'payoutBankDetails'=> function($q){
            $q->where('status', 1);
        }])->where('status', 0)->orderBy('id', 'DESC')->get();
        return $agents;
    }
    public function headings(): array{
        return [
            getAgentNomenclature().' Name',
            'Amount',
            'Payout Type',
            'Account holder Name',
            'Bank Account Number',
            'IFSC Code',
            'Bank Name'
        ];
    }

    public function map($agents): array
    {
        return [
            $agents->agent->name,
            number_format($agents->amount, 2),
            $agents->payoutOption->title,
            $agents->payoutBankDetails->first() ? $agents->payoutBankDetails->first()->beneficiary_name : '',
            $agents->payoutBankDetails->first() ? $agents->payoutBankDetails->first()->beneficiary_account_number : '',
            $agents->payoutBankDetails->first() ? $agents->payoutBankDetails->first()->beneficiary_ifsc : '',
            $agents->payoutBankDetails->first() ? $agents->payoutBankDetails->first()->beneficiary_bank_name : '',
        ];
    }
    
}
