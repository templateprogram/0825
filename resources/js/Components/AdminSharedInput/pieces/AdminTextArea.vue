<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { ref, watch} from 'vue';
defineExpose({ inheritAttrs: false});
const emit=defineEmits(['update:binder']);
defineOptions({
  inheritAttrs: false
})

const partials=defineProps({
    binder:String|null,
    config:{},
    columnName:String|null,
    error:String|null,
});

const inputValue=ref(partials.binder);
watch(inputValue,(val)=>{
    emit('update:binder',val);
})
</script>

<template>
    <InputLabel  :value="config?.name" :for="columnName"/>
    <textarea   v-bind="$attrs" class="w-full my-2 rounded dark:bg-gray-800" :id="columnName" :name="columnName" v-model="inputValue" :autocomplete="columnName">
    </textarea>
    <InputError :message="error" />

</template>
