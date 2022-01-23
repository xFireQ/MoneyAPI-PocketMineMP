<?php

declare(strict_types=1);

namespace MoneyAPI\listener\player;

use MoneyAPI\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinListener implements Listener {

    private Main $main;

    public function __construct(Main $main) {
        $this->main = $main;
    }

    /**
     * @param PlayerJoinEvent $e
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function onJoinRegisterPlayer(PlayerJoinEvent $e) : void {
        $player = $e->getPlayer();
        $userManager = $this->main->getUserManager();

        if(!$userManager->userExists($player)) {
            $userManager->createUser($player->getXuid(), $player->getName());
        }
    } 
}
