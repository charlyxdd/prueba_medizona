<template>
    <Head title="Notas de venta"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="py-2 flex justify-start">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-grow">
                    Notas de venta
                </h2>
                <button type="button"
                        class="py-2 px-4 bg-blue-700 text-sm font-medium text-white" @click="addNote">
                    Crear nuevo
                </button>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                    Cliente
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Fecha
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Cantidad de items
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Total
                                </th>
                                <th>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                v-for="note in notes.data" :key="note.id">
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ note.customer.name }}
                                </th>
                                <td class="py-4 px-6">
                                    {{ note.date }}
                                </td>
                                <td class="py-4 px-6">
                                    {{ note.note_items.length }}
                                </td>
                                <td class="py-4 px-6">
                                    ${{ numberFormat(note.total) }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="inline-flex rounded-md shadow-sm" role="group">
                                        <button type="button"
                                                class="py-2 px-4 bg-purple-700 text-sm font-medium text-white rounded-l-lg"
                                                @click="editNote(note)">
                                            Editar
                                        </button>
                                        <button type="button"
                                                class="py-2 px-4 text-sm font-medium text-white bg-red-700 rounded-r-md"
                                                @click="confirmDeleteNote(note.id)">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="notes.data.length===0">
                                <td colspan="5">
                                    <h4 class="text-center text-xl py-2">No se han creado notas de venta</h4>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <pagination class="mt-6" :links="notes.links"/>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>


    <div v-if="showModal"
         class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
        <div class="relative w-auto my-6 mx-auto max-w-6xl">
            <!--content-->
            <div
                class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                <!--header-->
                <div class="flex items-start justify-between p-5 border-b border-solid border-slate-200 rounded-t">
                    <h4 class="text-xl font-semibold">
                        {{ form.id ? 'Editar' : 'Crear nueva' }} nota de venta
                    </h4>
                    <button
                        class="p-1 ml-auto bg-transparent border-0 text-black opacity-5 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                        @click="showModal=false">
                          <span
                              class="bg-transparent text-black opacity-5 h-6 w-6 text-2xl block outline-none focus:outline-none">
                            ×
                          </span>
                    </button>
                </div>
                <!--body-->
                <form @submit.prevent="submit">
                    <div class="relative p-6 flex-auto">

                        <div class="my-4">
                            <BreezeLabel for="customer" value="Cliente"/>

                            <select id="customer"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    v-model="form.customer_id">
                                <option selected></option>
                                <option v-for="customer in customers" :value="customer.id" :key="customer.id">
                                    {{ customer.name }}
                                </option>
                            </select>

                        </div>
                        <div class="my-4">
                            <BreezeLabel for="date" value="Fecha"/>
                            <BreezeInput id="date" type="date" class="mt-1 block w-full" v-model="form.date" required
                                         autofocus autocomplete="username"/>
                        </div>

                        <hr>

                        <div class="flex items-end justify-center">
                            <div class="my-4 px-2">
                                <BreezeLabel for="product_id" value="Producto"/>
                                <select id="product_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        v-model="selectedItem">
                                    <option selected></option>
                                    <option v-for="item in items" :value="item" :key="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="my-4 px-2">
                                <BreezeLabel for="quantity" value="Cantidad"/>
                                <BreezeInput id="quantity" type="number" class="mt-1 block w-full" v-model="quantity"/>
                            </div>
                            <div class="my-4 px-2 flex items-end">
                                <button type="button"
                                        class="py-2 px-4 bg-purple-700 text-sm font-medium text-white h-min"
                                        @click="addNoteItem">
                                    Agregar
                                </button>
                            </div>
                        </div>

                        <div
                            class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                            role="alert" v-if="error && error!==''">
                            <span class="font-medium">¡Error!</span> {{ error }}.
                        </div>


                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 my-4">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                    Producto
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Cantidad
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Precio unitario
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    Total
                                </th>
                                <th>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                v-for="(noteItem, index) in noteItems" :key="index">
                                <th scope="row"
                                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ noteItem.item.name }}
                                </th>
                                <td class="py-4 px-6">
                                    {{ noteItem.quantity }}
                                </td>
                                <td class="py-4 px-6">
                                    $ {{ numberFormat(noteItem.total) }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="inline-flex rounded-md shadow-sm" role="group">
                                        <button type="button"
                                                class="py-2 px-4 text-sm font-medium text-white bg-red-700"
                                                @click="deleteNoteItem(index)">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="noteItems.length===0">
                                <td colspan="5">
                                    <h4 class="text-center text-xl py-2">No se han agregado items a la nota de
                                        venta</h4>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="flex flex-col items-end my-4">
                            <BreezeLabel for="total" value="Total"/>
                            <span>$ {{ numberFormat(form.total) }}</span>
                        </div>

                    </div>
                    <!--footer-->
                    <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 rounded-b">
                        <button
                            class="py-2 px-4 bg-red-700 text-sm font-medium text-white"
                            type="button" @click="showModal = false">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="py-2 px-4 bg-blue-700 text-sm font-medium text-white">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div v-if="showModal || showPopup" class="opacity-25 fixed inset-0 z-40 bg-black" @click="showModal = false"></div>


    <div v-if="showPopup"
         class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
        <div class="relative w-auto my-6 mx-auto max-w-6xl">
            <!--content-->
            <div
                class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                <!--header-->
                <div class="flex items-start justify-between p-5 border-b border-solid border-slate-200 rounded-t">
                    <button type="button"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                            @click="showPopup=false">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!--body-->
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        ¿Desea eliminar la nota de venta?
                    </h3>
                    <button data-modal-toggle="popup-modal" type="button"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
                            @click="deleteNote">
                        Aceptar
                    </button>
                    <button data-modal-toggle="popup-modal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                            @click="showPopup=false">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import Pagination from '@/Components/Pagination'
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import {ref} from 'vue'
import {Inertia} from '@inertiajs/inertia'

defineProps({
    notes: Object,
    items: Object,
    customers: Object,
});

/*
Variables reactivas
 */
const showModal = ref(false)
const showPopup = ref(false)
const noteItems = ref([]);
const selectedItem = ref(null);
const quantity = ref(1);
const error = ref('');
const selectedNoteId = ref(null);
const form = useForm({
    id: null,
    customer_id: null,
    date: '',
    note_items: [],
    total: 0
});
/*
Funcionalidad del form
 */
const addNoteItem = () => {
    error.value = '';
    if (!selectedItem.value) {
        error.value = 'Seleccione un producto';
        return false;
    }
    if (quantity <= 0) {
        error.value = 'La cantidad debe de ser mayor a 0';
        return false;
    }

    const total = selectedItem.value.price * quantity.value

    noteItems.value.push({
        item: selectedItem.value,
        quantity: quantity.value,
        total,
    });
    form.total += total;
    quantity.value = 1;
    selectedItem.value = null;

}
const deleteNoteItem = (index) => {
    form.total -= noteItems.value[index].total;
    noteItems.value.splice(index, 1);
}
const submit = () => {
    error.value = '';
    if (!form.customer_id) {
        error.value = 'Debe de seleccionar un cliente';
        return false;
    }

    if (form.date === '') {
        error.value = 'Debe de seleccionar una fecha';
        return false;
    }

    if (noteItems.value.length === 0) {
        error.value = 'Debe de seleccionar por lo menos un item';
        return false;
    }

    form.note_items = noteItems.value.map((item) => {
        return {
            item_id: item.item.id,
            quantity: item.quantity,
            total: item.total,
        }
    });

    if (form.id) {
        form.put(route('notes.update', form.id), {
            onFinish: () => {
                clearForm();
                showModal.value = false;
            }
        });
    } else {
        form.post(route('notes.store'), {
            onFinish: () => {
                clearForm();
                showModal.value = false;
            }
        });
    }


};

/*
Funcionalidad de la tabla
 */
const addNote = () => {
    clearForm();
    showModal.value = true;
}

const editNote = (note) => {
    const selectedNote = JSON.parse(JSON.stringify(note)) // Para cancelar referencia a objeto
    form.id = selectedNote.id;
    form.customer_id = selectedNote.customer.id;
    form.date = selectedNote.date;
    form.total = selectedNote.total;

    noteItems.value = selectedNote.note_items.map((item) => {
        return {
            item: item.item,
            quantity: item.quantity,
            total: item.total
        }
    });

    showModal.value = true;
}

const confirmDeleteNote = (noteId) => {
    // this.$inertia.delete(route("notes.destroy", noteId));
    selectedNoteId.value = noteId;
    showPopup.value = true;
}

const deleteNote = () => {
    Inertia.delete(route("notes.destroy", selectedNoteId.value), {
        onFinish: () => {
            showPopup.value = false;
        }
    });
}

/*
Funciones extras
 */

const numberFormat = (number) => {
    const n = parseFloat(number)
    const val = (n).toFixed(2)// .replace('.', ',');
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
};
const clearForm = () => {
    form.reset('id', 'customer_id', 'date', 'note_items', 'total');
    noteItems.value = [];
}

</script>

<style scoped>

</style>
