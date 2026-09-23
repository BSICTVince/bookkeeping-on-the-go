<?php
/**
 * One-click migration of the site's hand-rolled forms (Contact, 7 Steps
 * signup, Newsletter) onto the Forms engine, so every submission gets an
 * entry in wp-admin in addition to the existing wp_mail() notification.
 * Definitions live in content/forms.json; bootg_import_forms() (Weavit
 * Engine plugin) creates them idempotently by slug — safe to run again.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Ensures the system forms exist (idempotent, checked once per request). */
function bootg_ensure_system_forms() {
	static $done = false;
	if ( $done ) {
		return;
	}
	bootg_import_forms( bootg_load_json( BOOTG_DIR . '/content/forms.json' ) );
	$done = true;
}

function bootg_get_contact_form_id() {
	bootg_ensure_system_forms();
	return bootg_get_system_form_id( 'contact-form' );
}

function bootg_get_7_steps_form_id() {
	bootg_ensure_system_forms();
	return bootg_get_system_form_id( '7-steps-signup' );
}

function bootg_get_newsletter_form_id() {
	bootg_ensure_system_forms();
	return bootg_get_system_form_id( 'newsletter-signup' );
}
