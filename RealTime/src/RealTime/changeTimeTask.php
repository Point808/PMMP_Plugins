<?php
namespace RealTime;

use pocketmine\scheduler\PluginTask;

class changeTimeTask extends PluginTask{
    public $plugin;
    public function __construct($plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick) {
        $this->plugin->changeTime();

    }
}