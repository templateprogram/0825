import { useForm } from '@inertiajs/vue3';
export default function customForm({id:id=null,formInputs:_formInputs}){
    let a =useForm({
        ...initForm(_formInputs),
        id:id,
    });
    return a;
}
const initForm=(formInputs)=>{
    let returned={'validateAttribute':{}};
    for(const binder in formInputs)
    {
        returned.validateAttribute[binder]=formInputs[binder].name;
        if(formInputs[binder]?.type=='img')
        {
            continue;
        }
        returned[binder]=null;
    }
    return returned;
}