<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'darat_wp706' );

/** Database username */
define( 'DB_USER', 'darat_wp706' );

/** Database password */
define( 'DB_PASSWORD', 'S5(]93wpjS' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('DISABLE_WP_CRON', true);

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
define( 'AUTH_KEY',         'cnlsbdhglirse4xvq6qgbz3lg32oqcuzmy3m6pwflhzsygcx5j7rjatzerkmn1uh' );
define( 'SECURE_AUTH_KEY',  'e9dcg1hzmpvjskoc646naoo5ahb7pqg7o1ajj7ymydpm4aeb92f1viitjnxmcdqz' );
define( 'LOGGED_IN_KEY',    'beciqbmaasafbkl2qjtvrpb8jdfzfxedsbx6kp5bkwn91rc06aplz8nofhaqaqyl' );
define( 'NONCE_KEY',        '6iyxvgnvzrjlj0skgptgujqhiathdwfv2vzxoufitzlmgmcelpchdlx7ui2o5hkd' );
define( 'AUTH_SALT',        'eg1ilsvhkntbn3obxeyohpnnisgsvmqus4n7yw6cpci4gqwxnznoq4w6megzwfez' );
define( 'SECURE_AUTH_SALT', 'rdm7xyqtadzj6t8dp4ga22kfaqcdnlwoyotyuxbrahcd4nvvbpgf0czfssyh6nxg' );
define( 'LOGGED_IN_SALT',   'r52mfxcz44k0jcuow9rmaxn8lthnnv4f3enk32wak2pm4gsgterxqlxny3upqgox' );
define( 'NONCE_SALT',       'prj7cm5ylyvhwblvorujw4owilmmzupdg9yzfiyjaosga3kwhy8on8hzuzkr72at' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpwn_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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