<?php

declare(strict_types=1);

namespace MoneyAPI\commands\player;

use MoneyAPI\commands\BaseCommand;
use MoneyAPI\utils\ConfigUtil;
use pocketmine\command\CommandSender;
use MoneyAPI\Main;
use pocketmine\player\Player;

class MyMoneyCommand extends BaseCommand {

    private Main $main;

    public function __construct(Main $main) {
        parent::__construct("mymoney", "MyMoney Command", ["money", "monety", "mojemonety"], true);

        $this->main = $main;
    }

    /** @var Player $sender */
    public function onCommand(CommandSender $sender, array $args) : void {
        $user = $this->main->getUserManager()->getUser($sender);

        $sender->sendMessage(str_replace("{MONEY}", $user->getFormatMoney(), ConfigUtil::getMessage("commands.mymoney.money")));
    }
}
