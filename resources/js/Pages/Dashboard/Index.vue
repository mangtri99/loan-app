<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { DataTable } from "@/Components/ui/datatable";
import { columns } from "./States/column";
import { Loan } from "@/types/types";
import { Button } from "@/components/ui/button";

interface Props {
    loans: Loan[];
}

defineProps<Props>();
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
                    <DataTable :columns="columns" :data="loans" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
