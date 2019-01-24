<?php

namespace App\Console\Commands;

use App\Models\MonsterKill;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class ImportOldUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:olduserdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import old user data';

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct( UserRepository $userRepository )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $importName = $this->ask('What is the username?');
        $userData = $this->userRepository->getUserByName($importName);

        if ($userData == null){
            $this->error("Cant find user with name $importName");
            return;
        }
        try {
            $list = Storage::disk('local')->get('olddbitems.json');
            $parsed = json_decode($list, false);
            foreach ($parsed as $id => $kill){
                if ($kill->username == $importName && !empty($kill->drops)){
                    //Parse name
                    $monsterParam[0] = $kill->npc_name;
                    $monsterParam[1] = "";
                    if (strpos($kill->npc_name,":") !== false){
                        $ex = explode(":",$kill->npc_name);
                        $monsterParam[0] = $ex[0];
                        $monsterParam[1] = $ex[1];
                    }
                    $monster = $this->userRepository->getMonster((object)['npc_name' => $monsterParam[0], 'npc_level' => $monsterParam[1]], $userData);
                    MonsterKill::create([
                        'user_id' => $userData->id,
                        'monster_id' => $monster->id,
                        'loot' => $kill->drops
                    ]);
                    $this->comment("Imported ".$monster->name);

                }
            }
            $this->comment("Import finished");
        }catch (FileNotFoundException $e) {
            $this->comment("File not found: /storage/app/olddbitems.json");
        }
    }
}
