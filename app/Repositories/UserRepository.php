<?php

namespace App\Repositories;

use App\Models\MonsterKill;
use App\Models\User;
use App\Models\Monster;
use App\Models\Item;

class UserRepository
{
    /**
     * @param $token
     * @param array $columns
     *
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    public function getUserByApiToken($token, $columns = ['*'])
    {
        return User::where('api_token', $token)->first($columns);
    }

    /**
     * @param User $user
     * @param $data
     *
     * @return array|null|string
     */
    public function updateUserKills(User $user, $data)
    {
        $this->updateUserName($user, $data->npc->killer);

        $this->addDrops($user, $data);

        return json_encode([
            'status' => 1,
            'message' => __('User kill data updated')
        ]);
    }

    /**
     * @param User $user
     * @param $name
     *
     * @return $this
     */
    protected function updateUserName(User $user, $name)
    {
        if ($user->name == null || $user->name != $name) {
            $user->name = $name;
            $user->save();
        }

        return $this;
    }

    /**
     * @param $params
     *
     * @return Monster|\Illuminate\Database\Eloquent\Model
     */
    protected function getMonster($params)
    {
        //Monster name validator;
        $regex = '/(.*)\((.*)\)/m';
        preg_match_all($regex, $params->npc_name, $matches, PREG_SET_ORDER, 0);
        if (count($matches) == 3){
            $params->npc_name = trim($matches[1]);
            $params->npc_level = trim($matches[2]);
        }

        $monster = Monster::where('name', $params->npc_name);

        if (!empty($params->npc_level)) {
            $monster->where('level', $params->npc_level);
        }

        $monster = $monster->first();

        if ($monster == null) {
            return Monster::create([
                'name' => $params->npc_name,
                'level' => $params->npc_level
            ]);
        }

        return $monster;
    }

    /**
     * @param User $user
     * @param $data
     *
     * @return MonsterKill|\Illuminate\Database\Eloquent\Model
     */
    protected function addDrops(User $user, $data)
    {
        $monster = $this->getMonster($data->npc);

        $loot = [];
        foreach ($data->drops as $drop) {
            $item = $this->getItemByName($drop, true);

            $loot[] = [
                'item_id' => $item->id,
                'item_qty' => $drop->item_qty
            ];
        }

        return MonsterKill::create([
            'user_id' => $user->id,
            'monster_id' => $monster->id,
            'loot' => json_encode($loot)
        ]);
    }

    /**
     * @param $params
     * @param bool $create
     *
     * @return Item|\Illuminate\Database\Eloquent\Model|null|object
     */
    protected function getItemByName($params, $create = false)
    {
        $item = Item::where('item_id', $params->item_id)
            ->where('name', $params->item_name)->first();

        if ($create && $item == null) {
            return Item::create([
                'item_id' => $params->item_id,
                'name' => $params->item_name
            ]);
        }

        return $item;
    }

    /**
     * @param $json
     *
     * @return mixed
     */
    public function parseJsonData($json)
    {
        return json_decode($json);
    }
}