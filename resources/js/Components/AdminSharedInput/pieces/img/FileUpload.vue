<script setup>
defineOptions({
  inheritAttrs: false
})

import {triggerRef } from "vue";
import objectWithoutKey from "@/helpers/objectWithoutKey";
// import axios from "axios";
// import { onBeforeMount,shallowRef,triggerRef } from "vue";
import {default as base64Resolver,isNumeric} from '@/helpers/image/FileReader.js';
import TextInput from '@/Components/TextInput.vue';
const emit=defineEmits(['giveBase64ImageArray','closeLoading','openLoading','giveImageAttributes'])
// const props=defineProps()
const drop = (e) => {
    // const dt = e.dataTransfer;
    uploadingFile([...e?.dataTransfer?.files]);
}

const uploadingFile=(fileList)=>{
            reading.startUploadImage(fileList);
}
const props = defineProps({

    maxFileNumber:{
        type: Number,
        default:5,
    },
    columnName:String|null,
    config:{},
    maxSize:{
        type: Number,
        default:5,
    },
})
const multiple=props?.config?.multiple??false;
const column=props?.columnName;
const maxFileNumber=props?.maxFileNumber;
const maxSize=props?.maxSize;
const attachment=props?.config?.attachment;
// const loading=()=>{
//     emit('toggleLoading');
// }
const openLoading=()=>{
    emit('openLoading')
}
const closeLoading=()=>{
    emit('closeLoading')
}
const giveBase64=(baseX,multiple)=>{
    emit('giveBase64ImageArray',baseX,column,multiple);
}
const giveImageAttributes=(objArray)=>{
    let mappedObjArray=objArray.map(function(single){
        return objectWithoutKey(single,'src');
    });

    emit('giveImageAttributes',mappedObjArray,column);
}
const reading=new base64Resolver(
    {
        multiple:multiple,
        maxSize:maxSize,
        maxFileNumber:maxFileNumber,
        // loading:loading,
        closeLoading:closeLoading,
        openLoading:openLoading,
        giveBase64:giveBase64
    }
)
// const reading=new base64Resolver(multiple,maxSize,maxFileNumber,()=>{emit('toggleLoading');},(baseX,multiple)=>emit('giveBase64ImageArray',baseX,column,multiple));
reading.attachment=attachment;
const sortingPreviews=()=>{
    reading.xFileList.value.sort(function(a,b){
        if(isNumeric(a?.sort??0)&&isNumeric(b?.sort??0))
        {
            return parseInt(a?.sort??0)-parseInt(b?.sort??0);
       
        }
        else
        {
            return 0;
        }

    });
    triggerRef(reading.xFileList);
}

// 拿另一個變量指到reading.xFileList 監視他

// watch


// 檔案大小

// 檔案上傳事件

// 拖曳區
</script>

<template>
    <!-- 檔案區 -->
    <main
        class="transition-all bg-gray-100 border rounded-md dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-900 hover:bg-gray-200">
        <!-- 圖片上傳 -->
        <label for="uploadImg">
            <!-- 隱藏上傳input -->
            <input type="file" ref="fileInput" id="uploadImg" accept="image/*" multiple class="hidden"
                v-on:change="($event?.target?.files)&&uploadingFile([...$event?.target?.files])" />
            <!-- 自訂樣式&拖曳檔案 -->
            <div ref="dorpBox" v-on:dragenter.stop.prevent v-on:dragleave.stop.prevent
                v-on:dragover.stop.prevent v-on:drop.stop.prevent="drop"
                class="grid py-10 text-sm place-items-center">
                <div class="mb-4 text-black dark:text-white/60">
                    <p>將檔案拖曳至區域</p>
                    <p class=text-center>或</p>
                    <p>點擊選擇檔案上傳</p>
                </div>
                <p class="text-teal-500" v-if="multiple">{{ `最大上傳檔案 ${reading.maxFileNumber} 筆` }}</p>
                <p class="text-pink-500" v-if="reading.error.value!=null">{{ reading.error }}</p>
            </div>
        </label>
        <!-- 建立預覽圖 -->
        <div v-for="( a, index ) of  reading.xFileList.value" v-bind:key="`xFileList-${index}`" class="flex items-center p-6">
            <div class="relative">
                <div class="absolute top-0 left-0 ">
                    <button @click.prevent="reading.deleteFiles(index)"
                        class="inline-flex items-center justify-center w-6 h-6 text-xl text-center text-white rounded-full bg-rose-500">
                        ×
                    </button>
                </div>

                <div class="p-4">
                    <img :src="a?.src" class="object-cover rounded w-28 h-28" />
                </div>
            </div>
            <div class="flex-1 space-y-4" >
                
                <div v-for="(singleAttachment,smallIndex) in props.config.attachment" v-bind:key="`xFileList-${index}-${smallIndex}`" >
                    <p>
                        {{ singleAttachment?.title??'' }}
                    </p>
                    <TextInput class="w-full p-2" v-bind:value="reading.xFileList.value[index][smallIndex]" @change="reading.xFileList.value[index][smallIndex]=$event.target.value;giveImageAttributes(reading.xFileList.value);sortingPreviews()" :placeholder="singleAttachment?.title??''" />
                </div>
                <!-- 我不使用v-model了 因為我並沒有把它包成reactive 我直接用函數調用 -->
                <!-- <slot /> -->
            </div>
        </div>
    </main>
</template>

<style scoped></style>