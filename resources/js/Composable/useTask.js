import { useForm } from '@inertiajs/vue3';
import {useToast} from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
export function useTask() {

    const toast = useToast();
    const confirm = useConfirm();
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

    const handleTaskUpdate = async (task) => {
        await axios.post(route("task.update", {'task': task.id}),task).then(()=>{
            toast.add({ severity: 'success', summary: 'Success', detail: 'Task updated', life: 4000 })
            task.editing = false;
            task.errors = null;
        }).catch((error) => {
            task.errors = error.response.data.errors;
        });
    };

    const handleSetStatusComplete = (id) => {
        taskForm.id = id;
        taskForm.get(route("task.setStatusComplete",{'task':id}), {
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

    return { taskForm, handleNewTask, handleTaskUpdate, handleSetStatusComplete, confirmedDeleteTask }
}
