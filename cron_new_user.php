<?php
/*
 * Cron to download the feed and update the database
 */
require('vendor/autoload.php');

$ini = parse_ini_file("variables.ini.php");

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection(
    [
    'driver'    => 'mysql',
    'host'      => $ini['host'],
    'database'  => $ini['database'],
    'username'  => $ini['username'],
    'password'  => $ini['password'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    ]
);

$capsule->setAsGlobal();

$capsule->bootEloquent();

$xml = simplexml_load_file($ini['feed']);
$entries =$xml->entry;

$users = [];

$keyMap = [
    'UID' => 'user_id',
    'Contributor' => 'username',
    'Mapper since' => 'first_edit_date',
    'Registration' => 'registration_date',
    'First active near' => 'first_edit_location',
    'First Changeset' => 'first_changeset_id',
    'Editor used' => 'first_changeset_editor',
];

foreach ($entries as $entry) {
    $content = $entry->content;
    $matches = [];
    preg_match_all('/<b>(.+):<\/b>(.+)<\/br>/', $content, $matches);

    $tempUser = [];
    $count = count($matches[1]);
    for ($i = 0; $i < $count; $i++) {
        $value = $matches[2][$i];
        $regValue = '';
        if (preg_match('/<a.*>(.*)<\/a>/', $value, $regValue)) {
            $value = $regValue[1];
        }
        $key = $keyMap[$matches[1][$i]];
        if ($key == 'registration_date' || $key == 'first_edit_date') {
            $dt  = DateTime::createFromFormat('Y-m-d H:i:s', trim($value));
            $value = $dt->getTimestamp();
        }
        $tempUser[$key] = trim($value);
    }
    $users[] = $tempUser;

    Capsule::insert(
        'INSERT INTO new_user (user_id, username, registration_date, first_edit_date, first_edit_location, first_changeset_id, first_changeset_editor) VALUES (?, ?, ?, ?, ?, ?, ?) '.
        'ON DUPLICATE KEY UPDATE username=?, registration_date=?, first_edit_date=?, first_edit_location=?, first_changeset_id=?, first_changeset_editor=?',
        [$tempUser['user_id'], $tempUser['username'], $tempUser['registration_date'], $tempUser['first_edit_date'], $tempUser['first_edit_location'], $tempUser['first_changeset_id'], $tempUser['first_changeset_editor'], $tempUser['username'], $tempUser['registration_date'], $tempUser['first_edit_date'], $tempUser['first_edit_location'], $tempUser['first_changeset_id'], $tempUser['first_changeset_editor']]
    );

    Capsule::insert(
        'INSERT INTO welcome_user (uid, welcomed, welcomed_by, welcomed_on, answered) VALUES (?, 0, ?, 0, 0) '.
        'ON DUPLICATE KEY UPDATE uid=?',
        [$tempUser['user_id'], "", $tempUser['user_id']]
    );
}
