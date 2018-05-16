<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:'.__DIR__.'/../data/data.sqlite',
    'charset' => 'utf8',
    'on afterOpen' => function($event) {
        $event->sender->createCommand('PRAGMA foreign_keys = ON;')->execute();
    }
];
