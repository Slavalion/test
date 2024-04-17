<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictIP
{
    /**
     * Array of available IP for API interaction
     *
     * @var array
     */
    public $availableIPs = [
        // Local
        '127.0.0.1',

        // My VPN for Dev
        '185.104.115.2',

        // Socrobotic API
        '46.21.250.40',

        //
        '95.217.106.252',
        '95.217.108.45',

        '116.202.222.173',
        '168.119.11.222',
        '195.201.169.96',
        '195.201.198.51',
        '88.99.250.149',
        '195.201.164.51',
        '135.181.214.163',
        '178.63.93.45',
        '195.201.246.113',
        '88.99.149.95',
        '195.201.198.81',
        '176.9.7.99',
        '213.239.197.146',
        '95.217.106.252',
        '95.217.56.247',
        '95.216.70.59',
        '195.201.202.55',
        '94.130.19.147',
        '195.201.169.226',
        '159.69.58.216',
        '195.201.109.240',
        '144.76.43.53',
        '94.130.19.145',
        '178.63.82.175',
        '195.201.198.231',
        '195.201.206.122',
        '195.201.192.16',
        '88.99.209.157',
        '136.243.150.181',
        '94.130.8.217',
        '195.201.163.32',
        '195.201.241.143',
        '195.201.246.74',
        '65.108.202.178',
        '65.21.197.232',
        '65.21.82.80',
        '195.201.198.50',
        '195.201.166.177',
        '159.69.58.216',
        '148.251.0.144',
        '195.201.241.138',
        '195.3.222.21',
        '213.32.7.133',
        '95.214.52.150',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->ip(), $this->availableIPs)) {
            abort(403, 'You are restricted! Your IP is not allowed!');
        }

        return $next($request);
    }
}
