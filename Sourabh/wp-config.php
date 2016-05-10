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
define('DB_NAME', 'team4');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'T%S5Tn%~gwt-~tL^E:j~[8Yh)$TvE2_*C+gttGuz?pdZ+=79GH?}/*t<=etW(^2w');
define('SECURE_AUTH_KEY',  'T!|E*+=sdv$&a+-}SK5CJnvF_R(!tnvJ9Z1sl|I6IU)}Aw,)lu={bG;T_=^k{;Ci');
define('LOGGED_IN_KEY',    'nMxp1f3722ZB|;t Y&1>P:|YA,iEQw]r@-d|34?Z4S=j^%75nL ah/ZKIUsQbge~');
define('NONCE_KEY',        '8gxt}V[RJT-o2Il|C)F>U+pcwimf;{Q,%JJDc9&q<m[,H|StJu6i(|B%NbsJC]0#');
define('AUTH_SALT',        ')~*>Si9(!/wv+6n!bC&u@=amfZeKp#SqxQ@RUmPEFPJ(N>KE tKCdamAZJ1|H2.U');
define('SECURE_AUTH_SALT', '+&9|{G]x+U<iG85~ceC L}Ph~/PTNS@:[)9-~dAiQWdpHrne0[j#3d}<*$0pa79|');
define('LOGGED_IN_SALT',   '?%$Bd;5SU.J}(oOhLQ>AiJzjc]}6{9*/VUC/9ds4N%~)T3U-lWRX-R6 )r&*E- $');
define('NONCE_SALT',       'y+]FhnnrowMg>)+}r!KNObxAm06jO+DA79=[XtcLY?Z;3EOx++6_-hKU6,U$>E)p');

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
