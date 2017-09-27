<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\AdvertisingSpace;

class PcTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createData();
    }

    /**
     * create example data.
     *
     * @return void
     */
    protected function createData()
    {
        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:top',
            'alias' => '资讯首页banner',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                    'title' => '标题|string'
                ],
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:right',
            'alias' => '资讯右侧广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:feeds:right',
            'alias' => '动态右侧广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);
    }
}
