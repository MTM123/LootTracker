<?php

namespace App\Console\Commands;

use App\Models\MonsterKill;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDropLoot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:droploot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix json type drop loot for monsters';

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
        DB::raw("UPDATE `monster_kills` SET `loot` = TRIM(BOTH '\"' FROM `loot`)");
        $this->comment("Fixed");
    }
}
