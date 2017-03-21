<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'hostallourdes');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'GwTgh>3#OJklG>CB.d^U4J;,1Mec?.oLQ&S[o=3PS}Vl#@WYn!yP`=^Bmz#hywui');
define('SECURE_AUTH_KEY', 'OXm>>BwJ5K#m+Aumxm7~&_mw_5yO3-,TI!A#y&`m&GIW#YAYI[V?2UX|oVb6Tt_@');
define('LOGGED_IN_KEY', '!!~%:<n-g88s4}lGVpNM#K%mc:VUZ3pqvxK/tc+-%#%cT2bZ[:kE@E.c/J)6]/wZ');
define('NONCE_KEY', ']saqGV4A7zo[EQtQ]>)|=-=r4yE*B0/Z+X!Q+g)!T@~5b&V-dgk(~^u~^FC;6x_;');
define('AUTH_SALT', 'Jp*GO(?:Y#sO#FR`uh%`MGG$V? +?K[^63WSL}bYlVTE^v)XBEFKWRHC/k&ZUAo+');
define('SECURE_AUTH_SALT', '2V{i.E3CLZsW C,W&k4XS03Zd~hbqmGwZ( =N`cpuQKSj} `M6LQmT@d[#_JBE(#');
define('LOGGED_IN_SALT', 'p8)TJ@x0+o~kmNeo!9PLqPWe$nFqh#PaR+8j!7$[R.~]}ndyZf|/<&%GIuDA;1hs');
define('NONCE_SALT', '$|m3vp^d+:BF/{fcRTz0QIy!Wrmh/HL=wuMyU>s~qmY|0jCwSa}A!>d%}2[KZ^ZX');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

