<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'smadder');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Jf=kR,1qU;J&[Xpka 12kQ#$K<]Ln]b*>9/XBz}AC2rE2F2aDQE=2QrU6Xz+#5dM');
define('SECURE_AUTH_KEY',  '.~nS,@rpM@*$]REV9jF~+/76mxeh;)]^Fi=1s`BZw:BRr[#b2?[3~18~vy_l0kta');
define('LOGGED_IN_KEY',    '571sO=,uaDSH!O +y%=Dl<UG~PRH>NU#UQ1,RO+3^><!{L+g/5Roe:M+w(:a~CG@');
define('NONCE_KEY',        '[;/x8ZvTQP`/<.0$NFa!(A4Z)L$ywUT1u$2ZRB!>S}tqF3tNg7*dEx _YQAjQC:;');
define('AUTH_SALT',        'O9[XeTWx~iFrKq=Xbay2{<<O<9v1VQiV%K6lD[2`(qOX?|/F3$EYWYE X5%7HI0)');
define('SECURE_AUTH_SALT', 'myL_mGEB9GDB&X(0I[,(_v+A9D]S!q2Fof)X,VE)l1RW,b(3:r q4nwf>{0u@cb2');
define('LOGGED_IN_SALT',   '7ck8_~n}VQELhI:8}G?&vsYtW7@hKO!?j;`UrX&!KdcA(6Z7iJp@6P9tAM8S+2Jw');
define('NONCE_SALT',       'Cz47Xsp u=Q>+;q6>=)gdKy~l37fw?2 /7n.iF4NvzHlt]_;Zdb$?qUg,0.fr@cV');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
