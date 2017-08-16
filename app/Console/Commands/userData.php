<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class userData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:user_id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user data to database';

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
    public function handle(){
        $file = public_path('user_data.csv');
        $delimiter = ',';       
            if(!file_exists($file) || !is_readable($file))
                return false;

            $header = null;
            $data = array();

            if (($handle = fopen($file,'r')) !== false){
                while (($row = fgetcsv($handle, 1000, $delimiter)) !==false){
                    if (!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }

            $meta_descArr = $data;
            for ($i = 0; $i < count($meta_descArr); $i ++){
                Users::firstOrCreate($meta_descArr[$i]);
            }
            echo "Users data added"."\n";
    }
}
