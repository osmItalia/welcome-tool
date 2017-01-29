<?php
require('vendor/autoload.php');

$ini = parse_ini_file("variables.ini.php");
Flight::set('ini', $ini);
Flight::set('base', $ini['base_folder']);

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

session_start();

/*
 * This route handles authentication via oauth
 */
Flight::route(
    '/login',
    function () {
        $ini = Flight::get('ini');
        $server = new sabas\OAuth1\Client\Server\Openstreetmap(array(
            'identifier' => $ini['identifier'],
            'secret' => $ini['secret'],
            'callback_uri' => 'http://'.$_SERVER['HTTP_HOST'].Flight::get('base').'/login',
        ));

        if (isset($_SESSION['token_credentials']) && !isset($_GET['oauth_token'])) {
            if (!isset($_SESSION['token_credentials'])) {
                echo 'No token credentials.';
                exit(1);
            }
            $tokenCredentials = unserialize($_SESSION['token_credentials']);

            $user = $server->getUserDetails($tokenCredentials);
            $_SESSION['display_name'] = (string) $user->nickname;
            $_SESSION['user_id'] = (string) $user->uid;
            $_SESSION['user_picture'] = (string) $user->imageUrl;

            Flight::redirect('/');
        } elseif (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
            $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);
            $tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
            unset($_SESSION['temporary_credentials']);
            $_SESSION['token_credentials'] = serialize($tokenCredentials);
            session_write_close();

            Flight::redirect('/login');
        } elseif (!isset($_SESSION['token_credentials'])) {
            $temporaryCredentials = $server->getTemporaryCredentials();
            $_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
            session_write_close();
            $server->authorize($temporaryCredentials);
            exit;
        } else {

        }
    }
);

/*
 * Logout route, mainly for testing
 */
Flight::route(
    '/logout',
    function () {
        session_destroy();
        Flight::redirect('/');
    }
);

Flight::route(
    '/',
    function () {
        $users = Capsule::table('new_user')
            ->leftJoin('welcome_user', 'new_user.user_id', '=', 'welcome_user.uid')
            ->leftJoin(Capsule::raw("
                            (SELECT uid, MAX(timestamp) AS timestamp FROM notes GROUP BY uid) maxnotes
                        "), 'maxnotes.uid', '=', 'new_user.user_id')
            ->leftJoin('notes', 'maxnotes.timestamp', '=', 'notes.timestamp')
            ->where('registration_date', '>', strtotime('yesterday midnight'))
            ->where('registration_date', '<', strtotime('tomorrow midnight'))
            ->orderBy('registration_date', 'desc')
            ->get();

        Flight::render('user_table.php', [ 'results' => $users, 'day' => date('Ymd')], 'content');
        Flight::render('template.php', [ 'pTitle' => "Users who registered during the past day" ]);
    }
);

Flight::route(
    '/day(/@day)',
    function ($day) {
        if ($day === null) {
            $day = date('Ymd');
        }

        $date = DateTime::createFromFormat('YmdHi', $day.'0000');
        $start = $date->getTimestamp();
        $date->add(new DateInterval('P1D'));
        $end = $date->getTimestamp();

        $users = Capsule::table('new_user')
            ->leftJoin('welcome_user', 'new_user.user_id', '=', 'welcome_user.uid')
            ->leftJoin(Capsule::raw("
                            (SELECT uid, MAX(timestamp) AS timestamp FROM notes GROUP BY uid) maxnotes
                        "), 'maxnotes.uid', '=', 'new_user.user_id')
            ->leftJoin('notes', 'maxnotes.timestamp', '=', 'notes.timestamp')
            ->where('registration_date', '>', $start)
            ->where('registration_date', '<', $end)
            ->get();
        Flight::render('user_table.php', [ 'results' => $users, 'day' => $day ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Users who registered on ".$day ]);
    }
);

Flight::route(
    '/list(/@page)',
    function ($page) {
        /* pagination */
        if ($page === null || $page < 1) {
            $page = 1;
        } // 0 shows first 15, but it is page = 1
        $page--;
        $take = 50;
        $skip = $take * $page;

        $users = Capsule::table('new_user')
            ->leftJoin('welcome_user', 'new_user.user_id', '=', 'welcome_user.uid')
            ->leftJoin(Capsule::raw("
                            (SELECT uid, MAX(timestamp) AS timestamp FROM notes GROUP BY uid) maxnotes
                        "), 'maxnotes.uid', '=', 'new_user.user_id')
            ->leftJoin('notes', 'maxnotes.timestamp', '=', 'notes.timestamp')
            ->take($take)
            ->skip($skip)
            ->orderBy('registration_date', 'desc')
            ->get();
        Flight::render('user_table.php', [ 'results' => $users, 'function' => 'list', 'page' => $page+1 ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Registered users list (most recent first)" ]);
    }
);

Flight::route(
    '/welcomedByMe(/@page)',
    function ($page) {
        /* pagination */
        if ($page === null || $page < 1) {
            $page = 1;
        } // 0 shows first 15, but it is page = 1
        $page--;
        $take = 50;
        $skip = $take * $page;

        $users = Capsule::table('new_user')
            ->leftJoin('welcome_user', 'new_user.user_id', '=', 'welcome_user.uid')
            ->leftJoin(Capsule::raw("
                            (SELECT uid, MAX(timestamp) AS timestamp FROM notes GROUP BY uid) maxnotes
                        "), 'maxnotes.uid', '=', 'new_user.user_id')
            ->leftJoin('notes', 'maxnotes.timestamp', '=', 'notes.timestamp')
            ->take($take)
            ->skip($skip)
            ->where('welcomed_by', $_SESSION['display_name'])
            ->orderBy('registration_date', 'desc')
            ->get();
        Flight::render('user_table.php', [ 'results' => $users, 'function' => 'byMe', 'page' => $page+1 ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Registered users welcomed by me (most recent first)" ]);
    }
);


/*
 * Ajax functions to update welcomed and answered flags
 */
Flight::route(
    'POST /user/@id:[0-9]+/welcomed',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }
        $welcomed_by = $_SESSION['display_name'];
        if ($_POST['isWelcomed'] == 0) {
            $welcomed_by = '';
        }
        Capsule::table('welcome_user')
        ->where('uid', $id)
        ->update(
            [
                'welcomed' => $_POST['isWelcomed'],
                'welcomed_by' => $welcomed_by
            ]
        );
    }
);

Flight::route(
    'POST /user/@id:[0-9]+/answered',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }
        Capsule::table('welcome_user')
        ->where('uid', $id)
        ->update(
            [
                'answered' => $_POST['hasAnswered']
            ]
        );
    }
);

Flight::route(
    '/user/@id',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }
        $user = Capsule::table('new_user')
            ->leftJoin('welcome_user', 'new_user.user_id', '=', 'welcome_user.uid')
            ->where('new_user.user_id', $id)
            ->first();
        $notes = Capsule::table('notes')
            ->where('notes.uid', $id)
            ->orderBy('timestamp', 'desc')
            ->get();
        Flight::render('user.php', [ 'user' => $user, 'notes' => $notes ], 'content');
        Flight::render('template.php', [ 'pTitle' => "User ".$id ]);
    }
);

/*
 * Add a note
 */
Flight::route(
    'GET /note/@id',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }
        Flight::render('note.php', [ 'id' => $id ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Add a note on user ".$id ]);
    }
);

Flight::route(
    'POST /note/@id',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }
        Capsule::table('notes')->insert(
            [
                'uid' => $id,
                'timestamp' => time(),
                'author' => $_SESSION['display_name'],
                'type' => 'note',
                'note' => $_POST['note']
            ]
        );
        Flight::redirect('/user/'.$id);
    }
);

/*
 * Welcome message creation
 */

Flight::route(
    'GET /snippets/@lang',
    function ($lang) {
        $snip = Capsule::table('snippets')
            ->where('snippets.language', $lang)
            ->select('part', Capsule::raw('`text` AS hidden_text'), Capsule::raw('CONCAT(LEFT(`text`, 150), CASE WHEN LENGTH (`text`) > 150 THEN "..." ELSE "" END) AS text'))
            ->get();
        Flight::json($snip);
    }
);

Flight::route(
    'GET /welcome/@id:[0-9]+',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $lang = Capsule::table('languages')
            ->get();

        Flight::render('welcome.php', [ 'id' => $id, 'languages' => $lang ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Create welcome message for user ".$id ]);
    }
);

Flight::route(
    'POST /welcome/@id',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        Capsule::table('notes')->insert(
            [
                'uid' => $id,
                'timestamp' => time(),
                'author' => $_SESSION['display_name'],
                'type' => 'welcome',
                'note' => $_POST['message']
            ]
        );
        Flight::redirect('/user/'.$id);
    }
);

/*
 * Administration
 */

Flight::route(
    'GET /admin',
    function () {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $content = "<ul><li><a href='<?php echo Flight::get('base');?>/admin/languages'>Languages</a></li>";
        $content .= "<li><a href='<?php echo Flight::get('base');?>/admin/snippets'>Message snippets</a></li></ul>";
        Flight::render('template.php', [ 'pTitle' => "Admin", 'content' => $content ]);
    }
);

Flight::route(
    'GET /admin/languages',
    function () {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $lang = Capsule::table('languages')
            ->get();

        Flight::render('languages.php', [ 'languages' => $lang ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Languages admin" ]);
    }
);

Flight::route(
    'POST /admin/languages',
    function () {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $lang = Capsule::table('languages')
            ->insert(['iso' => $_POST['iso'], 'name' => $_POST['name']]);

        Flight::redirect('/admin/languages');
    }
);

Flight::route(
    'GET /admin/languages/delete/@language',
    function ($language) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $lang = Capsule::table('languages')
            ->where('iso', $language)
            ->delete();

        Flight::redirect('/admin/languages');
    }
);

Flight::route(
    'GET /admin/snippets',
    function () {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }
        $snip = Capsule::table('snippets')
            ->orderBy('language')
            ->get();

        Flight::render('snippets.php', [ 'snippets' => $snip ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Snippets admin" ]);
    }
);



Flight::route(
    'GET /admin/snippets/insert',
    function () {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $lang = Capsule::table('languages')
            ->get();

        Flight::render('snippets/insert.php', [ 'languages' => $lang  ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Snippets admin" ]);
    }
);

Flight::route(
    'POST /admin/snippets/insert',
    function () {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $snip = Capsule::table('snippets')
            ->insert(['language' => $_POST['iso'], 'part' => $_POST['part'], 'text' => $_POST['text']]);

        Flight::redirect('/admin/snippets');
    }
);

Flight::route(
    'GET /admin/snippets/delete/@id',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $snip = Capsule::table('snippets')
            ->where('id', $id)
            ->delete();

        Flight::redirect('/admin/snippets');
    }
);

Flight::route(
    'GET /admin/snippets/modify/@id',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $snip = Capsule::table('snippets')
            ->where('id', $id)
            ->first();
        $lang = Capsule::table('languages')
            ->get();

        Flight::render('snippets/modify.php', [ 'snippets' => $snip, 'languages' => $lang  ], 'content');
        Flight::render('template.php', [ 'pTitle' => "Snippets admin" ]);
    }
);

Flight::route(
    'POST /admin/snippets/modify/@id',
    function ($id) {
        if (!isset($_SESSION['display_name'])) {
            Flight::redirect('/');
        }

        $snip = Capsule::table('snippets')
            ->where('id', $id)
            ->update(['language' => $_POST['iso'], 'part' => $_POST['part'], 'text' => $_POST['text']]);

        Flight::redirect('/admin/snippets');
    }
);
Flight::start();
