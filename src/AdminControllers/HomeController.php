<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Zhiyi\Plus\Models\JWTCache;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\AuthToken;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class HomeController extends Controller
{
    use AuthenticatesUsers {
        login as traitLogin;
    }

    public function show(Request $request)
    {
        if (! $request->user()) {
            return redirect(route('admin'), 302);
        }

        // token
        $token = JWTCache::where('user_id', $request->user()->id)
            ->where('status', 0)
            ->value('value');

        $token = 'Bearer '.trim($token);

        return view('pcview::admin', [
            'token' => $token,
            'base_url' => route('pc:admin'),
            'csrf_token' => csrf_token(),
            'api' => url('api/v2'),
            'files' => url('/api/v2/files'),
        ]);
    }

    protected function menus()
    {
        $components = config('component');
        $menus = [];

        foreach ($components as $component => $info) {
            $info = (array) $info;
            $installer = array_get($info, 'installer');
            $installed = array_get($info, 'installed', false);

            if (! $installed || ! $installer) {
                continue;
            }

            $componentInfo = app($installer)->getComponentInfo();

            if (! $componentInfo) {
                continue;
            }

            $menus[$component] = [
                'name'  => $componentInfo->getName(),
                'icon'  => $componentInfo->getIcon(),
                'logo'  => $componentInfo->getLogo(),
                'admin' => $componentInfo->getAdminEntry(),
            ];
        }

        return $menus;
    }
}
