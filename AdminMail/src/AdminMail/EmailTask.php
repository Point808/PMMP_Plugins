<?php

namespace AdminMail;

use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use AdminMail\Main;

class EmailTask extends Task {

	public function __construct(Main $main, string $adminmail, string $playername, string $adminsubject, string $adminmessage) {
		parent::__construct($main);
		$this->playername = $playername;
		$this->adminmail = $adminmail;
		$this->adminsubject = $adminsubject;
		$this->adminmessage = $adminmessage;
	}

	public function onRun(int $currentTick) {
		$player = $this->getOwner()->getServer()->getPlayer($this->playername);
		$name = $player->getName();
		$admin = $this->adminmail;
		$admin1 = $this->adminsubject;
		$admin1 = str_replace("PNAME", $name, $admin1);
		$admin2 = $this->adminmessage;
		$admin2 = str_replace("PNAME", $name, $admin2);
		mail($admin, $admin1, $admin2);
	}

}