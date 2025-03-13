<script setup>
    import { computed, ref, toValue } from 'vue'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
    import CheckBox from '@/Pages/TreeView/Partials/Checkbox.vue'

    const props = defineProps({
        json: Object
    })

    const form = ref([])

    const list = computed(() => {
        return toValue(form).map(item => item.name)
    })

</script>

<template>

    <div class="py-12">

        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 grid grid-cols-5 gap-2">

            <div class="col-span-5">
                <h3 class="text-2xl font-semibold">Treeview Challenge</h3>
            </div>

            <div class="col-span-3 p-4 bg-gray-200 shadow-sm sm:rounded-lg">

                <span class="font-semibold">{{ json.name }}</span>

                <template
                    v-for="checkbox in json.values" 
                    :key="checkbox.id">

                    <CheckBox 
                        :form="form"
                        :checkbox="checkbox" />

                </template>
                
            </div>

            <div class="col-span-2 p-4 bg-gray-200 shadow-sm sm:rounded-lg">

                <span class="font-semibold">Answers</span>

                <div class="flex flex-col space-y-1">

                    <template
                        v-for="item in list" 
                        :key="`form_list_item_${item}`">

                        <span class="px-3 py-1.5 bg-gray-300 rounded-md">{{ item }}</span>

                    </template>

                </div>

            </div>

        </div>

    </div>

</template>