<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get all loans
        $loans = \App\Models\Loan::all();

        // loop through each loan
        foreach ($loans as $loan) {
            // get term of loan
            $term = $loan->term; // e.g. 3

            // create repayment for each loan
            for($i = 1; $i <= $term; $i++) {
                // loan amount / term
                // e.g. 1000 / 3 = 333.33
                $amount = $loan->amount / $term;
                // fix to 2 decimal places
                $amount = (float) number_format($amount, 2);

                // if last term, add the remainder
                // e.g. 333.33 + 333.33 + 333.34 = 1000
                if($i == $term) {
                    $amount = $amount + ($loan->amount - ($amount * $term));
                }

                $due_date = $loan->created_at;
                // add 1 week for each term
                // e.g. 2021-01-01 + 1 week = 2021-01-08
                $due_date = $due_date->addWeeks($i);

                \App\Models\Repayment::create([
                    'loan_id' => $loan->id,
                    'amount' => $amount,
                    'due_date' => $due_date,
                    'status_id' => Status::PENDING,
                ]);
            }
        }

    }
}
