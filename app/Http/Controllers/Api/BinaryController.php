<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 模型
use Illuminate\Support\Facades\DB;
use App\Models\Binary\Binary;
// 驗證
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
// 輸出格式
use App\Http\Resources\Api\Binary\BinaryResource;
use App\Http\Resources\Api\Binary\BinaryCollection;
// 例外處理
use App\Exceptions\Auth\JwtException;
// 輔助工具
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BinaryController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:jwt', ['except' => ['index',]]);
        $this->middleware('jwt.verify', ['except' => ['index',]]);
    }

    // protected function guard()
    // {
    //     return Auth::guard('jwt');
    // }

    public function index(Request $request)
    {
        $data = array('Controller' => 'BinaryController', 'function' => 'index', 'request' => $request);

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    // 期權 - 清單
    public function list(Request $request)
    {
        // 期權 - 緩存
        $key = sprintf('Binary:All:%s', date('YmdH'));
        $data = Cache::remember($key, env('CACHE_TIME', 12000), function () {
            $data = Binary::select('*')
                ->where('status', '1')
                ->orderBy('sort', 'ASC')
                ->get();

            return $data;
        });

        return new BinaryCollection($data);
    }
}
