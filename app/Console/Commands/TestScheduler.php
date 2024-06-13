<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestScheduler extends Command
{
    protected $signature = 'test:scheduler';
    protected $description = 'Test the Laravel scheduler';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('The scheduler is working correctly.');
    }
}