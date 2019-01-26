<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class UpdatePrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates osrs item prices';

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
        $content = file_get_contents("https://rsbuddy.com/exchange/summary.json");
        $allData = json_decode($content);

        Item::where('item_id', 995)->update(['price' => 1]);

        foreach ($allData as $items) {
            Item::where('name', $items->name)->update(['price' => $items->overall_average]);
            $this->comment("Price updated for: ".$items->name." -> ".$items->overall_average);
        }
    }
}
