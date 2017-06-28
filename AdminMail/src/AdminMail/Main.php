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

	
	public function onLoad() { # Called when the plugin is being loaded by the server
		$this->getLogger()->info("Loading plugin..."); # Logs to the console
	}
	
	public function onEnable() { # Called when the plugin is enabled successfully without any errors
		$this->getServer()->getPluginManager()->registerEvents($this, $this); # Registers the events
        	@mkdir($this->getDataFolder());
	        $this->saveDefaultConfig();
	}
	
	public function onDisable() { # Called when the plugin is being disabled
		$this->getLogger()->info("Plugin disabled!"); # Logs to the console
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) { # This is called when a player rans the command from this plugin
		if($cmd->getName() == "example") { # Remove this if your plugin has only one command
			$sender->sendMessage("This is an example command"); # Sends to the sender
		}
	}




	public function onJoin(PlayerJoinEvent $event) { # Called when a player joins
		$player = $event->getPlayer();
		$name = $player->getName();
		$adminmail = $this->getConfig()->get("adminmail");
		$adminsubject = $this->getConfig()->get("adminsubject");
		$adminmessage = $this->getConfig()->get("adminmessage");


		//mail($adminmail, "PMMP - $name joined", "Hello, player $name has joined the server.");
		//sleep(10);
                $task = new EmailTask($this, $adminmail, $name, $adminsubject, $adminmessage); // Create the new class Task by calling
                $this->getServer()->getScheduler()->scheduleDelayedTask($task, 5*20); // Counted in ticks (1 second = 20 ticks)
	}
}