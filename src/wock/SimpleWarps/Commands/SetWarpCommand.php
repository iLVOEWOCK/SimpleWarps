<?php

namespace wock\SimpleWarps\Commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use wock\SimpleWarps\Warps;

class SetWarpCommand extends BaseCommand {

    public function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("warp"));
        $this->setPermission("simplewarps.setwarp");
        $plugin = $this->getOwningPlugin();
        assert($plugin instanceof Warps);
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $message = $this->getMessage("in_game_usage");
            $sender->sendMessage($message);
            return;
        }

        $warpName = $args["warp"];
        $warp = Warps::getInstance()->warpManager->getWarp($warpName);

        if ($warp !== null) {
            $message = $this->getMessage("warp_exists");
            $sender->sendMessage($message);
            return;
        }

        Warps::getInstance()->warpManager->createWarp($warpName, $sender->getPosition());

        $message = $this->getMessage("warp_set", ["{WARP.NAME}" => $warpName]);
        $sender->sendMessage($message);

        Warps::getInstance()->warpManager->saveWarps();
    }

    public function getMessage(string $messageKey, array $placeholders = []): string
    {
        $config = new Config(Warps::getInstance()->getDataFolder() . "messages.yml", Config::YAML);
        $message = $config->get($messageKey, "");
        $message = str_replace(array_keys($placeholders), array_values($placeholders), $message);
        return str_replace("&", "§", $message);
    }

    public function getPermission(): string
    {
        return $this->getPermission();
    }
}