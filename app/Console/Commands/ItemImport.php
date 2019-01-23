<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ItemImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all OSRS items';

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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->comment("Reading import file");
        $list = Storage::disk('local')->get('list.txt');
        $re = '/(\d+),"(.*)"/m';
        preg_match_all($re, $list, $matches, PREG_SET_ORDER, 0);
        $this->comment("File parsed with '".count($matches)."' items");

        Item::query()->truncate();
        foreach ($matches as $id => $value){
            Item::create([
                'item_id' => $value[1],
                'name' => $value[2],
            ]);
            $this->comment("Importing: ".$value[1]. " => ".$value[2]);
        }
        $this->comment("Importing complete!");
        Storage::disk('local')->move('list.txt','list.txt.lock');
    }
}
