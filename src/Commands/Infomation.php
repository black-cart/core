<?php

namespace BlackCart\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Throwable;

class Infomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bc:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get infomation black-cart';
    const LIMIT = 10;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(config('black-cart.name').' - '.config('black-cart.title'));
        $this->info(config('black-cart.auth').' <'.config('black-cart.email').'>');
        $this->info('Version: '.config('black-cart.version'));
        $this->info('Sub-version: '.config('black-cart.sub-version'));
        $this->info('Core: '.config('black-cart.core'));
        $this->info('Sub-core: '.config('black-cart.sub-core'));
        $this->info('Type: '.config('black-cart.type'));
        $this->info('Homepage: '.config('black-cart.homepage'));
        $this->info('Github: '.config('black-cart.github'));
        $this->info('Facebook: '.config('black-cart.facebook'));
        $this->info('API: '.config('black-cart.api_link'));

    }
}
