<script setup>
import { ref } from "vue";
import bookingService from "@/services/bookingService";

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";

import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";

const props = defineProps({
    open: Boolean,
    booking: Object,
});

const emit = defineEmits(["update:open", "success"]);

const loading = ref(false);

const form = ref({
    reason: "",
});

const closeDialog = () => {
    form.value.reason = "";
    emit("update:open", false);
};

const submitCancel = async () => {
    try {
        loading.value = true;

        // jika backend belum memakai reason,
        // cukup kirim id saja
        await bookingService.cancel(props.booking.id);

        emit("success");

        form.value.reason = "";

        emit("update:open", false);
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-xl">
            <DialogHeader>
                <DialogTitle> Cancel Booking </DialogTitle>

                <DialogDescription>
                    Booking yang dibatalkan tidak dapat digunakan kembali.
                </DialogDescription>
            </DialogHeader>

            <div v-if="booking" class="space-y-5">
                <Card>
                    <CardHeader>
                        <CardTitle> Booking Information </CardTitle>
                    </CardHeader>

                    <CardContent>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Booking Code
                                </p>

                                <p class="font-medium">
                                    {{ booking.booking_code }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Status
                                </p>

                                <Badge>
                                    {{ booking.status }}
                                </Badge>
                            </div>

                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Booked By
                                </p>

                                <p class="font-medium">
                                    {{ booking.user?.name }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Schedule
                                </p>

                                <p class="font-medium">
                                    {{ booking.start_time }}
                                    <br />
                                    {{ booking.end_time }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div class="space-y-2">
                    <Label> Cancellation Reason </Label>

                    <Textarea
                        v-model="form.reason"
                        rows="4"
                        placeholder="Masukkan alasan pembatalan (opsional)"
                    />
                </div>
            </div>

            <DialogFooter>
                <Button variant="outline" @click="closeDialog"> Close </Button>

                <Button
                    variant="destructive"
                    :disabled="loading"
                    @click="submitCancel"
                >
                    {{ loading ? "Cancelling..." : "Cancel Booking" }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
