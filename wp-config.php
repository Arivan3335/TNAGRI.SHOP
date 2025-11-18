<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tnagri_shop' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'R*~kw@nKUTg_8m%g/m%@;m~6z;Lmf&RJlNP:9SiQfsM;&gkQQfpKI>3gptvh>@>^' );
define( 'SECURE_AUTH_KEY',  '#ER`G%x&fzWHU*igNb9~[QCnlZYn*eZc!|j|MueMdO}PTr!n%iA7Ky=_di03H_vv' );
define( 'LOGGED_IN_KEY',    'E3xJ{l8PGzhrQEpV c3Nl<q+4ht+`@kZBu~eVUu=E|-FC_.Qi#pBjGaT 33/#^~y' );
define( 'NONCE_KEY',        'xLyeaLS~u@3}Ffqr]4-ZR3H|./~~BZA^6Ysp![qeLyK|T#N;zTBvu.?l*w$I1}kJ' );
define( 'AUTH_SALT',        '1S;#xAIVSNJH3&f:(ob-a8C:~7 5q|2Rg:w>N5(8g<WV<LX^O2t6t9l]r}t0?/jF' );
define( 'SECURE_AUTH_SALT', 'Sw8cWKs:I0H[nNCVVAgWU)i0!Y +^v%BfM|hm2o=/*#CeMGsz^Wn.0=YhFLv_}*i' );
define( 'LOGGED_IN_SALT',   '8!{ BFKH&%AZ#Yv+CQ47p/s0/$uJG#}!p8r;vbq e:~z[bMBtYz@2HfE},eE#WQN' );
define( 'NONCE_SALT',       '%fq*lq[(E~bBwW x/3YG{ 6pV#rI/Kf_CsMRM^:<8oCi/)ZWFOR` )A5,Jo(E$,x' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
