<?php

namespace App\Console\Commands;

use App\Models\Auction;
use Illuminate\Console\Command;

class CleanExpiredAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auctions:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete auctions that have passed their end time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = Auction::where('end_time', '<', now())->delete();
        $this->info("$deleted expired auctions deleted.");
    }
}
