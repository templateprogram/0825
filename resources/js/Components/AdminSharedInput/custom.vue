<script setup>
import { ref, watch} from 'vue';


import AdminTinymce from './pieces/AdminTinymce.vue';
import AdminSelectInput from './pieces/AdminSelectInput.vue';
import AdminTextInput from './pieces/AdminTextInput.vue';
import AdminTextArea from './pieces/AdminTextArea.vue';
import PictureUploads from './pieces/img/PictureUploads.vue';
const _typeHash={
    'text':AdminTextInput,
    'img':PictureUploads,
    'textArea':AdminTextArea,
    'dropDown':AdminSelectInput,
    'edt':AdminTinymce,
}
defineExpose({ inheritAttrs: false});
const emit=defineEmits(['update:changing','toggleLoading','giveBase64ImageArray','closeLoading','openLoading','giveImageAttributes','giveImgDataAttributes']);


const partials=defineProps({
    changing:String|null,
    config:{},
    columnName:String|null,
    error:String|null,
    imageAttributes:String|null,
});
const outterInputValue=ref(partials.changing);
watch(outterInputValue,(val)=>{
    emit('update:changing',val);
})
const toggleLoading=()=>{
    emit('toggleLoading');
}
const openLoading=()=>{
    emit('openLoading');
}
const closeLoading=()=>{
    emit('closeLoading');
}
const giveBase64ImageArray=(...args)=>{
    emit('giveBase64ImageArray',...args);
}
const giveImageAttributes=(...args)=>{
    emit('giveImageAttributes',...args);
}
const giveImgDataAttributes=(...args)=>{
    emit('giveImgDataAttributes',...args);
}
</script>

<template>
        <component
        v-bind="$attrs"
        :config="config"
        :error="error"
        :columnName="columnName"
        v-model:binder="outterInputValue" 
        :imgAttributes="partials.imgAttributes"
        @toggleLoading="toggleLoading"
        @closeLoading="closeLoading"
        @openLoading="openLoading"
        @giveBase64ImageArray="giveBase64ImageArray"
        @giveImageAttributes="giveImageAttributes"
        @giveImgDataAttributes="giveImgDataAttributes"
        :is="_typeHash[config?.type]??AdminTextInput"
        ></component>
</template>