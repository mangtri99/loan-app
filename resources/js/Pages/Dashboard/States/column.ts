import { Loan } from "@/types/types";
import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import { Button } from "@/Components/ui/button";
import { Link } from "@inertiajs/vue3";
import { PENDING } from "@/constant";
import dayjs from "dayjs";

export const columns: ColumnDef<Loan>[] = [
    {
        accessorKey: "id",
        header: "ID",
    },
    {
        accessorKey: "user.name",
        header: "Customer",
        enableHiding: true,
    },
    {
        accessorKey: "amount",
        header: "Loan",
        cell(props) {
            return props.row.original.amount.toLocaleString("en-US", {
                style: "currency",
                currency: "USD",
            });
        },
    },
    {
        header: "Remaining Term",
        cell({row}) {
            const remainingTerm = row.original.repayments.filter((repayment) => {
                return repayment.status_id === PENDING
            })
            return `${remainingTerm.length} of ${row.original.term}`
        },
    },
    {
        header: "Remaining Debt",
        cell({row}) {
            return row.original.balance.toLocaleString("en-US", {
                style: "currency",
                currency: "USD",
            })
        },
    },
    {
        header: "Next Repayment Date",
        cell({row}) {
            const nextRepayment = row.original.repayments.filter((repayment) => {
                return repayment.status_id === PENDING
            })
            return dayjs(nextRepayment[0].due_date).format("DD/MM/YYYY")
        },
    },
    {
        header: "Last Repayment Date",
        cell({row}) {
            const lastRepayment = row.original.repayments[row.original.repayments.length - 1]
            return dayjs(lastRepayment.due_date).format("DD/MM/YYYY")
        },
    },
    {
        accessorKey: "status.name",
        header: "Status",
        cell(props) {
            return props.row.original.status.name;
        },
    },
    {
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const id = row.original.id;
            return h(
                Button,
                {
                    asChild: true,
                },
                h(Link, { href: route("loan.show", id), target: '_blank' }, "View")
            );
        },
    },
];
