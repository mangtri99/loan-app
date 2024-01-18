<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";
import { useToast } from "@/components/ui/toast/use-toast";
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { ref } from "vue";
import { CUSTOMER } from "@/constant";

interface Props {
    id: number;
    disabled?: boolean;
    data?: any;
    show?: boolean;
}
const { id, disabled, data, show = true } = defineProps<Props>();
const form = useForm({});
const { toast } = useToast();
const open = ref(false);

function submit() {
    form.patch(route('loan.update', id), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: "Paid Successfully",
                description: "The loan has been paid successfully",
            });
            open.value = false;
        },
        onError: () => {
            open.value = false;
            console.log("error");
            toast({
                title: "Something went wrong",
                description: "The loan has not been paid successfully",
            });
        },
    });
}

</script>

<template>
    <div v-if="$page.props.auth.user.role_id === CUSTOMER" v-show="show">
        <Button :disabled="disabled" type="button" class="bg-green-500 hover:bg-green-600" @click="open = true">
            <slot/>
        </Button>
        <AlertDialog :open="open">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle
                        >You will make a payment of
                        {{
                            data ? data.amount.toLocaleString("en-US", {
                                style: "currency",
                                currency: "USD",
                            }) : ""
                        }}, continue?</AlertDialogTitle
                    >
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="open = false"
                        >Cancel</AlertDialogCancel
                    >
                    <AlertDialogAction @click="submit"
                        >Pay</AlertDialogAction
                    >
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
