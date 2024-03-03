<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDirectAccess  // 保護路由的中間件，阻止用戶訪問這些路由
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    // 通過檢查請求的 header，例如 Referer，來判斷請求是直接從瀏覽器地址欄輸入的，還是作為內部流程的一部分。
    public function handle(Request $request, Closure $next): Response
    {
        // 檢查 Referer header 是否存在，並且是否來自同一個網站
        if (!$request->headers->has('referer') || !str_starts_with($request->headers->get('referer'), $request->getSchemeAndHttpHost())) {
            // 如果不是，可以重定向到首頁或顯示錯誤訊息
            return redirect('/noaccess');
        }

        return $next($request);
    }
}
