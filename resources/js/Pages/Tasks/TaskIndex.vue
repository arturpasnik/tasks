<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import {useTask} from "@/Composable/useTask.js";

const props = defineProps({
    tasks: Object
});

const { taskForm, handleNewTask, handleTaskUpdate, handleSetStatusComplete, confirmedDeleteTask } = useTask();

</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
        </template>

        <div class="w-full md:w-3/4 md:mx-auto flex flex-col space-y-2 p-3">
            <div class="flex flex-col">
                <InputText placeholder="Here you can add a task" size="small" type="text" v-model="taskForm.txt" :invalid="taskForm.errors.txt" />
                <p v-if="taskForm.errors.txt" class="px-2 text-red-700">{{ taskForm.errors.txt }}</p>
            </div>
            <div class="flex justify-end">
                <Button size="small" label="Add new task" @click="handleNewTask"></Button>
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
                            <div v-if="task.editing" class="flex flex-col w-full mr-4">
                                <InputText size="small" type="text" v-model="task.txt" :invalid="task.errors && ('txt' in task.errors)" />
                                <p v-if="task.errors && ('txt' in task.errors)" class="px-2 text-red-700">{{ task.errors.txt[0] }}</p>
                            </div>
                            <div v-else :class="{'line-through' : task.status === 2 }">{{ task.txt }}</div>
                            <div class="flex space-x-2">
                                <Button size="small" class="h-8" v-if="task.status === 1 && !task.editing" label="Edit" aria-label="Edit" @click="task.editing = true" />
                                <Button size="small" class="h-8" v-if="task.status === 1 && task.editing" label="Save" aria-label="Save" @click="handleTaskUpdate(task)" />
                                <Button size="small" class="h-8" v-if="task.status === 1" label="Completed" icon="pi pi-check" aria-label="Mark as completed" @click="handleSetStatusComplete(task.id)" />
                                <Button size="small" class="h-8" @click="confirmedDeleteTask($event, task.id)" icon="pi pi-times" label="Delete" severity="danger"></Button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
