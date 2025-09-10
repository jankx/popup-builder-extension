<?php

namespace App\PopupBuilder\Routes;

defined('ABSPATH') || exit;

class Onboard extends Api {
	private const EMAIL = 'popupkit_onboard_email';
	private const EMAIL_ID = 'popupkit_onboard_email_id';
	private const STATUS = 'popupkit_onboard_status';
	private const URL = 'https://api.wpmet.com/public/plugin-subscribe/';

	protected function get_routes(): array {
		return [
			[
				'endpoint'            => '/onboard',
				'methods'             => "GET",
				'callback'            => 'get_onboard',
				'permission_callback' => '__return_true'
			],
			[
				'endpoint'            => '/onboard',
				'methods'             => "POST",
				'callback'            => 'post_onboard',
				'permission_callback' => '__return_true'
			],
		];
	}

	public function get_onboard($request)
	{

		$status = get_option(Onboard::STATUS);
		$email = get_option(Onboard::EMAIL);

		return array(
			'status'    => 'success',
			'onboard'      => array(
				'status' => $status,
				'email' => $email,
			),
			'message'   => array(
				'Onboard data has been fetched successfully.',
			),
		);
	}

	public function post_onboard($request)
	{
		$data    = $request->get_params();

		update_option(Onboard::STATUS, 'onboarded');

		if (!empty($data['userMail']) && !empty(is_email($data['userMail']))) {
			$args = [
				'email'           => $data['userMail'],
				'slug'             => 'popupkit',
			];
			$response = wp_remote_post(
				Onboard::URL,
				[
					'method'      => 'POST',
					'data_format' => 'body',
					'headers'     => [
						'Content-Type' => 'application/json',
					],
					'body'        => wp_json_encode($args),
				]
			);

			if (is_wp_error($response)) {
				return [
					'status'  => 'error',
					'message' => __('Failed to send onboard data.', 'popup-builder-block')
				];
			}
			$body = wp_remote_retrieve_body($response);
			$response_data = json_decode($body, true);

			update_option(Onboard::EMAIL, 'subscribed');
			update_option(Onboard::EMAIL_ID, $response_data['response']['data']['id'] ?? '');

			return [
				'status'  => 'success',
				'data'    => $response_data,
				'message' => __('Onboard data saved successfully.', 'popup-builder-block')
			];
		}


		return [
			'status'  => 'success',
			'message' => __('Onboard data saved successfully.', 'popup-builder-block')
		];
	}
}
