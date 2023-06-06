<?php

namespace wock\SimpleWarps;

use pocketmine\plugin\PluginBase;
use wock\SimpleWarps\Commands\DeleteWarpCommand;
use wock\SimpleWarps\Commands\ListWarpsCommand;
use wock\SimpleWarps\Commands\SetWarpCommand;
use wock\SimpleWarps\Commands\WarpCommand;

class Warps extends PluginBase {

    /** @var Warps */
    private static $instance = null;

    /** @var WarpManager */
    public $warpManager;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->warpManager = new WarpManager($this->getDataFolder() . "warps.yml");
        $this->warpManager->loadWarps();
        self::$instance = $this;
        $this->registerCommands();
    }

    public function onDisable(): void
    {
        $this->warpManager->saveWarps();
    }

    public function registerCommands(){
        $commandmap = $this->getServer()->getCommandMap();

        $commandmap->registerAll("simplewarps", [
            new SetWarpCommand($this, "setwarp", "Create a new warp"),
            new WarpCommand($this, "warp", "Teleport to a server warp"),
            new ListWarpsCommand($this, "listwarps", "List all existing warps on the server", ["warps"]),
            new DeleteWarpCommand($this, "deletewarp", "Delete an existing warp", ["delwarp", "rmwarp", "removeawarp"])
        ]);
    }

    /**
     * @return static
     */
    public static function getInstance() : Warps {
        return self::$instance;
    }
}
