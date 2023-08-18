function isObject(v) {
    return '[object Object]' === Object.prototype.toString.call(v);
};

export default function jsonSort(o)
{
    if(!isObject(o))
    {
        return o;
    }
    if (Array.isArray(o)) {
        return o.sort().map(JSON.sort);
    } else if (isObject(o)) {
        return Object
            .keys(o)
        .sort()
            .reduce(function(a, k) {
                a[k] = JSON.sort(o[k]);

                return a;
            }, {});
    }

    return o;
}