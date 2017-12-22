<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 19.12.2017
 * Time: 8:49
 */
$host = '127.0.0.1';
$db = 'teplomash';
$user = 'root';
$pass = '123';
$charset = 'utf8';

$dns = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE                => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE     => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES       => false,
];
$pdo = new PDO($dns, $user, $pass, $opt);