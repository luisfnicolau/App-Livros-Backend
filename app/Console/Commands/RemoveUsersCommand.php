<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class RemoveUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:remove {driver=facebook}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renews token from given driver, default to facebook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $driver = $this->argument('driver');
        $env = trim(strtolower(env('APP_ENV')));
        if($env != 'local' && $env != 'dev'){
            $this->error("You should NOT delete users in $env enviroment");
            return false;
        }
        switch ($driver) {
            case 'facebook':
                $this->deleteFacebookUsers();
                break;
            case 'google':
                $this->info('Google test users are not being used right now');
            default:
                $this->warning('Driver not supported');
                break;
        }
    }

    /**
     * Delete ALL facebook test users from database
     * 
     */
    private function deleteFacebookUsers(){
        //got from gist: https://gist.github.com/colmdoyle/1002713
        // jSON URL which should be requested
        $json_url = 'https://graph.facebook.com/'
                . env('FACEBOOK_APP_ID')
                . '/accounts/test-users?access_token='
                . env('FACEBOOK_APP_ID')
                . '|'
                . env('FACEBOOK_APP_SECRET');

        // Initializing curl
        $ch = curl_init( $json_url );

        // Configuring curl options
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
        );

        // Setting curl options
        curl_setopt_array( $ch, $options );

        // Getting results
        $result =  curl_exec($ch); // Getting jSON result string

        $resultArray = json_decode($result, true);

        foreach($resultArray as $nestedArray)
        {
            foreach($nestedArray as $superNested)
            {
                $testUID = $superNested["id"];
                $access_token = $superNested["access_token"];
                $string = "https://graph.facebook.com/$testUID?method=delete&access_token=$access_token";
                $nukeCh = curl_init($string);
                $this->comment($string);
                curl_exec($nukeCh);
                $user = User::where('facebook_id', $testUID)->first();
                if($user && $user->exists)
                    $user->delete();
            }
        }
    }
}
