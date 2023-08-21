<script setup >
import Editor from '@tinymce/tinymce-vue'
import { reactive, ref,watch } from 'vue';
import axios from 'axios';
import '@/tinymce/tinymce.min.js';
// import '../tinymce/tinymce.min.js';
defineOptions({
  inheritAttrs: false
})
const emit=defineEmits('update:binder');
const props = defineProps({
    fileUploadUrl:{
        type:String,
        default:'/tinymce',  
    },
    binder:String|null,
    
})

const useSkin = ref(localStorage.theme === 'light' ? 'oxide' : 'oxide-dark');
const useCss = ref(localStorage.theme === 'light' ? 'default' : 'dark');


// 建立Promise格式，使用resolve,reject來拋成功或問題
const ImagePost = (blobInfo, progress) => new Promise((resolve, reject) => {
    // 建立表單數據
    const formData = new FormData();
    // (建立檔案名稱,blob格式,檔案名稱)
    formData.append('file', blobInfo.blob(), blobInfo.filename());

    // 設定限制5MB
    const isLt10M = blobInfo.blob().size / 1024 / 1024 < 5;

    // axios發送信任訊息(有使用token需開啟)
    axios.defaults.withCredentials = false;
    if (!isLt10M) {
        reject({ message: '上傳失敗，檔案格式失敗或大小不可超過5MB', remove: true })
    } else {
        axios.post(props.fileUploadUrl, formData)
            .then(res => {
                res.request.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100)
                }
                resolve(res.data.image)
            })
            .catch(err => {
                reject({ message: err.response.data.message, remove: true })
            })
    }
});
const inputValue=ref(props.binder);
watch(inputValue, (val) => {
    emit('update:binder',val);
});
const init = reactive({
    language: 'zh-Hant', //使用中文(要跟檔案同名)
    height: 500, //編輯器高度
    menubar: false, //文字選單
    quickbars_insert_toolbar: true, //快速插入工具列
    branding: false, //要不要顯示tinyMCE品牌
    resize: false, //編輯器可以自己選取大小
    promotion: false, //開啟更新通知
    toolbar_sticky: false, //固定工具列在上面不受捲軸影響
    toolbar_mode: 'sliding', //可能的值： 'floating'、'sliding'、'scrolling'或'wrap' 下拉樣式
    skin: useSkin.value, //黑暗版外表
    content_css: useCss.value, //黑暗版身體
    font_size_formats: '12px 14px 16px 18px 24px 36px 48px 56px 72px', //字型大小
    font_family_formats: '思源黑體=Noto Sans TC;思源宋體=Noto Serif TC;Roboto=roboto;Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; AkrutiKndPadmini=Akpdmi-n', //預設字型
    // 插件使用開源的
    plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons code',
    // 自訂工具列順序
    toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent | table numlist bullist | forecolor backcolor removeformat | harmap emoticons insertfile image media link anchor codesample',
    // 自動上傳圖片，可以解決base64問題
    automatic_uploads: true,
    // 處理圖片
    images_upload_handler: ImagePost,
    // init_instance_callback: function (ed) {
    //     alert(111);
    // },
});


</script>

<template>
    <div v-bind="$attrs">
        <!-- <Editor v-model.defer="inputValue"  api-key="no-api-key" :init="init" /> -->
        <Editor  v-model.defer="inputValue"  api-key="no-api-key" :init="init" />
    </div>

</template>

<style scoped></style>