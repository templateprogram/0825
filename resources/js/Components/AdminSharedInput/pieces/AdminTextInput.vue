<script setup>
import TextInput from '@/Components/TextInput.vue';
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
<div>
    <InputLabel  :value="config?.name" :for="columnName"/>
        <TextInput   v-bind="$attrs" :id="columnName" :name="columnName"  v-model="inputValue" type="text" class="block w-full mt-1" :autocomplete="columnName" />
        <InputError :message="error" />
    </div>
</template>
