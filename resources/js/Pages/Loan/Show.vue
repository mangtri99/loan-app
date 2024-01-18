<script setup lang="ts">
import { Label } from "@/Components/ui/label";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { PAID, PENDING, CUSTOMER, ADMIN, APPROVED } from "@/constant";
import { Loan } from "@/types/types";
import { Head, useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { computed } from "vue";
import { columns } from "./States/column";
import { DataTable } from "@/Components/ui/datatable";
import { useToast } from "@/Components/ui/toast/use-toast";
import { Button } from "@/components/ui/button";
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from "@/Components/ui/alert-dialog";
import { ref } from "vue";
import { Badge } from "@/Components/ui/badge";

interface Props {
    loan: Loan;
}

const { loan } = defineProps<Props>();
const { toast } = useToast();
const open = ref(false);
const form = useForm({});

const remainingTerm = computed(() => {
    const repayment = loan.repayments.filter(
        (repayment) => repayment.status_id === PENDING
    ).length;
    return repayment;
});

const remainingCredit = computed(() => {
    const repayment = loan.repayments.filter(
        (repayment) => repayment.status_id === PENDING
    );
    const credit = repayment.reduce((acc, cur) => acc + cur.amount, 0);
    return credit;
});

const nextPaymentDate = computed(() => {
    const repayment = loan.repayments.filter(
        (repayment) => repayment.status_id === PENDING
    );

    if(repayment.length > 0) {
        return dayjs(repayment[0].due_date).format("MMMM DD, YYYY");
    } else {
        return "All paid";
    }

});

function confirmApprove() {
    open.value = true;
}

function approve() {
    form.post(route("loan.approve", loan.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: "Loan Approved",
                description: "You have successfully approved this loan"
            });
            open.value = false;
        },
        onError: () => {
            toast({
                title: "Something went wrong",
                description: "Please try again later",
            });
            open.value = false;
        },
    });

}
</script>

<template>
    <Head title="Show Loan" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Loan ID: #{{ loan.id }}
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div
                    class="flex justify-end mb-6"
                    v-if="$page.props.auth.user.role_id === ADMIN && loan.status_id === PENDING"
                >
                    <Button variant="destructive" @click="confirmApprove">
                        Approve
                    </Button>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div class="flex items-center mb-4">
                            <p class="font-medium">
                                Customer Name : {{ loan.user.name }}
                            </p>
                        </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <div class="space-y-2">
                            <Label>Amount</Label>
                            <p class="font-medium">
                                {{
                                    loan.amount.toLocaleString("en-US", {
                                        style: "currency",
                                        currency: "USD",
                                    })
                                }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label>Status</Label>
                            <Badge :class="{
                                    'bg-red-500 hover:bg-red-500': loan.status_id === PENDING,
                                    'bg-green-500 hover:bg-green-500': loan.status_id === PAID,
                                    'bg-yellow-500 hover:bg-yellow-500': loan.status_id === APPROVED

                            }">
                                {{
                                    loan.status.name
                                }}
                            </Badge>
                        </div>
                        <div class="space-y-2">
                            <Label>Loan Type</Label>
                            <p class="font-medium">
                                <Badge class="bg-blue-500 hover:bg-bl">
                                    {{ loan.type.name }}
                                </Badge>
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label>Remaining Term</Label>
                            <p class="font-medium">
                                {{ remainingTerm }} of {{ loan.term }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label>Remaining Credit</Label>
                            <p class="font-medium">
                                {{
                                    remainingCredit.toLocaleString("en-US", {
                                        style: "currency",
                                        currency: "USD",
                                    })
                                }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label>Next Payment</Label>
                            <p class="font-medium">
                                {{ nextPaymentDate }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <p class="text-lg font-medium mb-4">Repayment List</p>
                    <DataTable :columns="columns" :data="loan.repayments" />
                </div>
            </div>
        </div>
        <AlertDialog :open="open">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle
                        >Are you sure, to approve this
                        {{
                            loan.amount.toLocaleString("en-US", {
                                style: "currency",
                                currency: "USD",
                            })
                        }} loan?</AlertDialogTitle
                    >
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="open = false"
                        >Cancel</AlertDialogCancel
                    >
                    <AlertDialogAction @click="approve"
                        >Approve</AlertDialogAction
                    >
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AuthenticatedLayout>
</template>
