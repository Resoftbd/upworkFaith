<?php
if ($_SERVER['SERVER_NAME'] != 'perfect-source.local') {
    define('MYSQL_DSN', 'mysql:dbname=upwork_seeinside;host=localhost;charset=utf8');
    define('MYSQL_USER', 'root');
    define('MYSQL_PASSWORD', '');
	/*define('MYSQL_DSN', 'mysql:dbname=stop_seeins;host=localhost;charset=utf8');
    define('MYSQL_USER', 'stop_seeins');
    define('MYSQL_PASSWORD', '5tREbo90!1');*/
} else {
    define('MYSQL_DSN', 'mysql:dbname=upwork_seeinside;host=localhost;charset=utf8');
    define('MYSQL_USER', 'root');
    define('MYSQL_PASSWORD', 'rbhr[t');
}
    
