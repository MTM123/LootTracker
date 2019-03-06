<?php
/**
 * Created by PhpStorm.
 * User: hardijs
 * Date: 19.28.1
 * Time: 11:23
 */

namespace App\Repositories;


use App\Http\Controllers\Api\MonsterLootController;
use App\Models\User;
use Carbon\Carbon;

class DropRepository
{
    /**
     * @var int
     */
    protected $_HOW_MANY_DROPS = 9;

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
                    $stackedLoot[$items->id]->drop_rate = number_format($killKc / $stackedLoot[$items->id]->drop_times, 0, "","");
                }else{
                    $stackedLoot[$items->id] = new \stdClass();
                    $stackedLoot[$items->id]->id = $items->item_id;
                    $stackedLoot[$items->id]->price = $items->price;
                    $stackedLoot[$items->id]->name = $items->name;
                    $stackedLoot[$items->id]->qty = $items->pivot->item_qty;
                    $stackedLoot[$items->id]->drop_times = 1;
                    $stackedLoot[$items->id]->total_price = $items->pivot->item_qty*$items->price;
                    $stackedLoot[$items->id]->drop_rate = number_format($killKc / $stackedLoot[$items->id]->drop_times, 0, "","");
                }
            }
        }

        //Sorting function
        $sortBy = "total_price"; //Add this to filter ['id','price','name','qty','drop_times','total_price',]
        if(array_key_exists(request()->sortby,MonsterLootController::$sortSelect)){
            $sortBy = MonsterLootController::$sortSelect[request()->sortby]['sort'];
        }
        $sortFlip = false;
        $sortedLoot = [];
        foreach ($stackedLoot as $killsNooneCare) {
            $biggestSortById = 0;
            $biggestSortByValue = 0;
            foreach ($stackedLoot as $id => $kills){

                $val = $kills->{$sortBy};
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

        //Put in session for graph when page is loaded
        session(['monster_stacked_loot' => $resort,'monster_loot' => $loot, 'monster_kc' => $killKc]);
        return (object) ['drops' => $resort, 'sortBy' => $sortBy, 'totalLootSum' => $totalLootSum, 'loot' => $loot];
    }

    public function last7DaysDrops($key)
    {
        $user = User::where('key', $key)->firstOrFail();

        $user->load(['kills' => function($query) {
            $query->whereBetween('created_at',[ Carbon::now()->startOfDay()->subDays(env("CONFIG_MAINPAGE_LOOT_HISTORY")), Carbon::now()->endOfDay()]);
            $query->orderBy('created_at', 'DESC');
        }, 'kills.items']);

        return $user;
    }

    public function formatForGraphData($data) {
        $allKills = [];
        foreach ($data->kills as $kills) {
            $incrimentId = date("d",strtotime($kills->created_at));
            $lootvalue = 0;

            if(!isset($allKills[$incrimentId])){
                $allKills[$incrimentId] = new \stdClass();
                $allKills[$incrimentId]->loot = 0;
                $allKills[$incrimentId]->date = date("Y-m-d",strtotime($kills->created_at));
            }

            foreach ($kills->items as $item) {
                $lootvalue += $item->price*$item->pivot->item_qty;
                if(!isset($allKills[$incrimentId]->valueable[$item->id])) {
                    $allKills[$incrimentId]->valueable[$item->id] = new \stdClass();
                    $allKills[$incrimentId]->valueable[$item->id]->name = $item->name;
                    $allKills[$incrimentId]->valueable[$item->id]->loot = 0;
                    $allKills[$incrimentId]->valueable[$item->id]->qty = 0;
                    $allKills[$incrimentId]->valueable[$item->id]->id = $item->item_id;
                }
                $allKills[$incrimentId]->valueable[$item->id]->name = $item->name;
                $allKills[$incrimentId]->valueable[$item->id]->loot += $item->price*$item->pivot->item_qty;
                $allKills[$incrimentId]->valueable[$item->id]->qty += $item->pivot->item_qty;
            }
            $allKills[$incrimentId]->loot += $lootvalue;
        }

        //Sort
        foreach ($allKills as $id => $value) {
            //Sort for the day
            $sorted = [];
            foreach ($allKills[$id]->valueable as $vid => $drops) {
                $maxVal = -1;
                $maxId = -1;
                foreach ($allKills[$id]->valueable as $ids => $drops2) {
                    if ($maxVal <= $drops2->loot && !array_key_exists($ids, $sorted)) {
                        $maxVal = $drops2->loot;
                        $maxId = $ids;
                    }
                }
                //var_dump($allKills[$id]->valueable);exit;
                $sorted[$maxId] = $allKills[$id]->valueable[$maxId];
                if(count($sorted) >= $this->_HOW_MANY_DROPS) {
                    break;
                }
            }
            $allKills[$id]->valueable = $sorted;
        }

        return $allKills;
    }
}