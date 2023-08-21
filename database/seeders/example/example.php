<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoNotAutoLoadTheExample extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $upOne=\App\Models\ServeClassification::create(['name'=>'aaa','back_body'=>'666']);
        // $upTwo=\App\Models\ServeClassification::create(['name'=>'bbb','back_body'=>'9998555111']);
        // \App\Models\Serve::create(['up_id'=>$upOne->id,'name'=>'22','back_body'=>'123']);
        // \App\Models\Serve::create(['up_id'=>$upTwo->id,'name'=>'22-1','back_body'=>'123-1']);
        \App\Models\User::create(['name'=>'測試用戶','email'=>'admin','password'=>Hash::make('g123456'),'manager'=>true]);
        $seoArray=[
            ['name'=>'最新消息','back_body'=>'sometext'],
            ['name'=>'服務','back_body'=>'sometext2'],
        ];
        foreach($seoArray as $out)
        {
            \App\Models\TheSeo::create($out);
        }
        // \App\Models\User::factory(30)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User', 
        //     'email' => 'test@example.com',
        // ]);
    }
}
