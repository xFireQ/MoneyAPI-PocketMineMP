<?php

declare(strict_types=1);

namespace MoneyAPI\user;

use MoneyAPI\Main;
use pocketmine\player\Player;

class UserManager {

    private Main $main;
    /** @var User[] */
    private array $users = [];
    private array $xuids = [];

    public function __construct(Main $main) {
        $this->main = $main;

        $main->getDb()->query("CREATE TABLE IF NOT EXISTS 'users' (xuid VARCHAR(16), name VARCHAR(16), money FLOAT)");
        $this->load();
    }

    private function load() : void {
        $result = $this->main->getDb()->query("SELECT * FROM 'users'");

        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $user = $this->createUser($row["xuid"], $row["name"]);

            $user->setMoney($row["money"]);
        }
    }

    public function createUser(string $xuid, string $name) : User {
        $this->users[$xuid] = $user = new User($xuid, $name);
        $this->xuids[strtolower($name)] = $xuid;

        return $user;
    }

    /**
     * @param $data string|Player
     */
    public function getUser($data) : ?User {
        $xuid = $data;

        if($data instanceof Player) {
            $xuid = $data->getXuid();
        } else if(is_string($data) && !is_numeric($data)) { // player nickname
            $xuid = $this->xuids[strtolower($data)] ?? null;
        }

        if($xuid === null) {
            return null;
        }

        return $this->users[$xuid] ?? null;
    }

    public function userExists(Player $player) : bool {
        return isset($this->users[$player->getXuid()]);
    }

    public function saveUsers() {
        foreach($this->users as $user) {
            $xuid = $user->getXUID();
            $name = $user->getName();
            $money = $user->getMoney();

            $isInDatabase = !empty($this->main->getDb()->query("SELECT * FROM 'users' WHERE xuid = '" . $xuid . "' AND name = '" . $name . "'")->fetchArray());
            $sql = !$isInDatabase ? "INSERT INTO users (xuid, name, money) VALUES ('" . $xuid . "', '" . $name . "', '" . $money . "')" : "UPDATE 'users' SET money = '" . $money . "' WHERE xuid = '" . $xuid . "' AND name = '" . $name . "'";

            $this->main->getDb()->query($sql);
        }
    }
}
