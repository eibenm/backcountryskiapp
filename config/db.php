<?php

// return [
//     'class' => 'yii\db\Connection',
//     'dsn' => 'mysql:host=localhost;dbname=yii2basic',
//     'username' => 'root',
//     'password' => '',
//     'charset' => 'utf8',

//     // Schema cache options (for production environment)
//     //'enableSchemaCache' => true,
//     //'schemaCacheDuration' => 60,
//     //'schemaCache' => 'cache',
// ];

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:'.__DIR__.'/../data/data.sqlite',
    'charset' => 'utf8',
    'on afterOpen' => function($event) {
        $event->sender->createCommand('PRAGMA foreign_keys = ON;')->execute();
    }
];
