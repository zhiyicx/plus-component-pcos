<?php

use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\base_path as component_base_path;

Route::middleware('web')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentPc\\Controllers')
    ->group(component_base_path('/routes/web.php'));

Route::prefix('/pc/admin')
   ->middleware('web')
   ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentPc\\AdminControllers')
   ->group(component_base_path('/routes/admin.php'));
