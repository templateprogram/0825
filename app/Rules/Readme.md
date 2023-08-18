# 你可以使用在lang/en/validation.php 修改這些Rule的多國語系

## 如果你在目錄中沒有找到的話，可以使用 lang:publish

```
        
'methodAction'=> ['string',Rule::in(['create', 'delete','update'])],
'name'=>'nullable|string|min:191',
'back_body'=>'nullable|string|max:15000',
'id.*'=>'nullable|integer|min:1',
```