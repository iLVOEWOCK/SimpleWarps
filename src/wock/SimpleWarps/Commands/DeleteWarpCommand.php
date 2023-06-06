<?php

namespace wock\SimpleWarps\Commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use wock\SimpleWarps\Warps;

class DeleteWarpCommand extends BaseCommand {

    public function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("warp"));
        $this->setPermission("simplewarps.delwarp");
        $plugin = $this->getOwningPlugin();
        assert($plugin instanceof Warps);
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $warpName = $args["warp"];

        $warpManager = Warps::getInstance()->warpManager;
        $warps = $warpManager->getWarps();

        if (!isset($warps[$warpName])) {
            $message = $this->getMessage("warp_not_exist", ["{WARP.NAME}" => $warpName]);
            $sender->sendMessage($message);
            return;
        }

        $warpManager->deleteWarp($warpName);
        $warpManager->saveWarps();

        $deletedWarpMessage = $this->getMessage("warp_deleted", ["{WARP.NAME}" => $warpName]);
        $sender->sendMessage($deletedWarpMessage);
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
