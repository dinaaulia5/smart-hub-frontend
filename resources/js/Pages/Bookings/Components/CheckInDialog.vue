<script setup>
import { ref, watch } from "vue";
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
import checkInService from "@/services/checkInService";

const props = defineProps({
    open: Boolean,
    booking: Object,
});

const emit = defineEmits(["update:open", "success"]);

const checkInForm = ref({
    booking_item_id: "",
    note: "",
});

watch(
    () => props.booking,
    (booking) => {
        if (!booking) return;

        checkInForm.value.booking_item_id = booking.items[0]?.id ?? "";

        checkInForm.value.note = "";
    },
    { immediate: true },
);

const loading = ref(false);

const submitCheckIn = async () => {
    try {
        loading.value = true;

        await checkInService.create(checkInForm.value);

        emit("success");
        emit("update:open", false);

        checkInForm.value = {
            booking_item_id: "",
            note: "",
        };
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const closeDialog = () => {
    emit("update:open", false);
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-2xl">
            <DialogHeader>
                <DialogTitle>Check In Booking</DialogTitle>

                <DialogDescription>
                    Pastikan informasi booking sudah sesuai sebelum melakukan
                    proses check in.
                </DialogDescription>
            </DialogHeader>

            <div v-if="booking" class="space-y-5">
                <!-- Booking Information -->
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

                <!-- Booking Items -->
                <Card>
                    <CardHeader>
                        <CardTitle> Booking Items </CardTitle>
                    </CardHeader>

                    <CardContent>
                        <div
                            v-for="item in booking.items"
                            :key="item.id"
                            class="rounded-lg border p-4 mb-3 last:mb-0"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold">
                                        {{ item.bookable?.name }}
                                    </h4>

                                    <p class="text-sm text-muted-foreground">
                                        {{ item.bookable_type }}
                                    </p>
                                </div>

                                <Badge variant="secondary">
                                    Qty {{ item.quantity }}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Notes -->
                <div class="space-y-2">
                    <Label for="note"> Notes (Optional) </Label>

                    <Textarea
                        v-model="checkInForm.note"
                        placeholder="Tambahkan catatan jika diperlukan..."
                        rows="4"
                    />
                </div>
            </div>

            <DialogFooter>
                <Button variant="outline" @click="closeDialog"> Cancel </Button>

                <Button :disabled="loading" @click="submitCheckIn">
                    {{ loading ? "Processing..." : "Check In" }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
