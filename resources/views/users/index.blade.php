<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('People') }}
        </h2>
    </x-slot>

    <div class="py-12" id="people-app">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-200">Choose file to upload</label>
                        <div class="mt-2">
                            <input @change="handleFileUpload($event)" type="file" class="text-white">
                            <span v-if="submitting" class="text-white">
                                    <i  class="fa fa-spinner fa-spin mr-1"></i> Uploading file....
                                </span>
                        </div>
                    </div>

                    <div v-if="people.length" class="mt-4">
                        <div class="flex flex-row justify-between">
                            <h2 class="text-white">Data Preview <span v-if="peopleLength" class="text-sm text-gray-400">@{{peopleLength}} rows</span></h2>
                            <a :href="'/people/export?path='+path" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                                <i v-if="submitting" class="fa fa-spinner fa-spin mr-1"></i>Export to Excel
                            </a>
                        </div>

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    #ID
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Phone
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Address
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="(value, index) in people" :key="index">
                                <td class="px-6 py-4">
                                    @{{value.name}}
                                </td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @{{value.name}}
                                </th>
                                <td class="px-6 py-4">
                                    @{{value.email}}
                                </td>
                                <td class="px-6 py-4">
                                    @{{value.phone}}
                                </td>
                                <td class="px-6 py-4">
                                    @{{value.address}}
                                </td>

                            </tr>
                            </tbody>
                        </table>
                        <div class="text-white flex justify-center mt-4"><a href="#" @click.prevent="loadMore">Load More <i class="fa fa-arrow-down"></i></a></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @section('script')
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/datatables.net-vue3@3.0.1/dist/datatables.net-vue3.umd.min.js"></script>
        <script>
            const { createApp, ref, onMounted } = Vue

            createApp({
                setup() {
                    const file = ref(null);
                    const page = ref(10);
                    const path = ref(null);
                    const peopleLength = ref(null);
                    const people = ref([]);
                    const allPeople = ref([]);
                    const submitting = ref(false);
                    onMounted(() => {
                    });

                    const handleFileUpload = function handleFileUpload($event) {
                        const target = $event.target;
                        if (target && target.files) {
                            file.value = target.files[0];
                        }

                        this.uploadJson();
                    };

                    const loadMore = function loadMore() {
                        page.value = page.value + 10
                        people.value =  allPeople.value.slice(0, page.value);
                    };

                    const uploadJson = async function uploadJson() {
                        try{
                            submitting.value = true;
                            const formData = new FormData();
                            formData.append('file', file.value);

                            const requestOptions = {
                                method: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: formData
                            };
                            const response = await fetch("/people/upload", requestOptions);
                            const responseData = await response.json();
                            allPeople.value = responseData.data;
                            peopleLength.value = responseData.data.length;
                            path.value =  responseData.path;


                            people.value =  responseData.data.slice(0, page.value);
                            submitting.value = false;
                        }catch (e) {
                            console.log(e)
                        }
                    };

                    return {
                        path, people, submitting, file, uploadJson, handleFileUpload, peopleLength, loadMore
                    }
                }
            }).mount('#people-app')
        </script>
    @endsection
</x-app-layout>


