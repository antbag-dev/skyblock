<?php
/**
 *  _____    ____    ____   __  __  __  ______
 * |  __ \  / __ \  / __ \ |  \/  |/_ ||____  |
 * | |__) || |  | || |  | || \  / | | |    / /
 * |  _  / | |  | || |  | || |\/| | | |   / /
 * | | \ \ | |__| || |__| || |  | | | |  / /
 * |_|  \_\ \____/  \____/ |_|  |_| |_| /_/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

namespace SkyBlock\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use SkyBlock\command\defaults\HelpCommand;
use SkyBlock\SkyBlock;

class IsleCommandMap extends Command {
    
    /** @var SkyBlock */
    private $plugin;
    
    /** @var IsleCommand[] */
    private $commands = [];
    
    /**
     * IsleCommandMap constructor.
     * @param SkyBlock $plugin
     */
    public function __construct(SkyBlock $plugin) {
        $this->plugin = $plugin;
        $this->registerCommand(new HelpCommand($this));
        parent::__construct("isle", "SkyBlock command", "Usage: /is", [
            "isle",
            "island",
            "sb",
            "skyblock"
        ]);
    }
    
    /**
     * @return IsleCommand[]
     */
    public function getCommands(): array {
        return $this->commands;
    }
    
    /**
     * @param string $alias
     * @return null|IsleCommand
     */
    public function getCommand(string $alias): ?IsleCommand {
        foreach($this->commands as $key => $subcommand) {
            if(in_array(strtolower($alias), $subcommand->getAliases())) {
                return $subcommand;
            }
        }
        return null;
    }
    
    /**
     * @param IsleCommand $command
     */
    public function registerCommand(IsleCommand $command) {
        $this->commands[] = $command;
    }
    
    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof Player) {
            $sender->sendMessage("Please, run this command in game");
            return;
        }
        
        $session = $this->plugin->getSessionManager()->getSession($sender);
        if(isset($args[0]) and $this->getCommand($args[0]) != null){
            $this->getCommand(array_shift($args))->onCommand($session, $args);
        } else {
            $session->sendTranslatedMessage("TRY_USING_HELP");
        }
    }
    
}