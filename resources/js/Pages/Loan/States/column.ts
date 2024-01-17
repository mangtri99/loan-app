import { Repayment } from "@/types/types";
import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import { Button } from "@/Components/ui/button";
import { Link } from "@inertiajs/vue3";
import { PENDING } from "@/constant";
import dayjs from "dayjs";

export const columns: ColumnDef<Repayment>[] = [
    {
        accessorKey: "id",
        header: "ID",
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
        accessorKey: "due_date",
        header: "Due Date",
        cell({row}) {
            const dueDate = row.original.due_date
            return dayjs(dueDate).format("DD/MM/YYYY")
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
        accessorKey: "paid_date",
        header: "Payment Date",
        cell({row}) {
            const paidDate = row.original.paid_date
            if(paidDate){
                return dayjs(paidDate).format("DD/MM/YYYY")
            } else {
                return "-"
            }
        },
    },
    // {
    //     id: "actions",
    //     enableHiding: false,
    //     cell: ({ row }) => {
    //         const id = row.original.id;
    //         return h(
    //             Button,
    //             {
    //                 asChild: true,
    //             },
    //             h(Link, { href: route("loan.show", id), target: '_blank' }, "View")
    //         );
    //     },
    // },
];
