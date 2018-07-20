<?php
namespace RealTime;

use pocketmine\scheduler\Task;

class changeTimeTask extends Task{
    public $plugin;
    public function __construct($plugin){
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick) {
        $this->plugin->changeTime();

    }
}