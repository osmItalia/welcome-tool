<?php
/*
 * Cron to update the user changeset number in the database
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

//// Script output
$verbose = 0; //no output

if (isset($argv[1]) && $argv[1]=="-v") {
    $verbose=1;
    print "Update changeset number in DB\n";
}

//make a call to the API to retrieve the user total changeset number
function total_changesets($user_id)
{
    $site = 'http://www.openstreetmap.org';
    $api  = '/api/0.6/user';

    $url = $site.$api.'/'.$user_id;

    $xml = simpleXML_load_file($url, "SimpleXMLElement", LIBXML_NOCDATA);

    if ($xml ===  false) {
        return -1; //error
    } else {
        return  $xml->user->changesets['count'];
    }
}

///////////////MAIN

$back_time = time() - ($ini['days_ago'] * 24 * 60 * 60);

$query = "SELECT user_id FROM new_user WHERE
          (total_changesets IS NULL OR total_changesets < ".$ini['cs_upper_limit'].")
          AND first_edit_date > $back_time
          ORDER BY user_id DESC
         ";


$result = Capsule::select($query);

foreach ($result as $elem) {
    sleep(3); // three seconds delay between each call
    $tcs = total_changesets($elem->user_id);

    if ($tcs > 0) {
        Capsule::insert(
            'UPDATE new_user SET total_changesets='. $tcs .',last_check='. time() .' WHERE user_id='. $elem->user_id
        );
    }

    if ($verbose) {
        print "User ID: $elem->user_id  cs:$tcs\n";
    }
}
