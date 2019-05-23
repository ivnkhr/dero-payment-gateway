<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateApiSecret extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:apisecret';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new API secret';

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
        $value = hash('sha256', time().env('APP_KEY'));
        $key = "DEROPAY_API_SECRET";
        
        $value = preg_replace('/\s+/', '', $value); //replace special ch
        $key = strtoupper($key); //force upper for security
        $env = file_get_contents(isset($env_path) ? $env_path : base_path('.env')); //fet .env file
        $env = str_replace("$key=" . env($key), "$key=" . $value, $env); //replace value
        /** Save file eith new content */
        $env = file_put_contents(isset($env_path) ? $env_path : base_path('.env'), $env);
        
        $this->info("DEROPAY_API_SECRET is set to {$value}");
    }
}
