<?php
/**
 * Plugin Name: WP Profile View Logs
 * Plugin URI: https://github.com/amirrezashf/wordpress-profile-view-logs
 * Description: Track and display WordPress user profile view logs for administrators and user managers.
 * Version: 1.0.0
 * Author: Amirreza Shayesteh Far
 * Author URI: https://amirrezaa.ir/
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class WP_Profile_View_Logs {

	const META_KEY = '_wp_profile_view_logs';
	const MAX_LOGS = 50;
	const DUPLICATE_WINDOW = 60;

	public function __construct() {
		add_action( 'load-user-edit.php', array( $this, 'track_profile_view' ) );
		add_action( 'show_user_profile', array( $this, 'render_logs_box' ) );
		add_action( 'edit_user_profile', array( $this, 'render_logs_box' ) );
	}

	private function can_track() {
		return current_user_can( 'edit_users' );
	}

	public function track_profile_view() {
		if ( ! $this->can_track() ) {
			return;
		}

		$viewed_user_id = isset( $_GET['user_id'] ) ? absint( $_GET['user_id'] ) : 0;

		if ( ! $viewed_user_id ) {
			return;
		}

		$viewer = wp_get_current_user();

		if ( ! $viewer || empty( $viewer->ID ) ) {
			return;
		}

		if ( (int) $viewer->ID === (int) $viewed_user_id ) {
			return;
		}

		if ( ! current_user_can( 'edit_user', $viewed_user_id ) ) {
			return;
		}

		$viewed_user = get_userdata( $viewed_user_id );

		if ( ! $viewed_user ) {
			return;
		}

		$logs = get_user_meta( $viewed_user_id, self::META_KEY, true );

		if ( ! is_array( $logs ) ) {
			$logs = array();
		}

		$latest = ! empty( $logs[0] ) && is_array( $logs[0] ) ? $logs[0] : array();

		if (
			! empty( $latest['viewer_id'] ) &&
			(int) $latest['viewer_id'] === (int) $viewer->ID &&
			! empty( $latest['timestamp'] ) &&
			( time() - (int) $latest['timestamp'] ) < self::DUPLICATE_WINDOW
		) {
			return;
		}

		$logs = array_filter(
			$logs,
			function ( $log ) {
				return is_array( $log );
			}
		);

		array_unshift(
			$logs,
			array(
				'viewer_id'      => (int) $viewer->ID,
				'viewer_login'   => sanitize_text_field( $viewer->user_login ),
				'viewer_name'    => sanitize_text_field( $viewer->display_name ),
				'timestamp'      => time(),
				'datetime_mysql' => current_time( 'mysql' ),
				'ip'             => $this->get_ip_address(),
				'user_agent'     => $this->get_user_agent(),
			)
		);

		update_user_meta(
			$viewed_user_id,
			self::META_KEY,
			array_slice( $logs, 0, self::MAX_LOGS )
		);
	}

	private function get_ip_address() {
		$keys = array(
			'HTTP_CF_CONNECTING_IP',
			'HTTP_X_FORWARDED_FOR',
			'REMOTE_ADDR',
		);

		foreach ( $keys as $key ) {
			if ( empty( $_SERVER[ $key ] ) ) {
				continue;
			}

			$value = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );

			if ( strpos( $value, ',' ) !== false ) {
				$parts = explode( ',', $value );
				$value = trim( $parts[0] );
			}

			if ( filter_var( $value, FILTER_VALIDATE_IP ) ) {
				return $value;
			}
		}

		return '';
	}

	private function get_user_agent() {
		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return '';
		}

		$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );

		if ( strlen( $user_agent ) > 180 ) {
			$user_agent = substr( $user_agent, 0, 180 ) . '...';
		}

		return $user_agent;
	}

	private function format_datetime( $timestamp ) {
		$timestamp = absint( $timestamp );

		if ( ! $timestamp ) {
			return '—';
		}

		return wp_date(
			get_option( 'date_format' ) . ' - ' . get_option( 'time_format' ),
			$timestamp,
			wp_timezone()
		);
	}

	public function render_logs_box( $user ) {
		if ( ! $this->can_track() ) {
			return;
		}

		if ( ! $user || empty( $user->ID ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_user', $user->ID ) ) {
			return;
		}

		$logs = get_user_meta( $user->ID, self::META_KEY, true );

		if ( ! is_array( $logs ) ) {
			$logs = array();
		}

		echo '<h2>تاریخچه بازدید پروفایل</h2>';
		echo '<table class="form-table" role="presentation">';
		echo '<tr>';
		echo '<th><label>آخرین بازدیدها</label></th>';
		echo '<td>';

		echo '<div style="background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:14px;max-width:760px;">';

		if ( empty( $logs ) ) {
			echo '<p style="margin:0;color:#666;">هنوز بازدیدی برای این پروفایل ثبت نشده است.</p>';
		} else {
			echo '<ul style="margin:0;padding-right:20px;max-height:260px;overflow:auto;list-style:disc;">';

			foreach ( $logs as $log ) {
				if ( ! is_array( $log ) ) {
					continue;
				}

				$viewer_name = ! empty( $log['viewer_name'] ) ? $log['viewer_name'] : 'کاربر ناشناس';
				$viewer_id   = ! empty( $log['viewer_id'] ) ? absint( $log['viewer_id'] ) : 0;
				$time        = ! empty( $log['timestamp'] ) ? $this->format_datetime( $log['timestamp'] ) : '—';
				$ip          = ! empty( $log['ip'] ) ? $log['ip'] : '—';

				$viewer_text = $viewer_name;

				if ( $viewer_id && current_user_can( 'edit_user', $viewer_id ) ) {
					$viewer_text = '<a href="' . esc_url( get_edit_user_link( $viewer_id ) ) . '">' . esc_html( $viewer_name ) . '</a>';
				} else {
					$viewer_text = esc_html( $viewer_name );
				}

				echo '<li style="margin-bottom:8px;line-height:1.8;">';
				echo $viewer_text;
				echo ' در تاریخ <strong>' . esc_html( $time ) . '</strong> این پروفایل را مشاهده کرد.';
				echo '<br><span style="color:#777;font-size:12px;">IP: ' . esc_html( $ip ) . '</span>';
				echo '</li>';
			}

			echo '</ul>';
		}

		echo '</div>';
		echo '<p class="description">حداکثر ۵۰ بازدید آخر ذخیره می‌شود. بازدیدهای تکراری یک مدیر در کمتر از ۶۰ ثانیه ثبت نمی‌شوند.</p>';

		echo '</td>';
		echo '</tr>';
		echo '</table>';
	}
}

new WP_Profile_View_Logs();
