<script setup>
import {computed} from 'vue';
import SelectionMenu from '@/Components/ui/SelectionMenu.vue';
import TextInput from '@/Components/TextInput.vue';
const emit=defineEmits(['update:binder']);
const partials=defineProps({
    binder:String|null,
    // selectionVal:String|Number|null,
    config:{},
});
const uploadSelectionResult=(val)=>{
    emit('update:binder',val);
}
const currentSelection=computed(()=>{
   let isSelected=partials.config?.selection?.filter((val)=>val.id==partials.binder);
   return (isSelected)?isSelected[0]?.name:null;
})
</script >
    

<template>
    <SelectionMenu v-if="partials.config?.selection!=undefined">
        <template #input>
            <TextInput disabled v-bind:value="currentSelection" class="w-full p-4"></TextInput>
        </template>
        <template #list>
            <!-- item?.id -->
            
            <li v-for="(item, index) in partials.config?.selection" :key="item?.id" @click="uploadSelectionResult(item?.id)"
                class="flex items-center p-4 space-x-2 rounded cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-500">
                <p>{{ item?.name }}</p>
            </li>
        </template>
    </SelectionMenu>
</template>
