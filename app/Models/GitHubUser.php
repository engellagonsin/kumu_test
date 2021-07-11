<?php

namespace App\Models;

class GitHubUser
{

    public static function generate($data) {
        $user = new GitHubUser;
        $user->login = $data['login'];
        $user->name = $data['name'];
        $user->company = $data['company'];
        $user->followers = $data['followers'];
        $user->publicRepositories = $data['public_repos'];
        //number of followers divided by the number of public repositories)
        if($data['followers'] > 0 && $data['public_repos'] > 0) {
            $user->aveFollowersPerPublicRepos = $data['followers'] / $data['public_repos'];
        } else {
            //set ave to 0
            $user->aveFollowersPerPublicRepos = 0;
        }
        
        $result = $user;
        
        return $result;
    }
}