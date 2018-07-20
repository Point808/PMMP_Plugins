<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 06/07/2015
 * Time: 11:00
 */

namespace RealTime;


use pocketmine\scheduler\Task;

class tellTimeTask extends Task{

    /**
     * Actions to execute when run
     *
     * @param $currentTick
     *
     * @return void
     */
    public $plugin;
    public function __construct($plugin){
        $this->plugin = $plugin;
    }


    public function onRun(int $currentTick)
    {
        // TODO: Implement onRun() method.
    }
}