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
define( 'DB_NAME', 'mwood' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Azerty123321!' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '{Qi(H;NrL46UsN{fV H6tuaB7BUw@,51q}uP  AZlC k<AoIX$`#]Si<x>bhh{q&' );
define( 'SECURE_AUTH_KEY',  'bGELJwGb6bseQT&fr%=={)w)#ip5K_pbSW$!Jf=;1zq!kWxDl+xhDqqS^.~5?1+i' );
define( 'LOGGED_IN_KEY',    '>zV8..^9bq5Gqw- @/%hF~(B?nWXRiyV~@[tGdyCVuOW1J#Jn.3@&u:tP$>SnAik' );
define( 'NONCE_KEY',        'x*&>*e1;Y?AD|L _Xz!DX2LSM;}<vv<!WI0/WKHc%a@t[2~97<(^?)D4 cWRZ/uO' );
define( 'AUTH_SALT',        ';*Lr.rC;eY )I_VSm <k9>KsBkteVUWT^|+OIN{sCxJH6rwn{!m8d5bCT%A3QI{4' );
define( 'SECURE_AUTH_SALT', 'gyGrKVK~7r8)zXP Cr~|4_ co`#>xbOPl$>>_tT4-JGI!-^VKOXicKxIc=sjOL)j' );
define( 'LOGGED_IN_SALT',   '(|6a2s!%um![|A~k^2u;sodb,&MmHQ{S26%</3w0Yqbdib|>%>8;$ONiX`Z4YVJ;' );
define( 'NONCE_SALT',       'B>=8@*EFhrcQpp7}&e)ACu]gg;Kh!eZ_wcaN1UQtXdz[,!7U)=7{i{a AR*([9[h' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
define('FS_METHOD','direct');
