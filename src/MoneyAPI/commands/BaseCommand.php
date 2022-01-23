<?php

declare(strict_types=1);

namespace MoneyAPI\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use MoneyAPI\utils\ConfigUtil;
use MoneyAPI\Main;

abstract class BaseCommand extends Command {

    private $playerCommand;
    private $perm;

    public function __construct(string $name, string $description, array $aliases = [], bool $playerCommand = false, bool $permission = false) {
        parent::__construct($name, $description, null, $aliases);

        $this->playerCommand = $playerCommand;

        if($permission) {
            Main::getInstance()->getPermissionManager()->setPermission("MoneyAPI.command." . $name);
            $this->perm = "MoneyAPI.command." . $name;
        }
    }

    public function execute(CommandSender $sender, string $label, array $args) : void {
        $permission = $this->perm;

        if($permission !== null && !Main::getInstance()->getPermissionManager()->hasPermission($sender, $permission)) {
            $sender->sendMessage(str_replace("{PERMISSION}", $permission, ConfigUtil::getMessage("basic.command-missing-permission")));
            return;
        }

        $this->onCommand($sender, $args);
    }

    public abstract function onCommand(CommandSender $sender, array $args) : void;
}