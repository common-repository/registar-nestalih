<?php

if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }
if ( ! defined( 'ABSPATH' ) ) { exit; }

if( !class_exists('Registar_Nestalih_Push_Notification') ) : class Registar_Nestalih_Push_Notification {
	// PRIVATE: API URL
	private $test_url = 'https://nestaliapi.delfin.rs/api';
	private $url = 'https://api.nestalisrbija.rs/api';
	
	// Run this class on the safe and protected way
	private static $instance;
	private static function instance() {
		if( !self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/*
	 * Get saved data
	 */
	public static function get() {
		return self::instance()->__get_data();
	}
	
	/*
	 * Save URL to push notifications
	 */
	public static function save_url() {
		return self::instance()->__save_url();
	}
	
	/*
	 * Delete URL from database
	 */
	public static function delete_url() {
		return self::instance()->__delete_url();
	}
	
	/*
	 * PRIVATE: Get saved data
	 */
	private function __get_data() {
		if( $get = get_option( Registar_Nestalih::TEXTDOMAIN . '-push-notification' ) ) {
			return $get;
		}
		return NULL;
	}
	
	/*
	 * PRIVATE: Save URL to push notifications
	 */
	private function __save_url() {

		if( $get = $this->__get_data() ) {
			return $get;
		}

		// Enable development mode
		if( defined('MISSING_PERSONS_DEV_MODE') && MISSING_PERSONS_DEV_MODE === true ) {
			$this->url = $this->test_url;
		}
		
		// Send POST request
		$request = wp_remote_post(
			"{$this->url}/save_url_for_ping",
			[
				'body' => [
					'url' => home_url('/rnp-notification/' . Registar_Nestalih_U::key())
				]
			]
		);

		// Get data
		if( !is_wp_error( $request ) ) {
			if($json = wp_remote_retrieve_body( $request )) {
				$get = json_decode($json);
				if($get->id ?? NULL) {
					update_option( Registar_Nestalih::TEXTDOMAIN . '-push-notification', $get, false );
					return $get;
				}
			}
		}
		
		return false;
	}
	
	/*
	 * PRIVATE: Delete URL from database
	 */
	private function __delete_url() {
		if( $get = $this->__get_data() ) {
			
			// Enable development mode
			if( defined('MISSING_PERSONS_DEV_MODE') && MISSING_PERSONS_DEV_MODE === true ) {
				$this->url = $this->test_url;
			}
			
			// Send POST request
			$request = wp_remote_post(
				"{$this->url}/delete_url_for_ping",
				[
					'body' => [
						'url' => $get->url,
						'id' => $get->id
					]
				]
			);

			// Get data
			if( !is_wp_error( $request ) ) {
				if($json = wp_remote_retrieve_body( $request )) {
					$get = json_decode($json);
					if(($get->result ?? NULL) === true) {
						delete_option( Registar_Nestalih::TEXTDOMAIN . '-push-notification' );
						return true;
					}
				}
			}
			
		}

		return false;
	}
	
} endif;