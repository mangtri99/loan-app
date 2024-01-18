<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { DataTable } from "@/Components/ui/datatable";
import { columns } from "./States/column";
import { Loan, Status } from "@/types/types";
import { Button } from "@/components/ui/button";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Label } from "@/Components/ui/label";

interface Props {
    loans: Loan[];
    statuses: Status[];
    query?: any;
}

const {loans, statuses, query} = defineProps<Props>();

const form = useForm({
    // sync with query string
    status_id: query.status_id ? query.status_id : "",
});

function reset() {
    form.status_id = undefined;
    form.get(route("dashboard"));
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end mb-6">
                    <Button as-child>
                        <Link :href="route('loan.create')">Apply for Loan</Link>
                    </Button>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div class="flex justify-end items-end mb-4 space-x-4">
                        <div class="space-y-2">
                            <Label for="repayment">Status</Label>
                            <Select id="repayment" v-model:model-value="form.status_id" @update:model-value="form.get(route('dashboard'))">
                                <SelectTrigger class="w-[180px]">
                                    <SelectValue
                                        placeholder="Filter by status"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="(type, index) in statuses"
                                            :key="type.id"
                                            :value="String(type.id)"
                                        >
                                            {{ type.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Button variant="destructive" @click="reset">Reset</Button>
                        </div>
                    </div>
                    <DataTable :columns="columns" :data="loans" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
