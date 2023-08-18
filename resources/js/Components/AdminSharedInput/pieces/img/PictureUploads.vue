<script setup>
import FileUpload from './FileUpload.vue';
import FileList from '@/Components/FileList.vue';
import { useForm } from '@inertiajs/vue3';
import {isNumeric} from '@/helpers/image/FileReader.js';
import { ref,onBeforeMount,onMounted} from 'vue';
import TextInput from '@/Components/TextInput.vue';
import objectWithoutKey from '@/helpers/objectWithoutKey';
/*
    重要:
        這邊得要先解析 base64 在進行預覽圖的產生，因為要讓客戶知道他的圖上傳完成。
        否則如果先進行預覽圖產生，再進行base64 客戶會以為他已經上傳完了，觸發了過早的ajax
*/
// 全域變數
// 因為產生預覽圖的時候不能用base64 字串產生
const emit=defineEmits(['toggleLoading','giveBase64ImageArray','closeLoading','openLoading','giveImageAttributes','giveImgDataAttributes']);
const giveBase64ImageArray=(...args)=>{
    emit('giveBase64ImageArray',...args); // 傳給form 目前的值
}
defineOptions({
  inheritAttrs: false
})
const partials=defineProps({
    binder:String|null|Array,
    config:{},
    columnName:String|null,
    maxFileNumber:{
        type: Number,
        default:5,
    },
    maxSize:{
        type: Number,
        default:5,
    },
    imgAttributes:String|null,
});
const config=partials.config;
const attachment=partials.config?.attachment??{};
// let requestPath=(multipleUpload==false)?"/destroyImg"+"/"+column:"/destroyMultipleImgs";
//     axios.post(requestPath,{
//         'imgPath':pathName,
//     })
//     .then(res=>res.data)
//     .then(res=>
//     {
//         res&&x.value.splice(index,1);
//         triggerRef(x);
//     })
//     .finally(()=>{
//         emit('toggleLoading');
//     });
const deleteImg=(index,path)=>{

    imgData.value.splice(index,1);
    /*
        這個刪除是針對已經上傳的圖片進行刪除。
    */
    let multiple=config?.multiple;
    let requestPath=(multiple==false)?'destroyImg'+'/'+(partials?.columnName??''):"destroyMultipleImgs";
    toggleLoading();
    useForm({'imgPath':path}).post(requestPath, { preserveState: true,onFinish: ()=>{
        //ANCHOR if you want to debug   
        toggleLoading();
    } })
}
const imgDataAttributes={};

// const imgDataAttributes={attachment};
const imgData=ref((Array.isArray(partials?.binder))?partials?.binder.map((single)=>{
    single.attributes=JSON.parse(single.attributes);
    imgDataAttributes[single.id]=single.attributes;
    return {'dataSrc':single?.url??'','src':`${window.location.origin+'/storage/'+(single?.url??'')}`,...objectWithoutKey(single,'src')}
}):[{'dataSrc':partials?.binder,'src':(partials?.binder)?`${window.location.origin+'/storage/'+(partials?.binder??'')}`:null,'attributes':(JSON.parse(partials?.imgAttributes??'{}'))[partials?.columnName]??null}]);
const toggleLoading=()=>{
    emit('toggleLoading');
}
const closeLoading=()=>{
    emit('closeLoading')
}
const openLoading=()=>{
    emit('openLoading')
}
/*
change yet uploaded imageData
更改尚未上傳的圖片資料(預覽圖)
*/
const givePreviewImageAttributes=(...args)=>{
    emit('giveImageAttributes',...args);
}
/*
change uploaded imageData
更改已上傳的圖片資料(真實圖片)
*/
const giveImgDataAttributes=(id=null,viAttribute,value)=>{
    if(id===null)
    {
        imgDataAttributes[partials.columnName][viAttribute]=value;
    }
    else
    {
        imgDataAttributes[id][viAttribute]=value;
    }
    sortImgData();
    // ANCHOR NEXT FIX
    emit('giveImgDataAttributes',partials.columnName,imgDataAttributes,partials.config?.multiple??false);
}
const sortImgData=()=>{
    imgData.value.sort((a,b)=>{
        let aSort=a?.attributes?.sort??0;
        let bSort=b?.attributes?.sort??0;
        if(isNumeric(aSort)&&isNumeric(bSort))
        {
            return parseInt(aSort)-parseInt(bSort);
       
        }
        else    
        {
            return 0;
        }
    })
}
const attachmentAttribute={};
for(let key in attachment)
{
   attachmentAttribute[key]=null;
}
onBeforeMount(() => {
    // console.log();
    if(partials?.config?.multiple===false)
    {

        imgDataAttributes[partials.columnName]=imgData.value[0].attributes;
    }
    emit('giveImgDataAttributes',partials.columnName,imgDataAttributes,partials?.config?.multiple??false);
    emit('giveBase64ImageArray',{},partials.columnName,partials?.config?.multiple??false);
});
/*
    void function 
*/



</script>
<template>
    <div >

    <FileUpload 
    @toggleLoading="toggleLoading"
    @openLoading="openLoading"
    @closeLoading="closeLoading"
    :maxFileNumber="maxFileNumber"
    :maxSize="maxSize"
    :config="config"
    :columnName="columnName"
    @giveBase64ImageArray="giveBase64ImageArray"
    @giveImageAttributes="givePreviewImageAttributes"
    >

        <!-- <div v-for="">


        </div>
        <div v-for="singleAttachment in partials.config.attachment">
            <TextInput class="w-full p-2" v-model="alt" placeholder="圖片敘述" />
        </div>
        <div>
            <TextInput class="w-full p-2" v-model="alt" placeholder="圖片敘述" />
        </div>
        <div>
            <TextInput class="w-full p-2" v-model="sort" placeholder="排序" />
        </div> -->
    </FileUpload>
    </div>
    <!-- 圖片列表 -->
    <!-- var sorted_students = students.sort(function(a, b) {
	return a.age - b.age;
}); -->
    <div>
    <p class="pb-2">第一張為主圖</p>
    <FileList ref="fileList" @key="`editImage-${a?.id??index}`" v-for="(a, index) in imgData" :mainImg="0" v-if="a?.dataSrc" @deleteImg="deleteImg(index,a.dataSrc)"
        @toggleLoading="toggleLoading" :src="a.src">
        <div v-for="(b,viAttribute) in a?.attributes??attachmentAttribute" @key="`property-${viAttribute}-${a?.id??index}`">
            <p v-text="attachment[viAttribute]?.title??''">
                
            </p>
            <TextInput class="w-full p-2" v-bind:value="b" @change="giveImgDataAttributes(a?.id,viAttribute,$event.target.value);"  :placeholder="attachment[viAttribute]?.title??''" />
        </div>
    </FileList>
    </div>
        
</template>
