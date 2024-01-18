import { Repayment } from "@/types/types";
import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import { ADMIN, PAID, PENDING } from "@/constant";
import { Badge } from '@/Components/ui/badge'
import dayjs from "dayjs";
import BtnAction from "../Components/BtnAction.vue";

export const columns: ColumnDef<Repayment>[] = [
    {
        accessorKey: "id",
        header: "ID",
    },
    {
        accessorKey: "amount",
        header: "Repayment",
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
        cell({row}) {
            // return props.row.original.status.name;
            return h(
                Badge, {
                    class: row.original.status_id === PENDING ? 'bg-red-500' : 'bg-green-500'
                },
                row.original.status.name
            );
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
    {
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const id = row.original.id;
            return h(
                BtnAction,
                {
                    id: id,
                    data: row.original,
                    // @ts-ignore
                    disabled: row.original.disabled || row.original.status_id === PAID ? true : false,
                    show: row.original.loan.status_id === PAID ?  false : true,
                },
                 "Pay"
            );
        },
        size: 100,
        maxSize: 100,
    },
];
