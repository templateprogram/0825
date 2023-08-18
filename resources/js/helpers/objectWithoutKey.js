export default function objectWithoutKey(object,key){
    const {[key]:deletedkey,...otherKeys}=object;
    return otherKeys;
}
export function clearobj(obj)
{
    for(const key in obj)
    {
        delete obj[key];
    }
}