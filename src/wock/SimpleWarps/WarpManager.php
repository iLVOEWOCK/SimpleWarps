<?php

namespace wock\SimpleWarps;

use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class WarpManager
{
    /** @var string */
    private $dataFile;

    /** @var array */
    private $warps;

    public function __construct(string $dataFile)
    {
        $this->dataFile = $dataFile;
        $this->warps = [];
    }

    public function loadWarps(): void
    {
        $config = new Config($this->dataFile, Config::YAML);
        $this->warps = $config->get("warps", []);
    }

    public function saveWarps(): void
    {
        $config = new Config($this->dataFile, Config::YAML);
        $config->set("warps", $this->warps);
        $config->save();
    }

    public function createWarp(string $name, Position $position): bool
    {
        if (!$this->warpExists($name)) {
            $this->warps[$name] = $this->positionToArray($position);
            return true;
        }
        return false;
    }

    public function deleteWarp(string $name): bool
    {
        if ($this->warpExists($name)) {
            unset($this->warps[$name]);
            return true;
        }
        return false;
    }

    public function getWarpPosition(string $name): ?Position
    {
        if ($this->warpExists($name)) {
            $data = $this->warps[$name];
            return $this->arrayToPosition($data);
        }
        return null;
    }

    public function warpExists(string $name): bool
    {
        return isset($this->warps[$name]);
    }

    public function positionToArray(Position $position): array
    {
        return [
            "x" => $position->getX(),
            "y" => $position->getY(),
            "z" => $position->getZ(),
            "level" => $position->getWorld()->getFolderName()
        ];
    }

    public function arrayToPosition(array $data): Position
    {
        $x = $data["x"];
        $y = $data["y"];
        $z = $data["z"];
        $level = $data["level"];
        // You may need to fetch the Level object based on the level name if it's not already loaded
        $position = new Position($x, $y, $z, $level);
        return $position;
    }

    public function getWarp(string $warpName): ?Warps
    {
        return $this->warps[$warpName] ?? null;
    }

    public function getWarps(): array
    {
        return $this->warps;
    }

    public function getWarpCoordinates(string $name): ?Vector3
    {
        if ($this->warpExists($name)) {
            $data = $this->warps[$name];
            $x = $data["x"];
            $y = $data["y"];
            $z = $data["z"];
            return new Vector3($x, $y, $z);
        }
        return null;
    }
}
