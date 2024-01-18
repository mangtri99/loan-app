<?php

use App\Models\Loan;
use App\Models\Repayment;
use App\Models\Role;
use App\Models\Status;
use App\Models\Type;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('can access dashboard and contains data', function () {
    $status = Status::factory()->create(['name' => 'Pending']);
    $type = Type::factory()->create(['name' => 'Weekly']);
    $user = User::factory()->create(['role_id' => Role::CUSTOMER]);
    $loan = Loan::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Dashboard/Index')
        ->has('loans')
    );
});

test('can access page create loan', function () {
    $status = Status::factory()->create(['name' => 'Pending']);
    $type = Type::factory()->create(['name' => 'Weekly']);
    $user = User::factory()->create(['role_id' => Role::CUSTOMER]);

    $response = $this->actingAs($user)->get('/loan/create');

    $response->assertStatus(200);

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Loan/Create')
        ->has('types')
        ->has('terms')
    );
});

test('can apply for a loan', function () {
    $status = Status::factory()->create(['name' => 'Pending']);
    $type = Type::factory()->create(['name' => 'Weekly']);
    $user = User::factory()->create(['role_id' => Role::CUSTOMER]);

    $response = $this->actingAs($user)->post('/loan', [
        'amount' => 1000,
        'term' => 1,
        'type_id' => Type::WEEKLY,
    ]);

    $response->assertStatus(302);
});

test('can access page detail loan', function () {
    $status = Status::factory()->create(['name' => 'Pending']);
    $type = Type::factory()->create(['name' => 'Weekly']);
    $user = User::factory()->create(['role_id' => Role::CUSTOMER]);
    $loan = Loan::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/loan/' . $loan->id . '/detail');

    $response->assertStatus(200);

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Loan/Show')
        ->has('loan')
    );
});

test('can approve a loan', function () {
    $status = Status::factory()->createMany([
        ['name' => 'PENDING'],
        ['name' => 'APPROVED'],
        ['name' => 'PAID'],
    ]);
    $type = Type::factory()->create(['name' => 'Weekly']);
    $user = User::factory()->create(['role_id' => Role::CUSTOMER]);
    $admin = User::factory()->create(['role_id' => Role::ADMIN]);
    $loan = Loan::factory()->create(['user_id' => $admin->id]);

    $response = $this->actingAs($user)->post('/loan/' . $loan->id . '/approve');

    $response->assertStatus(302);
});

test('can pay a loan', function () {
    $status = Status::factory()->createMany([
        ['name' => 'PENDING'],
        ['name' => 'APPROVED'],
        ['name' => 'PAID'],
    ]);
    $type = Type::factory()->create(['name' => 'Weekly']);
    $user = User::factory()->create(['role_id' => Role::CUSTOMER]);
    $admin = User::factory()->create(['role_id' => Role::ADMIN]);
    $loan = Loan::factory()->create(['user_id' => $admin->id]);
    for($i = 1; $i <= $loan->term; $i++) {
        Repayment::factory()->create([
            'amount' => $loan->amount / $loan->term,
            'loan_id' => $loan->id,
            'status_id' => Status::PENDING,
            'due_date' => now()->addWeeks($i),
        ]);
    }

    // get first repayment
    $repayment = Repayment::where('loan_id', $loan->id)->first();

    $response = $this->actingAs($user)->patch('/loan/' . $repayment->id );

    $response->assertStatus(200);

    // assert repayment status is paid
    $this->assertEquals(Status::PAID, $repayment->fresh()->status_id);
});
