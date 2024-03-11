<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from "primevue/useconfirm";

const toast = useToast();
const confirm = useConfirm();

const props = defineProps({
    tasks: Object
});

const taskForm = useForm({
    txt : ''
});
const handleNewTask = () => {
    taskForm.post(route("task.store"),{
        onSuccess: () => {
            taskForm.reset();
            toast.add({ severity: 'success', summary: 'Success', detail: 'Task created', life: 4000 });
        }
    });
};
const handleMarkCompletedTask = (id) => {
    taskForm.id = id;
    taskForm.put(route("task.update",{'task':id}), {
        onSuccess: () => {
            taskForm.reset();
            toast.add({ severity: 'info', summary: 'Update', detail: 'Task marked as completed', life: 4000 });
        }
    });
}
const confirmedDeleteTask = (event, id) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Are you sure you want to proceed?',
        acceptClass: '!bg-red-500 dark:!bg-red-40 !border-red-500 dark:!border-red-400 !ring-red-500 dark:!ring-red-400 hover:!bg-red-600 dark:hover:!bg-red-300 hover:!border-red-600 dark:hover:!border-red-300 focus:!ring-red-400/50 dark:!focus:ring-red-300/50',
        accept: () => {
            taskForm.id = id;
            taskForm.delete(route("task.destroy",{'task':id}), {
                onSuccess: () => {
                    taskForm.reset();
                    toast.add({ severity: 'error', summary: 'Delete', detail: 'Task deleted', life: 4000 });
                }
            });
        }
    });
};



</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
        </template>

        <div class="w-full md:w-3/4 md:mx-auto flex flex-col space-y-4 p-3">
            <div class="flex flex-col">
                <InputText type="text" v-model="taskForm.txt" />
                <div v-if="taskForm.errors.txt" class="text-red-700">{{ taskForm.errors.txt }}</div>
            </div>
            <div class="flex justify-end">
                <Button label="Add" @click="handleNewTask"></Button>
            </div>


        </div>

        <div class="w-full md:w-3/4 md:mx-auto">
            <div class="flex bg-white p-4">
                <table class="border-collapse table-auto w-full text-sm">
                    <thead>
                    <tr>
                        <th class="w-16 border-b font-bold p-4 pl-8 pt-0 pb-3 text-blue-900 text-left">#</th>
                        <th class="border-b font-medium p-4 pt-0 pb-3 text-blue-900 text-left">Task</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    <tr v-for="task in tasks">
                        <td class="border-b border-slate-100 p-4 pl-8 text-slate-500 font-bold">{{ task.id }}</td>
                        <td class="border-b border-slate-100 p-4 text-slate-500 flex flex-col md:flex-row justify-between">
                            <div :class="{'line-through' : task.status === 2 }">{{ task.txt }}</div>
                            <div class="flex space-x-2">
                                <Button v-if="task.status === 1" label="Completed" icon="pi pi-check" aria-label="Mark as completed" @click="handleMarkCompletedTask(task.id)" />
                                <Button @click="confirmedDeleteTask($event, task.id)" icon="pi pi-times" label="Delete" severity="danger"></Button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
