# create cahce database

```
 php artisan cache:table
 php artisan storage:link
``` 
先將 cache 先創立好

## 一些注意事項:
使用 page 頁面的時候，記得把request的name拔掉，因為通常一頁式的關於我們之類的，不會有name必填的狀況，只有列表才有。

## 配置:
將CustomOriginBlock 加入 kernel 裡Api 陣列中。
