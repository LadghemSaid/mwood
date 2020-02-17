<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //


if ($_SERVER["HTTP_HOST"] === 'localhost') {
    $db_name = 'wp-mwood';
    $password = '';
    $user_name = 'root';
} else if ($_SERVER["HTTP_HOST"] === '34.77.181.91') {
    $db_name = 'wp-mwood';
    $password = 'Azerty123321!';
    $user_name = 'root';
}


/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

define( 'WPMS_ON', true );
define( 'WPMS_SMTP_PASS', 'Azortresor' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );


// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
define('DB_NAME', $db_name);

/** MySQL database username */
define('DB_USER', $user_name);

/** MySQL database password */
define('DB_PASSWORD', $password);

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8' );

/** Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */

define( 'AUTH_KEY',         'ks0G6ATF9K1~i:D3tX[E]r)%/ABXEFRrRdp@%#rZ?w||tIk%`tE&I),P3Cs4V03:' );
define( 'SECURE_AUTH_KEY',  'u97ZKOLf##=fO {3VIpRAGu3b;S%DY|qv`<j7we~]L!CW{0fq(.0)_A#Nn{C,l~}' );
define( 'LOGGED_IN_KEY',    'L#@SaZC}[:7lu4R3tYeUM`:^R#$tIMbT2wmF^qd|T}J<~g]ahS8ziuS&g+L}>? *' );
define( 'NONCE_KEY',        ']Sl5usX#r(ZuM_,*UI`4&Q(m@m0#xkH4PGlWNq=;B1 Za?.CC4v|%C;0NaL.~)q9' );
define( 'AUTH_SALT',        '*B%z F<!BU,7k)TB=uInENDPv|Th1[}$N-LL|6W`H|?i8t`nHC_z?l6 6)1Zn/@?' );
define( 'SECURE_AUTH_SALT', '+rvwPXjD]1,o{a=q8{7.q56N)CTz.q%N`H*bHWM%R#J]6K!Je]:l*vw%0Ucsn~Jw' );
define( 'LOGGED_IN_SALT',   't~A|v^}S(xu6]o2s/ND]7rLQ,_N}3S5=g1p-FQw!w8s|*vAi0!>fom.cU+3)z$ 2' );
define( 'NONCE_SALT',       'z;~C7 [3V0=P&p,+rFp?pm$prBcid&y06#B`fQTH<d#<x(wg%0gLW$l V%Zuus1w' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
//define( 'WP_HOME', 'http://localhost/symfony/mwood/' );
//define( 'WP_SITEURL', 'http://localhost/symfony/mwood/' );
