<?php

namespace App\PopupBuilder\Hooks;

defined( 'ABSPATH' ) || exit;

class AnalyticsExpiry {

	public function __construct() {
		add_action( 'pbb_daily_event', array( $this, 'check_expiry' ) );
		$this->schedule_event();
	}

	public function schedule_event() {
		if ( ! wp_next_scheduled( 'pbb_daily_event' ) ) {
			wp_schedule_event( time(), 'weekly', 'pbb_daily_event' );
		}
	}

	public function check_expiry() {
		$settings = get_option( 'pbb-settings-tabs' );
		$expiry   = isset( $settings['analytics'] ) ? $settings['analytics']['value'] : 2;

		if ( $expiry === 'forever' ) {
			return;
		}

		\App\PopupBuilder\Helpers\DataBase::deleteExpiredData( $expiry );
	}
}
