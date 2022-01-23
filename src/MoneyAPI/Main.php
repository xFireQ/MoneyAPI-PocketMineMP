<?php

declare(strict_types=1);

namespace MoneyAPI;

use MoneyAPI\commands\player\MyMoneyCommand;
use MoneyAPI\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use MoneyAPI\commands\admin\AMoneyCommand;
use MoneyAPI\user\UserManager;
use MoneyAPI\listener\player\JoinListener;

class Main extends PluginBase {

    private static Main $instance;

    private Config $pluginConfig;
    private \SQLite3 $db;
    private UserManager $userManager;
    private PermissionManager $permissionManager;

    public static function getInstance() : Main {
        return self::$instance;
    }

    public function onEnable() : void {
        self::$instance = $this;
        $this->saveResource("config.yml");
        $this->pluginConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->db = new \SQLite3($this->getDataFolder() . "DataBase.db");
        $this->userManager = new UserManager($this);
        $this->permissionManager = new PermissionManager();

        $this->initCommands();
        $this->initListeners();
        $this->getLogger()->info("Plugin zostal wlaczony!");
    }

    public function onDisable() : void {
        $this->userManager->saveUsers();

        $this->getLogger()->info("Plugin zostal wylaczony!");
    }

    private function initListeners() : void {
        $this->getServer()->getPluginManager()->registerEvents(new JoinListener($this), $this);
    }

    public function initCommands() : void {
        $commands = [
            new AMoneyCommand($this),
            new MyMoneyCommand($this)
        ];

        $this->getServer()->getCommandMap()->registerAll("moneyapi", $commands);
    }

    public function getDb() : \SQLite3 {
        return $this->db;
    }

    public function getPluginConfig() : Config {
        return $this->pluginConfig;
    }

    public function getUserManager() : UserManager {
        return $this->userManager;
    }

    public function getPermissionManager() : PermissionManager {
        return $this->permissionManager;
    }
}
