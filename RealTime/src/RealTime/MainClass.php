<?php

namespace RealTime;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\utils\Config;
use pocketmine\scheduler\CallbackTask;



class MainClass extends PluginBase implements Listener{

    public $config;
	

	public function onLoad(){

	}

	public function onEnable(){
		@mkdir($this->getDataFolder()); // Creation dossier contenant config
	 
		
		$this->getLogger()->info(TextFormat::DARK_GREEN . "RealTime enabled.");


    $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array() );

	
	if ($this->config->__isset('Time changes')== false){
	
	$this->config->set('Time changes', +0);
	}
	
	
	if ($this->config->__isset('Tell time')==false){
	$this->config->set('Tell time', true);
	}
	
	
	if ($this->config->__isset('Tell time interval in minuts')==false){
	$this->config->set('Tell time interval in minuts', 5);
	}
	
	$this->config->save();
	
		
		
		
	$tTimeInterval = $this->config->get('Tell time interval in minuts')*20*60;
	$tTime = $this->config->get('Tell time');

		
	
$modify = $this->config->get('Time changes');


$test = date('H') + ($modify);

		if ($test > 23){
		$test = $test - 24;
		}
		
		$this->getLogger()->info(TextFormat::DARK_GREEN ."[RealTime] It is " . $test. " (20h is same as 8pm)");		
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new changeTimeTask($this), 120);
		$this->changeTime();
		
		if ($tTime == true) {
		$this->getLogger()->info($tTimeInterval);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new tellTimeTask($this), $tTimeInterval);
		$this->tellTime();
		}
												
		
		
    }
	
	
	public function onDisable(){
		$this->config->save();

		$this->getLogger()->info(TextFormat::DARK_RED . "RealTime disabled.");
	}

	
	
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		
		if(!($sender instanceof Player)){
		$this->getLogger()->info(TextFormat::DARK_RED. "[RealTime] Please run this command in-game.\n");						
		}else{
			
            $w =$sender->getLevel();
            $world = $w->getName();

            if ($command->getName() == "hour"){
            $modify = ($this->config->get("Time changes"));
            $times = date('H');
            $minutes = date('i');
            $time = $times + ($modify);

            if ($time > 23){
            $time = $time - 24;
            }

            $reponse = ("[RealTime] It is ". $time .":". $minutes ."");
            $sender->sendMessage($reponse);
            $this->changeTime();
            return true;
            }
		
		

			   
			

		if ($command->getName() == "rt")	{
	        switch ($args[0]){
				

                case "enable" :

                $this->config->set( $world, 0);
                $reponse = ("[RealTime] RealTime enabled for the world '".$world."'");
                $sender->sendMessage($reponse);
                $this->config->save();
                $this->changeTime();
                return true;

                break;







                case "disable" :

                                $this->config->set( $world, 3);


                                $reponse = ("[RealTime] Time unlock (RealTime disabled) for the world '".$world."'");
                $sender->sendMessage($reponse);
                $this->config->save();
                $this->changeTime();
                                return true;

                            break;


                case "day" :
                                 $this->config->set( $world, 1);
                                                         //Il faut adapter le file put content a la nouvelle API
                                $reponse = ("[RealTime] Time lock to day for the world '".$world."'");


                                $sender->sendMessage($reponse);
                                $this->config->save();
                            $this->changeTime();
                        return true;
            break;


                case "night" :

                                $this->config->set( $world, 2);

                                $reponse = ("[RealTime] Time lock to night for the world '".$world."'");
                $sender->sendMessage($reponse);
                $this->config->save();
                $this->changeTime();
            return true;

                            break;

                case "sunrise":

                                 $this->config->set( $world, 5);

                                $reponse = ("[RealTime] Time lock to sunrise for the world '". $world."'");
                $sender->sendMessage($reponse);
                $this->config->save();
                $this->changeTime();

            return true;

            break;
                case "sunset":

                                 $this->config->set( $world, 4);

                                $reponse = ("[RealTime] Time lock to sunset for the world '" . $world."'");
                $sender->sendMessage($reponse);
                $this->config->save();
                $this->changeTime();
                return true;

                break;

                case "lock":
                $currentTick = $w->getTime();


                $this->config->set( $world, 6);
                $this->config->set($world."-tick",$currentTick);
                $this->config->save();

                $reponse = ("[RealTime] Time lock to the tick ".$currentTick." on world '" . $world."'");
                $sender->sendMessage($reponse);
                $this->changeTime();
                return true;
                break;

                    }



        }else{
            $this->changeTime();
            return false;
				
						
					
}
		
		

	
}

}
	
		




	public function changeTime(){
  
 $modify = $this->config->get('Time changes');
	$times = date('H');
	$time = $times + $modify;
	
	$monde = $this->getServer()->getLevels();
	
	
	foreach($monde as $w){
		
		if ($this->config->__isset($w->getName())!== true){
				
				
				
				
	$this->config->set($w->getName(),3);
		
		}
		
		$mode = $this->config->get($w->getName());


		
	if ($mode == 6){
		$ticks = $this->config->get($w->getName()."-tick");
		
	}
	
	if ($mode == 1){
		$ticks = 6001;
	}
	if ($mode == 4){
		$ticks = 12000;
	}
	if ($mode == 5){
		$ticks = 5;
	}

	if ($mode == 2){
		$ticks = 13751;		
	}
	
	
	
	
	if ($mode == 0){
		if ($time > 23){
		$time = $time - 24;
	}
		
	if ($time == 0) {
		$ticks = 18000;
		}
			if ($time == 24) {
		$ticks = 18000;
		}
	if ($time == 1) {
		$ticks = 19000;
		}	
	if ($time == 2) {
		$ticks = 19500;
		}	
	if ($time == 3) {
		$ticks = 20000;
		}	
	if ($time == 4) {
		$ticks = 20500;
		}	
	if ($time == 5) {
		$ticks = 21000;
		}	
	if ($time == 6) {
		$ticks = 21500;
		}	
	if ($time == 7) {
		$ticks = 22500;
		}	
	if ($time == 8) {
		$ticks = 0;
		}	
	if ($time == 9) {
		$ticks = 2000;
		}	
	if ($time == 10) {
		$ticks = 4000;
		}	
	if ($time == 11) {
		$ticks = 5000;
		}	
	if ($time == 12) {
		$ticks = 6000;
		}	
	if ($time == 13) {
		$ticks = 7000;
		}	
	if ($time == 14) {
		$ticks = 8001;
		}	
	if ($time == 15) {
		$ticks = 9001;
		}	
	if ($time == 16) {
		$ticks = 10251;
		}	
	if ($time == 17) {
		$ticks = 11251;
		}	
	if ($time == 18) {
		$ticks = 12751;
		}	
	if ($time == 19) {
		$ticks = 13001;
		}	
	if ($time == 20) {
		$ticks = 13500;
		}	
	if ($time == 21) {
		$ticks = 15001;
		}	
	if ($time == 22) {
		$ticks = 16751;
		}	
	if ($time == 23) {
		$ticks = 17501;
		}
}



if ($mode !== 3){
	
				
				$w->checkTime();
				$w->setTime($ticks);
				
				$w->startTime();
				
				$w->stopTime();
			$w->checkTime();
	
	}

elseif ($mode === 3){
	$w->checkTime();
	$w->startTime();
	$w->checkTime();
}




}

}



public function tellTime(){
	
	
    		
    	 //$modify = $this->config->get("Time changes");
	$times = date('H');
	$minutes = date('i');
	$time = $times;// + $modify;

		if ($time > 23){
		$time = $time - 24;
	}
	
Server::getInstance()->broadcastMessage("[RealTime] It is ". $time .":". $minutes );

	}
	
	



}