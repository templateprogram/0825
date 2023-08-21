<?php

namespace App\Http\helper\image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
// 此為處理base 64 的檔案
// 然而真正的上傳出現在 ImageHelper裡面
class TheImgUpload
{
    private static $currentStoreDisk=null;
    public static function destroyImg(string $column='url',$path=null):void
    {
    
        $path=(is_null($path))?request()->imgPath:$path;
       
        $columnPath=str_replace("/storage/",'',$path);

        $model=app('App\Models\\'.explode('/',$columnPath)[0]);

        // \App\Models\FakeImg::where('url',$imgPath)->delete();
        // \App\Models\$model::where('url',$imgPath)->delete();
        $imgPath=storage_path('app/public/'.$columnPath);

        if(($linkClear=$model::where($column,$columnPath)->first())&&file_exists($imgPath)&&!is_dir($imgPath))
        {
            $linkClear->url=null;
            $linkClear->save();
            unlink($imgPath);

        }

    }
    // public function destroyAllImg(string $column="url",Array $imgPaths)
    // {
    //     $columnPath=str_replace("/storage/",'',reset($imgPaths));
    //     $model=app('App\Models\\'.explode('/',$columnPath)[0]);
    // }
    public static function destroyMultipleImgs(string $column='url')
    {
        /*
        imgPath is string or array
        */
        // if(is_array(request()->imgPath))
        // {
        //     return $this->destroyAllImg($column,request()->imgPath);
        // }
        $columnPath=str_replace("/storage/",'',request()->imgPath);

        $model=app('App\Models\\'.explode('/',$columnPath)[0]);

        // \App\Models\FakeImg::where('url',$imgPath)->delete();
        // \App\Models\$model::where('url',$imgPath)->delete();
        $imgPath=storage_path('app/public/'.$columnPath);
        
        if($model::where($column,$columnPath)->delete()&&file_exists($imgPath)&&!is_dir($imgPath))
        {
            unlink($imgPath);
            // return true;
        }
        // return false;
    }
    public function tinyMceFiles(Request $request)
    {
        // 搭配$request->validate(),先驗證圖片結果

        if ($request->file('file')) {
            // 建立時間(檔案路徑用)
            $date = date("Ymd");
            $nowTime = date("his");
            // 設定檔案名稱
            $imageName = $nowTime . '-' . Str::random(10) . '.' . $request->file('file')->extension();
            // 獲取圖片後另存路徑
            $path = $request->file('file')->storeAs('tinyMceFiles/' . $date . '/', $imageName, 'public');
            $path=str_replace('//','/',$path);
            // 因為直接回傳字串 在儲存字串時 就會將path 傳進去
            // 到時候可以直接獲取sql上的路徑
     

            $response = [
                'image'=>Storage::url($path),
                'message'=>'上傳成功',
            ];
            // 響應結果
            return response()->json($response,201);

        }
    }
    private static function uploadMultipleImages(\Illuminate\Database\Eloquent\Relations\HasMany $staticEloquent,Array &$currentLoop,Array &$attributes)
    {
        $date = date("Ymd");
        $nowTime = date("his");

        /*
           以下是註哥的原代碼，感謝提供。
        */
        $image_64=array_shift($currentLoop);
        $imageAttributes=array_shift($attributes);
        
              // 將base64分離副檔名
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
                // 獲取base64前面類型
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                // 去除base64類型剩下圖片
        $image = str_replace($replace, '', $image_64);
        if(is_null(self::$currentStoreDisk))
        {
            self::$currentStoreDisk=class_basename($staticEloquent->getRelated());
        }
            $column=self::$currentStoreDisk."/".$date . '/' . $nowTime . '-' . Str::uuid() . '.' . $extension;
            $staticEloquent->create([
                'url'=>$column,
                'attributes'=>(is_null($imageAttributes))?null:json_encode($imageAttributes),
            ]);
        Storage::disk('public')->put($column, base64_decode($image));
        if(!empty($currentLoop))
        {
            return self::{__FUNCTION__}(...func_get_args());
        }
        else
        {
            self::$currentStoreDisk=null;
            return;
        }
        
    }
    private static function uploadSingleImages(\Illuminate\Database\Eloquent\Model $eloquentModel,Array $imagesConfiguration,string $column)
    {
        //Img has many Relation string 關係之間的函數名稱
        // dd($imagesConfiguration);
        if(!is_null($clearingPath=$eloquentModel->{$column}))
        {
            self::destroyImg($column,$clearingPath);
        }
        if(!empty($imagesConfiguration['blobFiles']))
        {
            $date = date("Ymd");
            $nowTime = date("his");
            /*
               以下是註哥的原代碼，感謝提供。
            */
            $image_64=reset($imagesConfiguration['blobFiles']);
                  // 將base64分離副檔名
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
                    // 獲取base64前面類型
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                    // 去除base64類型剩下圖片
            $image = str_replace($replace, '', $image_64);
            $storeColumn=class_basename($eloquentModel)."/".$date . '/' . $nowTime . '-' . Str::uuid() . '.' . $extension;
            if(Schema::hasColumn($eloquentModel->getModel()->getTable(),'img_attributes'))
            {
                $imgAttribute=(is_null($eloquentModel?->img_attributes))?[]:json_decode($eloquentModel->img_attributes,true);
                $imgAttribute[$column]=@$imagesConfiguration['attributeArray'][0];
                $eloquentModel->img_attributes=json_encode($imgAttribute);
            }
            $eloquentModel->{$column}=$storeColumn;
            $eloquentModel->save();
            Storage::disk('public')->put($storeColumn, base64_decode($image));
            // return self::uploadSingleImages($eloquentModel,$imagesConfiguration['blobFiles']);
        }
    }
    private static function startMultipleImages(\Illuminate\Database\Eloquent\Model $eloquentModel,string $Relation,Array $imagesConfiguration)
    { 
       
       //Img has many Relation string 關係之間的函數名稱
    //    dd($imagesConfiguration);
        if(!empty($imagesConfiguration['blobFiles']))
        {
            // dd($eloquentModel->{$Relation}(),$imagesConfiguration['blobFiles'],($imagesConfiguration['attributeArray']??[]));
            $attributeArray=($imagesConfiguration['attributeArray']??[]);
            return self::uploadMultipleImages($eloquentModel->{$Relation}(),$imagesConfiguration['blobFiles'],$attributeArray);
        }
    }
    /*
        blobFiles:Array<object>
        columnOrRelation:string
        multiple:Boolean
    */
    public static function multipleFile(\Illuminate\Database\Eloquent\Model $eloquentModel,array|null $imageFiles=null):void
    {
        // dd(request()->imageFiles);
       
            // dd($request?->imageFiles);
            // $imageFiles=$request?->imageFiles;
            // dd($imageFiles);
            // dd(json_encode($imageFiles));
            if(is_null($imageFiles))
            {
                return;
            }
            if(is_string($imageFiles))
            {
                $imageFiles=json_decode($imageFiles,true);
            }
            
            // dd($imageFiles);
            //這邊使用列表是因為有可能不只有一個單傳，最好的方式應該是將columnOrRelation變成list索引值，實現
            //associative array 的功能
            foreach($imageFiles as $key=>$imagesConfiguration)
            {
            
                $multipleFlag=$imagesConfiguration['multiple'];
                if($multipleFlag===true)
                {
                     //多傳
                     if(!(method_exists($eloquentModel,$key)))
                     {
                        return;
                     }
                     self::startMultipleImages($eloquentModel,$key,$imagesConfiguration);
                  
                }
                else
                {
        
                    if(!Schema::hasColumn($eloquentModel->getTable(),$key))
                    {
                        return;
                    }
                    // TODO  單傳尚未處理 img Data
                    // 請 dd  img Data 來完成尚未完成的工作
                          // dd($imageFiles);
                    self::uploadSingleImages($eloquentModel,$imagesConfiguration,$key);
                }
            }
             
      
    
    }
}
