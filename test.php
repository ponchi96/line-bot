<?php
//index.php
include_once __DIR__ . "/autoload.php";

$myClass = new MyClass();
$myClass->doSomething();

$member = new \MyNamespace\Member\Member();
$member->getMemberList();

$mailler = new \MyNamespace\Email\Mailler();
$mailler->sendMail();