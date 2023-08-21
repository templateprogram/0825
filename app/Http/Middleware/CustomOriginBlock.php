<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomOriginBlock
{
    private $sharedOrigin=[];
    private $localPortAccessible=17;
    private $sharedIps=[];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function __construct()
    {
        $this->sharedOrigin[]='https://master--mellow-lily-dc954a.netlify.app';
        $this->sharedOrigin[]="http://localhost:3000";
        for($i=0;$i<$this->localPortAccessible;$i++)
        {
            $portNumber=8000+$i;
            $this->sharedOrigin[]="127.0.0.1:$portNumber";
        }
        
    }
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request->getHttpHost());
        if(!in_array($request->getHttpHost(),$this->sharedOrigin))
        {
            abort(403,"you are restricted to access the site");
        }

        return $next($request);
    }
}
