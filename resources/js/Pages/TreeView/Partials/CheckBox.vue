<script setup>
    import { computed, onMounted, ref, toValue } from 'vue'
    import CheckBox from '@/Pages/TreeView/Partials/Checkbox.vue'
    import ExpandMore from '@/Components/Icons/ExpandMore.vue'
    import ExpandLess from '@/Components/Icons/ExpandLess.vue'

    const props = defineProps({
        checkbox: Object,
        form: Array,
    })

    const emit = defineEmits(['verifyDescendants'])

    const isExpanded = ref(toValue(props.form.filter(item => props.checkbox.descendants.map(desc => desc.code).includes(item.code))).length > 0)

    const checkedDescendants = computed(() => {
        return props.form.filter(item => props.checkbox.descendants.map(desc => desc.code).includes(item.code))
    })

    const hasDescendants = computed(() => {
        return props.checkbox.descendants.length > 0
    })

    const isChecked = computed(() => {
        /* check for has descendants */
        if(toValue(hasDescendants)) {          
            /* check */
            return toValue(checkedDescendants).length > 0 && toValue(checkedDescendants).length == props.checkbox.descendants.length
        }

        /* no descendants */
        return props.form.map(item => item.code).includes(props.checkbox.code)
    })
    
    const isIndeterminate = computed(() => {
        /* check */
        return toValue(hasDescendants) && 
                toValue(checkedDescendants).length > 0 && 
                 toValue(checkedDescendants).length < props.checkbox.descendants.length
    })
    
    const check = (evt) => {
        /* checked */
        if(evt.target.checked) {
            /* check for descendants */
            if(toValue(hasDescendants)) {
                /* expand */
                isExpanded.value = true

                /* add all descendants */
                addDescendants(props.checkbox.descendants)
            } 

            /* if not already added */
            if (!props.form.map(checkedItem => checkedItem.code).includes(props.checkbox.code)) {
                /* add checked */
                props.form.push(props.checkbox)
            }

        } else {
            /* check for descendants */
            if (toValue(hasDescendants)) {
                /* remove all descendants */
                removeDescendants(props.checkbox.descendants)
            }

            /* delete single parent item */
            deleteItemFromForm(props.checkbox.code)

            /* check parent has any descendants left */
            emit('verifyDescendants')
        }
    }

    const deleteItemFromForm = (code) => {
        /* identify the index */
        const idx = props.form.findIndex((item) => {
            return item.code == code
        })
        
        /* exists */
        if (idx >= 0) {
            /* remove the checked */
            props.form.splice(idx, 1)
        }
    }

    const addDescendants = (descendants) => {
        /* add all descendants */
        descendants.forEach((item) => {
            /* check for descandants */
            if (item.descendants.length > 0) addDescendants(item.descendants)

            /* check not already added */
            if (!props.form.map(checkedItem => checkedItem.code).includes(item.code)) props.form.push(item)
        })
    }

    const removeDescendants = (descendants) => {
        /* add all descendants */
        descendants.forEach((item) => {
            /* check for descandants */
            if (item.descendants.length > 0) removeDescendants(item.descendants)

            /* delete single item */
            deleteItemFromForm(item.code)
        })
    }

    const verifyDescendants = () => {
        console.log('verifying all descendants have been removed - housekeeping')

        /* check for checked descendants exist - upstream housekeeping */
        if (toValue(checkedDescendants).length == 0) deleteItemFromForm(props.checkbox.code)
    }

</script>

<template>

    <div class="flex flex-col">

        <div class="flex items-center">

            <input v-on:click="check($event)" type="checkbox" :checked="isChecked" :indeterminate="isIndeterminate" class="rounded-md" />
            
            <span class="mx-2">{{ checkbox.name }}</span>

            <template v-if="checkbox.descendants.length > 0">

                <ExpandMore v-if="!isExpanded" v-on:click="isExpanded = true" class="w-7 h-7 text-gray-400 fill-current" />
                <ExpandLess v-else v-on:click="isExpanded = false" class="w-7 h-7 text-gray-400 fill-current" />

            </template>

        </div>

        <div v-if="hasDescendants && isExpanded" class="ml-4 my-1 space-y-1">

            <template
                v-for="descendant in checkbox.descendants"
                :key="checkbox.id">
                
                <CheckBox
                    v-on:verifyDescendants="verifyDescendants()"
                    :form="form"
                    :checkbox="descendant" />
                
            </template>

        </div>

    </div>

</template>