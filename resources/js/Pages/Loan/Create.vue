<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Status, Type } from "@/types/types";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Button } from "@/components/ui/button";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import InputError from "@/Components/InputError.vue";

interface Props {
    types: Type[];
    terms: Type[];
}

defineProps<Props>();

const form = useForm("post", "/loan", {
    amount: "",
    term: "",
    type_id: "",
});

const submit = () =>
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            console.log("error");
        },
    });
</script>

<template>
    <Head title="Create Loan" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Create Loan
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <form @submit.prevent="submit">
                        <div class="lg:w-1/2 w-full space-y-4">
                            <div class="space-y-2">
                                <Label for="amount">Amount</Label>
                                <Input
                                    id="amount"
                                    v-model="form.amount"
                                    @change="form.validate('amount')"
                                    placeholder="e.g. 1000"
                                />
                                <InputError v-if="form.invalid('amount')" :message="form.errors.amount" />
                            </div>
                            <div class="space-y-2">
                                <Label for="term">Term</Label>
                                <Select id="term" v-model:model-value="form.term" @update:model-value="form.validate('term')">
                                    <SelectTrigger class="w-full">
                                        <SelectValue
                                            placeholder="Choose term"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="term in terms"
                                                :key="term.id"
                                                :value="String(term.id)"
                                            >
                                                {{ term.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError v-if="form.invalid('term')" :message="form.errors.term" />
                            </div>

                            <div class="space-y-2">
                                <Label for="repayment">Repayment Type</Label>
                                <Select id="repayment" v-model:model-value="form.type_id" @update:model-value="form.validate('type_id')">
                                    <SelectTrigger class="w-full">
                                        <SelectValue
                                            placeholder="Choose repayment type"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="(type, index) in types"
                                                :key="type.id"
                                                :value="String(type.id)"
                                            >
                                                {{ type.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError v-if="form.invalid('type_id')" :message="form.errors.type_id" />
                            </div>
                        </div>
                        <div class="flex justify-end mt-6">
                            <Button type="submit">Submit</Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
