### Wbudowane Komendy
Wtyczka posiada wbudowane komendy takie jak
```
/mymoney - sprawdza swoje monety
/adminmoney - zarzadza monetami gracza itp
```

### Jak uzyc wtyczki?
To bardzo proste na poczatku dodaj impot
```use MoneyAPI\user\UserManager;```

### Jak sprawdzic monety gracza?

```
$nick = $sender->getName();
$user = UserManager::getUser($nick)->getMoney();
$money = $user->getMoney();

$sender->sendMessage("Twoje monety " . $money);

```

### Jak dodac monety?

```
$nick = $sender->getName();
$user = UserManager::getUser($nick)->getMoney();
$user->addMoney(2); // dodaje 2 monety
```

### Jak usunac monety?

```
$nick = $sender->getName();
$user = UserManager::getUser($nick)->getMoney();
$user->removeMoney(2); // usuwa 2 monety
```

### Jak ustawic monety?

```
$nick = $sender->getName();
$user = UserManager::getUser($nick)->getMoney();
$user->setMoney(2); // ustawia 2 monety
```

### Jak zrobic np sklep?

```
$nick = $sender->getName();
$user = UserManager::getUser($nick)->getMoney();
$money = $user->getMoney();

if($money >= 0.99) {
   //posiada monety
} else {
   //nie posiada monet
}

```
# MoneyAPI-PocketMineMP
