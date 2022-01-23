<?php

declare(strict_types=1);

namespace MoneyAPI\user;

use pocketmine\player\Player;
use pocketmine\Server;

class User {

    private string $xuid;
    private string $name;

    private float $money = 0.0;

    public function __construct(string $xuid, string $name) {
        $this->xuid = $xuid;
        $this->name = $name;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getXUID() : string {
        return $this->xuid;
    }

    public function getPlayer(): ?Player {
        return Server::getInstance()->getPlayerExact($this->name);
    }

    public function getMoney() : float {
        return $this->money;
    }

    public function getFormatMoney() : string {
        return number_format($this->money, 2);
    }

    public function setMoney(float $money) : void {
        $this->money = max(0, $money); // money cannot be less than 0
    }

    public function addMoney(float $amount) : void {
        $this->money += $amount;
    }

    public function removeMoney(float $amount) : void {
        $this->setMoney($this->money - $amount);
    }
}