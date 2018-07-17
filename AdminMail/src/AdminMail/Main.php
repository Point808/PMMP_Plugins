<?php
	
namespace AdminMail;

use pocketmine\Player;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
        	@mkdir($this->getDataFolder());
	        $this->saveDefaultConfig();
	}
	
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		$adminmail = $this->getConfig()->get("adminmail");
		$adminsubject = $this->getConfig()->get("adminsubject");
		$adminmessage = $this->getConfig()->get("adminmessage");
                $task = new EmailTask($this, $adminmail, $name, $adminsubject, $adminmessage);
                $this->getScheduler()->scheduleDelayedTask($task, 5*20);
	}

}