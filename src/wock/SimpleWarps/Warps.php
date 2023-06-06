<?php

namespace wock\SimpleWarps;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;
use pocketmine\plugin\PluginBase;
use wock\SimpleWarps\Commands\DeleteWarpCommand;
use wock\SimpleWarps\Commands\ListWarpsCommand;
use wock\SimpleWarps\Commands\SetWarpCommand;
use wock\SimpleWarps\Commands\WarpCommand;

class Warps extends PluginBase {

    private static ?Warps $instance = null;

    /** @var WarpManager */
    public WarpManager $warpManager;

    /**
     * @throws HookAlreadyRegistered
     */
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->warpManager = new WarpManager($this->getDataFolder() . "warps.yml");
        $this->warpManager->loadWarps();
        self::$instance = $this;
        $this->registerCommands();
        if(!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }    }

    public function onDisable(): void
    {
        $this->warpManager->saveWarps();
    }

    public function registerCommands(){
        $command_Map = $this->getServer()->getCommandMap();

        $command_Map->registerAll("simple_warps", [
            new SetWarpCommand($this, "setwarp", "Create a new warp"),
            new WarpCommand($this, "warp", "Teleport to a server warp"),
            new ListWarpsCommand($this, "listwarps", "List all existing warps on the server", ["warps"]),
            new DeleteWarpCommand($this, "deletewarp", "Delete an existing warp", ["delwarp", "rmwarp", "removewarp"])
        ]);
    }

    /**
     * @return static
     */
    public static function getInstance() : Warps {
        return self::$instance;
    }
}
