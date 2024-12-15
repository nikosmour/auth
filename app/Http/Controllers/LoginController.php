<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (session()->has('cookies1')) {
            $cookie = session('cookies1');
        }else {
            $response = Http::get('https://mussa.upnet.gr/user/index.php?action=showAccountInfo',);

// Get cookies from the response
            $cookies = $response->cookies();  // This returns an array of cookies
            $cookiesArray = [];
            foreach ($cookies as $cookie) {
                $cookiesArray[$cookie->getName()] = $cookie->getValue();
            }
        }
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/png,image/svg+xml,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.5',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Origin' => 'https://mussa.upnet.gr',
            'DNT' => '1',
            'Sec-GPC' => '1',
            'Connection' => 'keep-alive',
            'Referer' => 'https://mussa.upnet.gr/user/index.php?action=showAccountInfo',
            'Upgrade-Insecure-Requests' => '1',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'same-origin',
            'Sec-Fetch-User' => '?1',
            'Priority' => 'u=0, i', // if this header is needed
        ])->
        withCookies($cookiesArray, 'mussa.upnet.gr')->
        post('https://mussa.upnet.gr/user/index.php?action=showAccountInfo', [
            'username' => 'up1019943',
            'password' => 'password',
            'submit'=>'Σύνδεση',
            'post'=>1,
            'mode'=> 2,
        ]);



        if ($response->successful()) {
            $cookies = $response->cookies();  // This returns an array of cookies
            $cookiesArray=[];
            foreach ($cookies as $cookie) {
                $cookiesArray[$cookie->getName()]=$cookie->getValue();
            }
            session(["cookies1"=>$cookiesArray]);
            $htmlContent = $response->body();

            $crawler = new Crawler($htmlContent);

// Extract the first <table> from the page
            $table = $crawler->filter('table')->first();

            if ($table->count()) {
                $tableHtml = $table->outerHtml(); // Get the full HTML of the table
                dd($tableHtml);
            } else {
                echo'No table found on the page';
                dd($htmlContent);

            }
        } else {
            dd($response->status(), $response->body());
        }
        /*return response()->json([
            'error' => 'invalid_credentials',
        ]);*/
    }
}
