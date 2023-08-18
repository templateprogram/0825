
import { shallowRef,triggerRef,ref,watch } from "vue";
export default function imageResolver({multiple:multiple,maxSize:maxSize,maxFileNumber:maxFileNumber,openLoading:openLoading,closeLoading:closeLoading,giveBase64:giveBase64})
{
    let obj=new base64Resolver(...arguments);
    return new Proxy(obj,proHandler);
    /*
     此處所有屬性 不論baseX 或者XFileList 皆屬於預覽用屬性
     因此與已上傳圖片 完全不同
    */
}
export function isNumeric(value){
    return /^-?\d+$/.test(value);    
}



const proHandler={
            
    get(obj, prop) {
        if(obj.hasOwnProperty(prop))
        {
            return obj[prop];
        }
        else if(obj[prop]&&typeof obj[prop]==='function')
        {
            // private function
            /*
                當我們使用 代理呼叫函數的時候，實際上是代理正在呼叫函數
                所以this 的指向是錯誤的並不是obj本身
                先是將 function 的this 重新指向為obj
                再返還給外部 這樣就不會導致 在使用 代理的set 函數時 處理到不應該暴露出來的變量
            */
            return obj[prop].bind(obj);
        }
    },
    set(obj, prop, value) { // set should return bool type
        //只能讓外部傳送fileList 這個變量
        // 其他一律為私有變量
        if(['fileList','attachment'].includes(prop))
        {
            obj[prop]=value;
            return true;
        }
        return false;
    }
}

 class base64Resolver{
    
    constructor({multiple:multiple,maxSize:maxSize,maxFileNumber:maxFileNumber,openLoading:openLoading,closeLoading:closeLoading,giveBase64:giveBase64}){
        this.attachment=[];
        this.multiple=multiple;
        this.closeLoading=closeLoading;
        this.openLoading=openLoading;
        this.giveBase64=giveBase64;
        this.maxSizeVirtual=maxSize;
        this.maxSize=this.maxSizeVirtual*this.mbRate;
        this.mbRate=1048576;
        this.fileList=null;
        this.i=0;
        this.maxFileNumber=maxFileNumber;
        this.error=ref(null);
        this.currentImage=null;
        this.customCreatePreview=null;
        this.customResolveBase64=null;
        this.reader=new FileReader();
        this.xFileList=shallowRef([]);
        this.baseX=[];
        this.reader.onload = (event) => {
            this.openLoading&&this.openLoading();
            let file = (event.target)?.result ?? null;
             new Promise((resolve, reject) => {
                // console.log(event.targe);\
                if (!file) {
                    this.currentImage=null;
                    return reject();
                }
                else {
                    resolve(file)
                }
        
            })
            .then((res)=>this.resolveBase64(res)) 
            .then((res)=>this.createPreview(res))
            .then(()=>this.uploadImage())
            /*
            these two function should all be void function 
            */
        }
   
    
    } //end constructor
    /*
        private Function
    */
    resolveBase64(res)
    {
        //如果res 不為字串的話
    // 此檔案並不是使用ts 寫成的 但是我們必須在這裡進行嚴格的類型聲明 否則檔案傳不出去
        if(typeof res!='string') // the resolve method will make it a string
        {
            this.error.value="檔案不支持";
            return null;
        }
        else
        {
            if(this.multiple==false&&this.baseX.length>=1)// 單傳
            {
                return;
            }
            // let obj=objectWithoutKey(this.xFileList[this.baseX.length-1])
            // this.baseX.push({'image':res,objectWithoutKey(this)})       
            // first add base 64
            this.baseX.push(res);
            this.giveBase64(this.baseX,this.multiple);
            return res;
        }
       
        
 


   
    }
    createPreview(res) //private
    {

        let number=this.xFileList.value.length+1;
        if((this.multiple==false && number>=2)||number>this.maxFileNumber)
        {
            return;
        }
        let obj={src:URL.createObjectURL(this.currentImage)};
        for(let key in this.attachment)
        {
            obj[key]=null;
        }
        this.xFileList.value.push(obj);
            // let obj=objectWithoutKey(this.xFileList[this.baseX.length-1])
            // this.baseX.push({'image':res,objectWithoutKey(this)})       
        triggerRef(this.xFileList);
                // 檢查檔案類型
        return res;
    }
    startUploadImage(fileList)
    {
        if(!this.multiple)
        {
            this.reseting();
        }

        /* ANCHOR 
           # XFileList is something that is able to preview
         xFileList 在每次上傳的時候一定會重置，因為她是預覽圖
        */
        this.fileList=fileList;
        this.uploadImage();
    }
    uploadImage()
    { 
        let number=this.xFileList.value.length+1;
        if(!(this.fileList?.length)||(this.multiple===false&&number>=2))
        {
            // 單傳
            // 或者fileList沒有資料了，全部上傳了
            (this.closeLoading)&&this.closeLoading();
            // (this.loading)&&this.loading();
            return;
        }
        else if(number>this.maxFileNumber)
        {
            
            // 說明圖片太多
            (this.closeLoading)&&this.closeLoading();
            this.error.value=`最多上傳${this.maxFileNumber}筆`;
            return ;
        }
     
        this.currentImage=this.fileList.shift();

        if(!this.currentImage)
        {
            (this.closeLoading)&&this.closeLoading();
            return;
        }
      
        // if(this.i>this.maxFileNumber)
        // {
        //     (this.loading)&&this.loading();
        //     this.error.value=`最多上傳${this.maxFileNumber}個檔案`;
        //     return; 
        // }
        
        if(['image/apng', 'image/avif', 'image/gif', 'image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'].includes(this.currentImage.type))
        {

            if((this.currentImage?.size??0)>=this.maxSize)
            {
                (this.closeLoading)&&this.closeLoading();
                this.error.value=`檔案不可超過${this.maxSizeVirtual}MB`;
                return;
            }
            this.reader.readAsDataURL(this.currentImage);
            // this.reader.readAsDataURL(this.currentImage).then(()=>{
            //     return this.uploadImage(fileList);
            // });
        }
        else
        {
            (this.closeLoading)&&this.closeLoading();
            this.error.value="存在不合法檔案";
            return;
        }
        // this.i++;
        return;
    }
    deleteFiles(index)
    {
        this.xFileList.value.splice(index,1);
        this.baseX.splice(index,1);
        triggerRef(this.xFileList);
    }
    reseting()
    {
        // this.i=0;
        this.xFileList.value.splice(0);
        this.baseX=[];
    }
    /*

    public function 
    */
}
