<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRequest;
use App\Models\Loan;
use App\Models\Role;
use App\Models\Status;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $loans = Loan::with(['user', 'repayments.status', 'status', 'type']);

        if(auth()->user()->role_id == Role::CUSTOMER) {
            $loans = $loans->where('user_id', auth()->user()->id)->get();
        } else {
            $loans = $loans->get();
        }
        return Inertia::render('Dashboard/Index', [
            'loans' => $loans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $optionTerms = collect(Loan::LOAN_TERMS)->map(function ($term) {
            return [
                'id' => $term,
                'name' => $term . ' x',
            ];
        });
        return Inertia::render('Loan/Create',
            [
                'types' => Type::all(),
                'terms' => $optionTerms
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoanRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();

            $data['user_id'] = auth()->user()->id;
            $data['status_id'] = Status::PENDING;
            $data['balance'] = $data['amount'];
            $loan = Loan::create($data);

            // create repayments based on term
            $repaymentAmount = $loan->amount / $loan->term;
            $repayments = [];
            for ($i = 1; $i <= $loan->term; $i++) {
                // loan amount / term
                // e.g. 1000 / 3 = 333.33
                $repaymentAmount = $loan->amount / $loan->term;;
                // format to 2 decimal places
                $repaymentAmount = number_format((float) $repaymentAmount, 2, '.', '');

                // if last term, add the remainder
                // e.g. 333.33 + 333.33 + 333.34 = 1000
                if($i == $loan->term) {
                    $repaymentAmount = $repaymentAmount + ($loan->amount - ($repaymentAmount * $loan->term));
                }

                $due_date = $loan->created_at;

                // If weekly, add 1 week for each term
                if($loan->type_id == Type::WEEKLY){
                    // add 1 week for each term
                    // e.g. 2021-01-01 + 1 week = 2021-01-08
                    $due_date = $due_date->addWeeks($i);
                // If monthly, add 1 month for each term
                } else {
                    // add 1 month for each term
                    // e.g. 2021-01-01 + 1 month = 2021-02-01
                    $due_date = $due_date->addMonths($i);
                }

                $repayments[] = [
                    'loan_id' => $loan->id,
                    'amount' => $repaymentAmount,
                    'status_id' => Status::PENDING,
                    'due_date' => $due_date,
                ];
            }
            $loan->repayments()->createMany($repayments);
            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Loan created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Loan failed to create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = Loan::with(['user', 'repayments.status', 'status', 'type'])->findOrFail($id);
        return Inertia::render('Loan/Show', [
            'loan' => $loan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $loan = Loan::findOrFail($id);

            if($loan->balance == 0) {
                return redirect()->back()->with('error', 'Loan is already fully paid');
            }

            $this->validate($request, [
                'amount' => 'required|numeric|min:100000|max:100000000',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Loan failed to update');
        }
    }

    public function approve(string $id)
    {
        try {
            DB::beginTransaction();
            $loan = Loan::findOrFail($id);
            $loan->status_id = Status::APPROVED;
            $loan->save();

            // also approve all repayments
            $loan->repayments()->update(['status_id' => Status::APPROVED]);
            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Loan approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Loan failed to approve');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
