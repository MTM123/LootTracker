<?php
/**
 * Created by PhpStorm.
 * User: hardijs
 * Date: 19.28.1
 * Time: 11:23
 */

namespace App\Repositories;


use App\Http\Controllers\Api\MonsterLootController;

class DropRepository
{
    public function sortDrops($user) {

        $killKc = count($user->kills);
        $loot = [];
        $stackedLoot = [];
        $totalLootSum = 0;

        foreach ($user->kills as $kills) {
            foreach ($kills->items as $items) {
                $loot[] = ['id' => $items->item_id, 'price' => $items->price, 'name' => $items->name, 'qty' => $items->pivot->item_qty];
                $totalLootSum += $items->pivot->item_qty*$items->price;
                //Stack loot
                if (array_key_exists($items->id, $stackedLoot)) {
                    $stackedLoot[$items->id]->qty += $items->pivot->item_qty;
                    $stackedLoot[$items->id]->drop_times += 1;
                    $stackedLoot[$items->id]->total_price += $items->pivot->item_qty*$items->price;
                }else{
                    $stackedLoot[$items->id] = new \stdClass();
                    $stackedLoot[$items->id]->id = $items->item_id;
                    $stackedLoot[$items->id]->price = $items->price;
                    $stackedLoot[$items->id]->name = $items->name;
                    $stackedLoot[$items->id]->qty = $items->pivot->item_qty;
                    $stackedLoot[$items->id]->drop_times = 1;
                    $stackedLoot[$items->id]->total_price = $items->pivot->item_qty*$items->price;
                }
            }
        }

        //Sorting function
        $sortBy = "total_price"; //Add this to filter ['id','price','name','qty','drop_times','total_price',]
        if(array_key_exists(session(MonsterLootController::$SESSION_SORT_KEY),MonsterLootController::$sortSelect)){
            $sortBy = MonsterLootController::$sortSelect[session(MonsterLootController::$SESSION_SORT_KEY)]['sort'];
        }
        $sortFlip = false;
        $sortedLoot = [];
        foreach ($stackedLoot as $killsNooneCare) {
            $biggestSortById = 0;
            $biggestSortByValue = 0;
            foreach ($stackedLoot as $id => $kills){
                $val = ((array)$kills)[$sortBy];
                if ($biggestSortByValue <= $val && !array_key_exists($id, $sortedLoot)){
                    $biggestSortById = $id;
                    $biggestSortByValue = $val;
                }
            }
            $sortedLoot[$biggestSortById] = $stackedLoot[$biggestSortById];
        }


        if ($sortFlip) {
            $sortedLoot = array_reverse($sortedLoot, true);
        }

        //get ride of key values
        $resort = [];
        foreach ($sortedLoot as $id => $kills) {
            $resort[] = $kills;
        }

        session(['monster_stacked_loot' => $resort,'monster_loot' => $loot, 'monster_kc' => $killKc]);
        return (object) ['drops' => $resort, 'sortBy' => $sortBy, 'totalLootSum' => $totalLootSum];
    }
}