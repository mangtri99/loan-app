## Loan App Laravel + Inertia + VueJS

This is simple application to request and manage loan. Customer can easyly request a loan and Administrator also easyly manage every loan

## Prerequisite
PHP version : 8.2
Node version: 18.17
Database MySQL version: 5.7
Package Manager: NPM

## Tech Stack
Laravel
Inertia 
VueJS
MySQL

## How to get started

After clone this repository, setup new database `loan_app` and then simply run

```bash
cp .env.example .env
composer install
npm run install
php artisan key:generate
```

To generate table and dummy data
```bash
php artisan migrate --seed
```

Run Application
```bash
php artisan serve
npm run dev
```

For Testing
```bash
php artisan test
```

Access application : http://localhost:8000/

Demo Account:
Administrator : admin@mail.com / password
Customer      : customer@mail.com / password

## Database 
The following table is contained in the database

Table User
- store all users in the application. used for login on the application

Table Loan
- store all request loan from customer

Table Repayment
- detail of repayment loan based on term choosed by customer. The amount of loan will divided by term choosed by customer

Table Role
- master data roles. option role for users consist of administrator and customer

Table Type
- master data type of loan. customer can choose time cylce for pay they repayment of loan. currently only weekly available

Table Status
- master data status of loan. This indicate status of loan and repayment. the statuses consist of PENDING, APRROVED, PAID

## Flow App
1. customer login to the application and apply for a loan
2. customer can request amount and term for loan
3. after submit the loan. will generate repayment based on term they choosed
4. the loan will have status PENDING, in this state customer cant pay they loan. administrator need to approve the loan
5. for the administrator, admin can approve a loan. 
6. back to customer, after the loan approved by administrator, the can pay loan. customer must pay repayment sequentially.
7. After all repayment PAID, the loan status become PAID
