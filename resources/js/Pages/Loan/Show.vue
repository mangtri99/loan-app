<script setup lang="ts">
import { Label } from "@/Components/ui/label";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { PAID, PENDING, CUSTOMER, ADMIN } from "@/constant";
import { Loan } from "@/types/types";
import { Head } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { computed } from "vue";
import { columns } from "./States/column";
import { DataTable } from "@/Components/ui/datatable";
import { useToast } from "@/components/ui/toast/use-toast";
import { Button } from "@/components/ui/button";
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from "@/components/ui/alert-dialog";
import { ref } from "vue";

interface Props {
    loan: Loan;
}

const { loan } = defineProps<Props>();
const { toast } = useToast();
const open = ref(false);

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

const latestPaidDate = computed(() => {
    const repayment = loan.repayments.filter(
        (repayment) => repayment.status_id === PAID
    );

    // get the latest paid date
    const latestPaid = repayment.reduce((acc, cur) => {
        if (acc < cur.created_at) {
            return cur.created_at;
        } else {
            return acc;
        }
    }, "");

    if (latestPaid) {
        return dayjs(latestPaid).format("MMMM DD, YYYY");
    } else {
        return "No payment yet";
    }
});

function confirmApprove() {
    open.value = true;
}

function approve() {
    toast({
        title: "Scheduled: Catch up",
        description: "Friday, February 10, 2023 at 5:57 PM",
    });
    open.value = false;
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
                    <Button variant="destructive" @click="() => {}">
                        Approve
                    </Button>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div class="flex items-center mb-4">
                            <p class="font-medium">
                                Customer Name :
                                {{
                                    loan.user.name
                                }}
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
                            <p class="font-medium">{{ loan.status.name }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label>Loan Type</Label>
                            <p class="font-medium">{{ loan.type.name }}</p>
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
                            <Label>Latest Payment</Label>
                            <p class="font-medium">
                                {{ latestPaidDate }}
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
        <AlertDialog>
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
                    <AlertDialogAction @click="confirmApprove"
                        >Approve</AlertDialogAction
                    >
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AuthenticatedLayout>
</template>
