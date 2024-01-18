export interface User {
    id: number
    name: string
    email: string
    email_verified_at: string
    role_id: number
}

export interface Status {
    id: number
    name: string
}

export interface Type {
    id: number
    name: string
}

export interface Repayment {
    id: number
    loan_id: number
    status_id: number
    amount: number
    due_date: Date
    paid_date: Date
    created_at: string
    updated_at: string
    status: Status
    loan: Loan
}

export interface Loan {
    id: number
    status_id: number
    type_id: number
    amount: number
    term: number
    balance: number
    created_at: string
    updated_at: string
    status: Status
    type: Type
    repayments: Repayment[]
    user: User
}
