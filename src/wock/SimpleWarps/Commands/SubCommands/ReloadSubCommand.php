<?php

namespace wock\SimpleWarps\Commands\SubCommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use wock\SimpleWarps\Warps;

class ReloadSubCommand extends BaseSubCommand {

    public function prepare(): void
    {
        $this->setPermission("simplewarps.reload");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $config = new Config(Warps::getInstance()->getDataFolder() . "messages.yml", Config::YAML);
        $config->reload();

        $sender->sendMessage("Messages reloaded successfully.");
    }
}
