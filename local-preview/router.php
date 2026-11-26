<?php
/**
 * Local WordPress Theme Preview Router (PHP Built-in Server)
 * Memungkinkan preview seluruh halaman tema secara langsung di http://127.0.0.1:8088
 * sebelum di-upload ke WordPress live.
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'PMK_LOCAL_PREVIEW', true );

$theme_dir   = realpath( __DIR__ . '/../panti-muhammadiyah-kaligesing' );
$storage_dir = __DIR__;
$db_file     = $storage_dir . '/messages.json';

$request_path = parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
$clean_path   = '/' . trim( (string) $request_path, '/' );
if ( '//' === $clean_path ) {
	$clean_path = '/';
}

// Serve favicon.ico using the official Muhammadiyah Panti logo
if ( '/favicon.ico' === $clean_path ) {
	$fav = $theme_dir . '/assets/images/photos/logo-panti.png';
	if ( is_file( $fav ) ) {
		header( 'Content-Type: image/png' );
		header( 'Content-Length: ' . filesize( $fav ) );
		header( 'Cache-Control: public, max-age=3600' );
		readfile( $fav );
		exit;
	}
}

// Serve static files inside theme assets
if ( preg_match( '#^/wp-content/themes/panti-muhammadiyah-kaligesing/(.+)$#', $request_path, $m ) ) {
	$target = realpath( $theme_dir . '/' . $m[1] );
	if ( $target && 0 === strpos( $target, $theme_dir ) && is_file( $target ) ) {
		$ext = strtolower( pathinfo( $target, PATHINFO_EXTENSION ) );
		$mime = array(
			'css'  => 'text/css; charset=utf-8',
			'js'   => 'application/javascript; charset=utf-8',
			'svg'  => 'image/svg+xml',
			'png'  => 'image/png',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'webp' => 'image/webp',
		);
		header( 'Content-Type: ' . ( $mime[ $ext ] ?? 'application/octet-stream' ) );
		header( 'Content-Length: ' . filesize( $target ) );
		header( 'Cache-Control: public, max-age=3600' );
		readfile( $target );
		exit;
	}
}

// WordPress Compatibility Shims
function pmk_current_slug() {
	$p = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
	return $p;
}

function get_template_directory() {
	global $theme_dir;
	return $theme_dir;
}

function get_template_directory_uri() {
	return '/wp-content/themes/panti-muhammadiyah-kaligesing';
}

function get_stylesheet_uri() {
	return get_template_directory_uri() . '/style.css';
}

function home_url( $path = '' ) {
	if ( '' === $path || '/' === $path ) {
		return '/';
	}
	return '/' . ltrim( $path, '/' );
}

function get_theme_mod( $key, $default = '' ) {
	return $default;
}

function esc_html( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
}

function esc_attr( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
}

function esc_url( $url ) {
	return htmlspecialchars( (string) $url, ENT_QUOTES, 'UTF-8' );
}

function esc_url_raw( $url ) {
	return (string) $url;
}

function esc_textarea( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
}

function sanitize_text_field( $str ) {
	return trim( strip_tags( (string) $str ) );
}

function sanitize_textarea_field( $str ) {
	return trim( strip_tags( (string) $str ) );
}

function sanitize_email( $email ) {
	return trim( filter_var( (string) $email, FILTER_SANITIZE_EMAIL ) );
}

function wp_unslash( $value ) {
	return is_string( $value ) ? stripslashes( $value ) : $value;
}

function absint( $val ) {
	return abs( (int) $val );
}

function __( $text, $domain = '' ) {
	return $text;
}

function add_theme_support( $feature, $args = array() ) {}
function register_nav_menus( $locations = array() ) {}
function wp_enqueue_style() {}
function wp_enqueue_script() {}
function has_custom_logo() { return false; }
function has_nav_menu() { return false; }
function is_front_page() { return '' === pmk_current_slug(); }
function is_page( $slug = '' ) { return pmk_current_slug() === trim( (string) $slug, '/' ); }

function language_attributes() {
	echo 'lang="id-ID"';
}

function bloginfo( $show = '' ) {
	if ( 'charset' === $show ) {
		echo 'UTF-8';
	} elseif ( 'description' === $show ) {
		echo 'Panti Asuhan Yatim & Dhuafa Muhammadiyah Kaligesing - Purworejo';
	} else {
		echo 'Panti Asuhan Muhammadiyah Kaligesing';
	}
}

function body_class( $extra = '' ) {
	echo 'class="pmk-local-preview ' . esc_attr( $extra ) . '"';
}

function wp_head() {
	global $theme_dir;
	$titles = array(
		''           => 'Home - Panti Asuhan Muhammadiyah Kaligesing',
		'about'      => 'About - Panti Asuhan Muhammadiyah Kaligesing',
		'facilities' => 'Facilities - Panti Asuhan Muhammadiyah Kaligesing',
		'contact'    => 'Contact - Panti Asuhan Muhammadiyah Kaligesing',
	);
	$slug    = pmk_current_slug();
	$title   = $titles[ $slug ] ?? 'Panti Asuhan Muhammadiyah Kaligesing';
	$css_ver = is_file( $theme_dir . '/assets/css/main.css' ) ? filemtime( $theme_dir . '/assets/css/main.css' ) : time();
	echo '<title>' . esc_html( $title ) . '</title>' . "\n";
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">' . "\n";
	echo '<link rel="stylesheet" href="' . esc_url( get_template_directory_uri() . '/assets/css/main.css?ver=' . $css_ver ) . '">' . "\n";
}

function wp_footer() {
	global $theme_dir;
	$js_ver = is_file( $theme_dir . '/assets/js/main.js' ) ? filemtime( $theme_dir . '/assets/js/main.js' ) : time();
	echo '<script src="' . esc_url( get_template_directory_uri() . '/assets/js/main.js?ver=' . $js_ver ) . '"></script>' . "\n";
}

function wp_body_open() {}

function get_header() {
	include get_template_directory() . '/header.php';
}

function get_footer() {
	include get_template_directory() . '/footer.php';
}

function pmk_local_save_message( $record ) {
	global $db_file;
	$items = array();
	if ( is_file( $db_file ) ) {
		$decoded = json_decode( (string) file_get_contents( $db_file ), true );
		if ( is_array( $decoded ) ) {
			$items = $decoded;
		}
	}
	$record['id'] = count( $items ) + 1;
	$items[]      = $record;
	return false !== file_put_contents( $db_file, json_encode( $items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );
}

require_once $theme_dir . '/functions.php';

// Route Dispatch
if ( '/admin-messages' === $clean_path ) {
	header( 'Content-Type: application/json; charset=utf-8' );
	echo is_file( $db_file ) ? file_get_contents( $db_file ) : '[]';
	exit;
}

$routes = array(
	'/'           => 'front-page.php',
	'/home'       => 'front-page.php',
	'/about'      => 'page-about.php',
	'/facilities' => 'page-facilities.php',
	'/contact'    => 'page-contact.php',
);

if ( isset( $routes[ $clean_path ] ) ) {
	ob_start();
	include $theme_dir . '/' . $routes[ $clean_path ];
	$html_out = ob_get_clean();
	header( 'Content-Type: text/html; charset=UTF-8' );
	header( 'Content-Length: ' . strlen( $html_out ) );
	echo $html_out;
	exit;
}

http_response_code( 404 );
get_header();
?>
<section class="pmk-section">
	<div class="pmk-container">
		<div class="pmk-info-card">
			<h1>404 &mdash; Halaman Tidak Ditemukan</h1>
			<p>Gunakan 4 menu navigasi utama: Home, About, Facilities, atau Contact.</p>
			<a class="pmk-btn pmk-btn--primary" href="/">Kembali ke Home</a>
		</div>
	</div>
</section>
<?php
get_footer();
