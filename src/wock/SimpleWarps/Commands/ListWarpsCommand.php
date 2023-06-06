<?php

namespace wock\SimpleWarps\Commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use wock\SimpleWarps\Warps;

class ListWarpsCommand extends BaseCommand {

    public function prepare(): void
    {
        $this->setPermission("simplewarps.listwarps");
        $plugin = $this->getOwningPlugin();
        assert($plugin instanceof Warps);
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $warpManager = Warps::getInstance()->warpManager;
        $warps = $warpManager->getWarps();

        if (empty($warps)) {
            $message = $this->getMessage("no_warps_available");
            $sender->sendMessage($message);
            return;
        }

        $existingWarpsMessage = $this->getMessage("existing_warps");
        $sender->sendMessage($existingWarpsMessage);

        $warpNames = implode(", ", array_keys($warps));
        $warpNamesMessage = $this->getMessage("warp_names", ["{WARP.NAMES}" => $warpNames]);
        $sender->sendMessage($warpNamesMessage);
    }

    public function getMessage(string $messageKey, array $placeholders = []): string
    {
        $config = new Config(Warps::getInstance()->getDataFolder() . "messages.yml", Config::YAML);
        $message = $config->get($messageKey, "");
        $message = str_replace(array_keys($placeholders), array_values($placeholders), $message);
        return str_replace("&", "§", $message);
    }

    public function getPermission()
    {
        $this->getPermission();
    }
}