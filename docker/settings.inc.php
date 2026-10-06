<?php

// Use the project's sample settings in the container, without replacing the
// developer's ignored lib/settings.inc.php on the host.
require __DIR__ . '/settings-example.inc.php';

$config['meritBadges'] = true;
$absolute_server_URI = '//opencaching.localhost/';
$mp3url = '//opencaching.localhost/mp3';
$config['cookie']['domain'] = 'opencaching.localhost';

$dbserver = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'opencaching';
$dbusername = getenv('DB_USER') ?: 'opencaching';
$dbpasswd = getenv('DB_PASSWORD') ?: '';
$dbcharset = 'utf8mb4';

// The application uses these credentials for its automatic DB updates too.
$opt['db']['admin_username'] = $dbusername;
$opt['db']['admin_password'] = $dbpasswd;

$dynbasepath = '/srv/ocpl-dynamic-files/';
$mp3dir = $dynbasepath . 'mp3';
