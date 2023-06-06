<?php

namespace wock\SimpleWarps\Commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use wock\SimpleWarps\Commands\SubCommands\ReloadSubCommand;
use wock\SimpleWarps\Warps;

class WarpCommand extends BaseCommand {

    public function prepare(): void
    {
        $this->registerSubCommand(new ReloadSubCommand("reload"));
        $this->registerArgument(0, new RawStringArgument("warp"));
        $this->setPermission("simplewarps.warp");
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
        $warpManager = Warps::getInstance()->warpManager;

        if (!$warpManager->warpExists($warpName)) {
            $message = $this->getMessage("warp_not_exist", ["{WARP.NAME}" => $warpName]);
            $sender->sendMessage($message);
            return;
        }

        $warpPosition = $warpManager->getWarpCoordinates($warpName);

        if ($warpPosition === null) {
            $message = $this->getMessage("error_retrieving_position");
            $sender->sendMessage($message);
            return;
        }

        $sender->teleport($warpPosition);
        $message = $this->getMessage("teleported_to_warp", ["{WARP.NAME}" => $warpName]);
        $sender->sendMessage($message);
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
