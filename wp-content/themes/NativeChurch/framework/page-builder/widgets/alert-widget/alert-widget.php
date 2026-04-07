<?php

/*
Widget Name: Alert Box Widget
Description: A widget to add Alert Boxes to your pages.
Author: imithemes
Author URI: http://imithemes.com
*/

class Alert_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'alert-widget',
			__('Alert Widget', 'framework'),
			array(
				'description' => __('A widget to add Alert Boxes to your pages.', 'framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'content' => array(
					'type' => 'textarea',
					'label' => __('Alert Box Content', 'framework'),
					'description' => __('HTML tags are allowed in this.', 'framework'),
					
				),
				'type' => array(
					'type' => 'select',
					'state_name' => 'standard',
					'label' => __( 'Choose Color Style', 'framework' ),
					'prompt' => __( 'Choose Color Style', 'framework' ),
					'options' => array(
						'standard' => __( 'Standard', 'framework' ),
						'warning' => __( 'Warning', 'framework' ),
						'error' => __( 'Error', 'framework' ),
						'info' => __( 'Info', 'framework' ),
						'success' => __( 'Success', 'framework' ),
					)
				),
				'custom_color' => array(
					'type' => 'color',
					'label' => __('Custom background color', 'framework'),
					'default' => '',
				),
				'custom_bcolor' => array(
					'type' => 'color',
					'label' => __('Custom border color', 'framework'),
					'default' => '',
				),
				'custom_tcolor' => array(
					'type' => 'color',
					'label' => __('Custom text color', 'framework'),
					'default' => '',
				),
				'close' => array(
					'type' => 'checkbox',
					'label' => __('Show close button', 'framework'),
					'default' => true,
				),
				'animation' => array(
					'type' => 'select',
					'state_name' => 'fadeIn',
					'label' => __( 'Choose animation', 'framework' ),
					'prompt' => __( 'Choose animation', 'framework' ),
					'options' => array(
						'flash' => __( 'Flash', 'framework' ),
						'shake' => __( 'Shake', 'framework' ),
						'bounce' => __( 'Bounce', 'framework' ),
						'tada' => __( 'Tada', 'framework' ),
						'swing' => __( 'Swing', 'framework' ),
						'wobble' => __( 'Wobble', 'framework' ),
						'wiggle' => __( 'Wiggle', 'framework' ),
						'pulse' => __( 'Pulse', 'framework' ),
						'fadeIn' => __( 'FadeIn', 'framework' ),
						'fadeInUp' => __( 'FadeInUp', 'framework' ),
						'fadeInLeft' => __( 'FadeInLeft', 'framework' ),
						'fadeInRight' => __( 'FadeInRight', 'framework' ),
						'fadeInUpBig' => __( 'FadeInUpBig', 'framework' ),
						'fadeInDownBig' => __( 'FadeInDownBig', 'framework' ),
						'fadeInLeftBig' => __( 'FadeInDownBig', 'framework' ),
						'fadeInRightBig' => __( 'FadeInRightBig', 'framework' ),
						'bounceIn' => __( 'BounceIn', 'framework' ),
						'bounceInUp' => __( 'BounceInUp', 'framework' ),
						'bounceInDown' => __( 'BounceInDown', 'framework' ),
						'bounceInLeft' => __( 'BounceInLeft', 'framework' ),
						'bounceInRight' => __( 'BounceInRight', 'framework' ),
						'rotateIn' => __( 'RotateIn', 'framework' ),
						'rotateInUpLeft' => __( 'RotateInUpLeft', 'framework' ),
						'rotateInDownLeft' => __( 'RotateInDownLeft', 'framework' ),
						'rotateInUpRight' => __( 'RotateInUpRight', 'framework' ),
						'rotateInDownRight' => __( 'RotateInDownRight', 'framework' ),
					)
				),
			),
			plugin_dir_path(__FILE__)
		);
	}
	
	
	function get_template_name( $instance ) {
		return 'template';
	}


	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return array();
	}
	function modify_instance($instance){
		return $instance;
	}


}

siteorigin_widget_register('alert-widget', __FILE__, 'Alert_Widget');