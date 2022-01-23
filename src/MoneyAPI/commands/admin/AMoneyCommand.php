<?php

declare(strict_types=1);

namespace MoneyAPI\commands\admin;

use MoneyAPI\commands\BaseCommand;
use MoneyAPI\Main;
use MoneyAPI\utils\ConfigUtil;
use pocketmine\command\CommandSender;

class AMoneyCommand extends BaseCommand {

    private Main $main;

    public function __construct(Main $main) {
        parent::__construct("adminmoney", "Command Money", ["am", "amonety", "amoney", "adminmonety"], false, true);

        $this->main = $main;
    }

    public function onCommand(CommandSender $sender, array $args) : void {
        if(empty($args)) {
            $this->sendUsage($sender);
            return;
        }

        $userManager = $this->main->getUserManager();

        switch(strtolower($args[0])) {
            case "sprawdz":
                if(!isset($args[1])) {
                    $sender->sendMessage(ConfigUtil::getMessage("commands.amoney.name"));
                    return;
                }

                $user = $userManager->getUser($args[1]);

                if($user === null) {
                    $sender->sendMessage(ConfigUtil::getMessage("commands.amoney.userNotFound"));
                    return;
                }

                $message = ConfigUtil::getMessage("commands.amoney.money");
                $message = str_replace("{MONEY}", $user->getFormatMoney(), $message);
                $message = str_replace("{NAME}", $user->getName(), $message);

                $sender->sendMessage($message);
                break;
            case "ustaw":
            case "dodaj":
            case "usun":
                if(!isset($args[1])) {
                    $sender->sendMessage(ConfigUtil::getMessage("commands.amoney.name"));
                    return;
                }

                if(!isset($args[2])) {
                    $sender->sendMessage(ConfigUtil::getMessage("commands.amoney.quantity"));
                    return;
                }

                if(!is_numeric($args[2])) {
                    $sender->sendMessage(ConfigUtil::getMessage("commands.amoney.numeric"));
                    return;
                }

                $user = $userManager->getUser($args[1]);
                $money = floatval($args[2]);

                if($user === null) {
                    $sender->sendMessage(ConfigUtil::getMessage("commands.amoney.userNotFound"));
                    return;
                }

                $senderMessage = "";
                $targetMessage = "";

                switch($args[0]) {
                    case "ustaw":
                        $user->setMoney($money);

                        $senderMessage = ConfigUtil::getMessage("commands.amoney.set");
                        $targetMessage = ConfigUtil::getMessage("commands.amoney.got");
                        break;
                    case "dodaj":
                        $user->addMoney($money);

                        $senderMessage = ConfigUtil::getMessage("commands.amoney.add");
                        $targetMessage = ConfigUtil::getMessage("commands.amoney.gotadd");

                        break;
                    case "usun":
                        $user->removeMoney($money);

                        $senderMessage = ConfigUtil::getMessage("commands.amoney.remove");
                        $targetMessage = ConfigUtil::getMessage("commands.amoney.gotremove");
                        break;
                }

                $senderMessage = str_replace("{NAME}", $user->getName(), $senderMessage);

                $targetMessage = str_replace("{MONEY}", number_format($money, 2), $targetMessage);
                $targetMessage = str_replace("{NAME}", $sender->getName(), $targetMessage);

                $sender->sendMessage($senderMessage);

                if(($player = $user->getPlayer()) !== null) {
                    $player->sendMessage($targetMessage);
                }
                break;
            default:
                $this->sendUsage($sender);
        }
    }

    private function sendUsage(CommandSender $sender) {
        $messages = ConfigUtil::getMessage("commands.amoney.usage");

        foreach($messages as $message) {
            $sender->sendMessage($message);
        }
    }
}