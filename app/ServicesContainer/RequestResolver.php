<?php

namespace App\ServicesContainer;

use Illuminate\Support\Facades\Schema;
use App\Http\helper\image\TheImgUpload;

class RequestResolver
{
    private $requestValues;
    private $request;
    private $images;
    public function __construct(\Illuminate\Http\Request|null $request)
    {
        $this->request=$request;
        $this->requestValues=$request?->except(['id','validateAttribute','imageFiles','imgDataAttributes']);
        $this->images=$request?->imageFiles;
    }
    private function upload(\Illuminate\Database\Eloquent\Model $eloquentModel)
    {

        TheImgUpload::multipleFile($eloquentModel,$this->images);
    }
    private function imgDataAttributesEdit(\Illuminate\Database\Eloquent\Model $model)
    {
        foreach($this?->request?->imgDataAttributes??[] as $key=>$out)
        {
            if($out['multiple']===true)
            {
                foreach($out['data'] as $id=>$attributes)
                {
                    $model->{$key}()->where('id',$id)->update(['attributes'=>json_encode($attributes)]);
                }

            }
            else
            {
                if(Schema::hasColumn($model->getModel()->getTable(),'img_attributes'))
                {

                    $model->img_attributes=json_encode($out['data']);
                }
                $model->save();
            }
            // dd($out->multiple);
        }
        // dd($this->request->imgDataAttributes);
    }
    public function update(int $id,\Illuminate\Database\Eloquent\Model $model)
    {
        if(is_null($this->request)||is_null($id))
        {
            return;
        }
        if($this->request->method()==='PUT')
        {
            $theTarget=$model::where('id',$id)?->first();
            $this->imgDataAttributesEdit($theTarget);
            if(is_null($theTarget))
            {
                return;
            }
            $model::where('id',$id)->update($this->requestValues);
            session()->flash('flash.bannerStyle', 'green');
            session()->flash('flash.banner', '更新成功');
            $this->upload($theTarget);
        }
        else
        {
            // patch will not use images
            $model::where('id',$id)->update($this->requestValues);
        }
       
    }
    public function store(\Illuminate\Database\Eloquent\Model $model)
    {
        if(is_null($this->request))
        {
            return;
        }
        if ($this->request->postName === 'delete') {
            $model::destroy($this->request->id);
            session()->flash('flash.bannerStyle', 'red');
            session()->flash('flash.banner', '刪除完畢');
        }
        $theTarget=$model::create($this->requestValues);
        $this->upload($theTarget);
        session()->flash('flash.bannerStyle', 'green');
        session()->flash('flash.banner', '新增完畢');
    }
}
