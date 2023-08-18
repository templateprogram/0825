<?php

namespace App\Http\Requests\Helper\Filter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder as eloBuilder;
use Illuminate\Database\Query\Builder as supBuilder;
class FilteredRequest extends FormRequest
{
    private array $filterReserve=['id','page','pages','imageFiles','search'];
    public function generateQuery(\Illuminate\Database\Eloquent\Model $modelClass,string ...$relations):eloBuilder|supBuilder
    {
        $queries=$this->queryFilter($modelClass);
        // dd($queries);
        $preLoad=(count($queries)==0)?$modelClass::query():$modelClass::where($queries);
        $queryBuilder=$preLoad->when($this->query('search'),fn($query,$search)=>$query->where('name','like',"%$search%"))
        ->with([...$relations]);
        return $queryBuilder;
    }
    public function queryfilter(\Illuminate\Database\Eloquent\Model $modelClass):array
    {
        $eloQuery=[];
        foreach($this?->query()??[] as $key=>$out)
        {
       
            if(in_array($key,$this->filterReserve)||!(Schema::hasColumn($modelClass->getTable(),$key)))
            {
                continue;
            }
            
            preg_match('/\[(.*?)\](.*)/',$out,$matches);
            // $operator=$matches[1];
            // $value=$matches[2];
            if(empty($matches))
            {
                $matches[1]='=';
                $matches[2]=$out;
            }

            array_push($eloQuery,[$key,$matches[1],$matches[2]]);

        }
        return $eloQuery;
    }
    public function rules(): array
    {
        return [];
    }
}
