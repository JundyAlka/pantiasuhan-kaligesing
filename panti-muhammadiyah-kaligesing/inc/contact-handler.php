<?php
/**
 * Penanganan Formulir Kontak & Penyimpanan Database WordPress (Black Box Testing Tabel 4.1)
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Nama tabel database pesan kontak.
 */
function pmk_get_messages_table() {
	global $wpdb;
	return $wpdb->prefix . 'panti_contact_messages';
}

/**
 * Buat tabel database pesan kontak saat aktivasi tema.
 */
function pmk_create_messages_table() {
	global $wpdb;
	if ( ! isset( $wpdb ) ) {
		return;
	}
	$table_name      = pmk_get_messages_table();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		full_name varchar(160) NOT NULL,
		email varchar(160) NOT NULL,
		subject varchar(180) DEFAULT '',
		message text NOT NULL,
		ip_address varchar(64) DEFAULT '',
		created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

/**
 * Daftarkan Custom Post Type Pesan Kontak agar bisa dicek langsung dari Dashboard WP Admin.
 */
function pmk_register_message_cpt() {
	register_post_type(
		'pmk_message',
		array(
			'labels'       => array(
				'name'          => __( 'Pesan Kontak', 'panti-muhammadiyah-kaligesing' ),
				'singular_name' => __( 'Pesan Kontak', 'panti-muhammadiyah-kaligesing' ),
				'menu_name'     => __( 'Pesan Kontak KP', 'panti-muhammadiyah-kaligesing' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-email-alt',
			'supports'     => array( 'title', 'editor', 'custom-fields' ),
			'capabilities' => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap' => true,
		)
	);
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'init', 'pmk_register_message_cpt' );
}

/**
 * Proses pengiriman formulir kontak (baik di WordPress maupun di preview lokal).
 *
 * @return array{status: string, notice: string, errors: array, values: array}
 */
function pmk_process_contact_submission() {
	$result = array(
		'status' => 'idle',
		'notice' => '',
		'errors' => array(),
		'values' => array(
			'name'    => '',
			'email'   => '',
			'subject' => 'Informasi Umum Panti',
			'message' => '',
		),
	);

	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || ! isset( $_POST['pmk_contact_action'] ) ) {
		return $result;
	}

	// Verifikasi nonce WordPress bila tersedia
	if ( function_exists( 'wp_verify_nonce' ) && isset( $_POST['pmk_contact_nonce'] ) ) {
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pmk_contact_nonce'] ) ), 'pmk_submit_contact' ) ) {
			$result['status'] = 'error';
			$result['notice'] = 'Sesi formulir kedaluwarsa. Silakan muat ulang halaman dan coba kembali.';
			return $result;
		}
	}

	$raw_name    = isset( $_POST['pmk_name'] ) ? wp_unslash( $_POST['pmk_name'] ) : '';
	$raw_email   = isset( $_POST['pmk_email'] ) ? wp_unslash( $_POST['pmk_email'] ) : '';
	$raw_subject = isset( $_POST['pmk_subject'] ) ? wp_unslash( $_POST['pmk_subject'] ) : 'Informasi Umum Panti';
	$raw_message = isset( $_POST['pmk_message'] ) ? wp_unslash( $_POST['pmk_message'] ) : '';

	$name    = trim( sanitize_text_field( $raw_name ) );
	$email   = trim( sanitize_email( $raw_email ) );
	$subject = trim( sanitize_text_field( $raw_subject ) );
	$message = trim( sanitize_textarea_field( $raw_message ) );

	$result['values'] = array(
		'name'    => $name,
		'email'   => $email,
		'subject' => $subject,
		'message' => $message,
	);

	if ( '' === $name ) {
		$result['errors']['name'] = 'Nama lengkap wajib diisi.';
	}
	if ( '' === $email ) {
		$result['errors']['email'] = 'Alamat email wajib diisi.';
	} elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
		$result['errors']['email'] = 'Format alamat email tidak valid.';
	}
	if ( '' === $message ) {
		$result['errors']['message'] = 'Pesan wajib diisi.';
	}

	if ( ! empty( $result['errors'] ) ) {
		$result['status'] = 'error';
		$result['notice'] = 'Peringatan: Mohon lengkapi seluruh kolom wajib (Nama, Email, dan Pesan) sebelum mengirim formulir.';
		return $result;
	}

	$ip_address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$saved      = false;

	// Simpan ke database WordPress ($wpdb + CPT pmk_message)
	global $wpdb;
	if ( isset( $wpdb ) && method_exists( $wpdb, 'insert' ) ) {
		pmk_create_messages_table();
		$inserted = $wpdb->insert(
			pmk_get_messages_table(),
			array(
				'full_name'  => $name,
				'email'      => $email,
				'subject'    => $subject,
				'message'    => $message,
				'ip_address' => $ip_address,
				'created_at' => function_exists( 'current_time' ) ? current_time( 'mysql' ) : gmdate( 'Y-m-d H:i:s' ),
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( function_exists( 'wp_insert_post' ) ) {
			wp_insert_post(
				array(
					'post_type'    => 'pmk_message',
					'post_status'  => 'publish',
					'post_title'   => sprintf( '[%s] %s (%s)', $subject, $name, $email ),
					'post_content' => $message,
					'meta_input'   => array(
						'_pmk_sender_name'  => $name,
						'_pmk_sender_email' => $email,
						'_pmk_subject'      => $subject,
						'_pmk_ip'           => $ip_address,
					),
				)
			);
		}
		$saved = ( false !== $inserted );
	} elseif ( function_exists( 'pmk_local_save_message' ) ) {
		$saved = pmk_local_save_message(
			array(
				'full_name'  => $name,
				'email'      => $email,
				'subject'    => $subject,
				'message'    => $message,
				'ip_address' => $ip_address,
				'created_at' => gmdate( 'Y-m-d H:i:s' ),
			)
		);
	}

	if ( $saved ) {
		$result['status'] = 'success';
		$result['notice'] = 'Terima kasih, ' . esc_html( $name ) . '! Pesan Anda telah berhasil dikirim dan tercatat di database Panti Asuhan Muhammadiyah Kaligesing.';
		$result['values'] = array(
			'name'    => '',
			'email'   => '',
			'subject' => 'Informasi Umum Panti',
			'message' => '',
		);
	} else {
		$result['status'] = 'error';
		$result['notice'] = 'Maaf, terjadi gangguan saat menyimpan pesan Anda. Silakan coba lagi atau hubungi WhatsApp resmi kami.';
	}

	return $result;
}
