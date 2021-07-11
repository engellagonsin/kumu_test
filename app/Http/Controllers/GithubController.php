<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Models\GitHubUser;

class GithubController extends Controller
{
    //for getting github users
    public function getUserDetails(Request $request)
    {
        $METHOD_LOGGER = "POST:GithubController:get-user-details:";
        Log::info("{$METHOD_LOGGER}request:{$request}");
        $arr = array();

        if(count($request->all()) > 10){
            abort(400, "Max of 10 users per request.");
        }
        foreach ($request->all() as &$username) {
            $cachedGithubUser = Redis::get(env('REDIS_GITHUB_USER_KEY') . $username);
            if(isset($cachedGithubUser)){
                Log::info("{$METHOD_LOGGER}redis_cached:{$username}");
                $response = json_decode($cachedGithubUser, FALSE);
            }else{
                Log::info("{$METHOD_LOGGER}github_api:{$username}");
                $response = Http::get("https://api.github.com/users/{$username}");
                if(!$response->successful()){
                    continue;
                }
                Log::info("{$METHOD_LOGGER}github_api:response:{$response}");
                $response = GitHubUser::generate($response->json());
                //2mins expiration
                Log::info("{$METHOD_LOGGER}redis_set:{$username}");
                Redis::setex(env('REDIS_GITHUB_USER_KEY') . $username, env('REDIS_EXPIRATION_GITHUB_USER'), json_encode($response));
            }
            $arr[] = $response;
        }
        if(count($arr) > 0){
            Log::info("{$METHOD_LOGGER}sorting");
            usort($arr, function($a, $b) {
                return $a->name <=> $b->name;
            });
        }
        $response = json_encode($arr);
        
        Log::info("{$METHOD_LOGGER}response:{$response}");
        return $response;
    }
}
