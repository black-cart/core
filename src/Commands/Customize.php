<?php

namespace BlackCart\Core\Commands;

use Illuminate\Console\Command;
use Throwable;
use DB;

class Customize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bc:customize {obj?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customize obj';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $obj = $this->argument('obj');
        switch ($obj) {
            case 'admin':
                $this->call('vendor:publish', ['--tag' => 'bc:config-admin']);
                $this->call('vendor:publish', ['--tag' => 'bc:view-admin']);
                break;
            
            case 'validation':
                $this->call('vendor:publish', ['--tag' => 'bc:config-validation']);
                break;

            default:
                $this->info('Nothing');
                break;
        }
    }
}
