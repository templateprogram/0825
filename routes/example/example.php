<?php

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TheBigImageImg;
use Illuminate\Support\Facades\Route;
use App\Http\helper\image\TheImgUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\ServeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\FakeSomeController;
use App\Http\Controllers\Admin\FakeImageController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TheBigImageController;
use App\Http\Controllers\Admin\ServeClassificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// 註冊頁面跟首頁不對外開放
Route::get('/', function () {
    return redirect()->to('/login', 301);
});
Route::get('/register', function () {
    return redirect()->to('/login', 301);
});
Route::post('/register', function () {
    return redirect()->to('/login', 301);
});


// 主要後臺群組
/*
find config.jetstream
find variable auth_session
getting into namespace auth_session variable connected to 

*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
    /*
    api
    */
    Route::post('/destroyMultipleImgs',[TheImgUpload::class,'destroyMultipleImgs']);
    Route::post('/tinymce',[TheImgUpload::class,'tinyMceFiles']);
    Route::post('/destroyImg/{column?}',[TheImgUpload::class,'destroyImg']);
    // 會員中心
    // here to add all the inertia Request sidebar 
    /*
        路由創建完後 記得在HandleInertiaRequest 裡面增加全域變數，來增加新的sidebar 功能列表
        創建新的 program 請使用 php artisan make:controller Admin/aaa --resource --model=member 
        --model member 看需要的model 是什麼
    */
    Route::resource('/admin-thebigimage', TheBigImageController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-fakeSome', FakeSomeController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-serve-classification', ServeClassificationController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-member', App\Http\Controllers\Admin\AdminMemberController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-fakeImage', FakeImageController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-serve', ServeController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-teammember', TeamMemberController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-contact', ContactController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-about', AboutController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-banner', BannerController::class, ['except' => ['create', 'show', 'edit']]);
    Route::resource('/admin-product', ProductController::class, ['except' => ['create', 'show', 'edit']]);
});


// 這邊都是測試
//Example
Route::get('/example', function () {

    $user = new App\Models\User();

    return Inertia::render('Example', [
        'users' => $user->all(),
        'filter' => $user->query()->when(request()->search, fn ($query) => $query->where('email', 'like', '%' . request()->search . '%')->get()),
    ]);
})->name('example');

// tinyMic
Route::post('/tinymce', function (Request $request) {

    $request->validate([
        'file' => ['image', 'max:5120', 'required']
    ]);

    if ($request->file('file')) {
        // 建立時間(檔案路徑用)
        $date = date("Ymd");
        $nowTime = date("his");
        // 設定檔案名稱
        $imageName = $nowTime . '-' . Str::random(10) . '.' . $request->file('file')->extension();
        // 獲取圖片後另存路徑
        $path = $request->file('file')->storeAs('tinyMceFiles/' . $date, $imageName, 'public');

        // 到時候可以直接獲取sql上的路徑
        $response = [
            'image' => "http://" . $request->server("HTTP_HOST") . "/storage/$path"
        ];
        // 響應結果
        return response($response);
    }
});


// function fileUpload($request)
// {
//     // 設定時間(給檔名用)
//     $date = date("Ymd");
//     $nowTime = date("his");
//     // 獲取圖片檔案
//     $image_64 = $request->fileItems;
//     // 將base64分離副檔名
//     $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
//     // 獲取base64前面類型
//     $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
//     // 去除base64類型剩下圖片
//     $image = str_replace($replace, '', $image_64);
//     // 將圖片路徑設定好，並加上副檔名
//     $imageName = 'FileUpload/' . $date . '/' . $nowTime . '-' . Str::random(10) . '.' . $extension;;
//     // 保存圖片轉換base64
//     Storage::disk('public')->put($imageName, base64_decode($image));
// }

//imgPost
Route::post('/file-upload', function (Request $request) {

    // 請記住php.ini post_max_size =8M post不能超過這個大小，可以從伺服器端去改


    if ($request->fileItems != null) {
        for ($i = 0; $i < count($request->fileItems); $i++) {
            // 設定時間(給檔名用)
            $date = date("Ymd");
            $nowTime = date("his");
            // 獲取圖片檔案
            $image_64 = $request->fileItems[$i];
            // 將base64分離副檔名
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
            // 獲取base64前面類型
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
            // 去除base64類型剩下圖片
            $image = str_replace($replace, '', $image_64);
            // 將圖片路徑設定好，並加上副檔名
            $imageName = 'FileUpload/' . $date . '/' . $nowTime . '-' . Str::random(10) . '.' . $extension;;
            // 保存圖片轉換base64
            Storage::disk('public')->put($imageName, base64_decode($image));
        }

        $response = [
            'message' => '成功上傳' . count($request->fileItems) . '圖片',
        ];
        return response($response);
    }
});

//textarea
Route::post('/textarea', function (Request $request) {
    Validator::make($request->all(), [
        'formItem.*.codeText' => 'string|max:1024',
    ], ['formItem.*.codeText.string' => '格式須為為字串符', 'formItem.*.codeText.max' => '字元不可超過1024個'])->validate();
});
