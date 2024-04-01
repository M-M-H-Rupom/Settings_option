<?php
/**
 * Plugin Name: Option setting 
 * Author: Rupom
 * Desciption: Plugin description
 * Version: 1.0
 */

class details_Settings_Page {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
		add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array($this,'callback_plugin_link') );
	}
	public function callback_plugin_link($links){
		$s_link = sprintf('<a href="%s">%s</a>','options-general.php?page=details','setting');
		$links[] = $s_link;
		return $links;
	}
	public function wph_create_settings() {
		$page_title = 'details';
		$menu_title = 'Details';
		$capability = 'manage_options';
		$slug = 'details';
		$callback = array($this, 'wph_settings_content');
		$icon = 'dashicons-admin-settings';
		$position = 12;
		add_options_page($page_title, $menu_title, $capability, $slug, $callback, $icon);
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon,$position);
	}
	public function wph_settings_content() { ?>
		<div class="wrap">
			<h1>Info</h1>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'details' );
					do_settings_sections( 'details' );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	public function wph_setup_sections() {
		add_settings_section( 'details_section', 'hello section', array(), 'details' );
	}

	public function wph_setup_fields() {
		$fields = array(
			array(
				'label' => 'First name',
				'id' => 'fname',
				'type' => 'text',
				'section' => 'details_section',
				'desc' => 'Hello',
				'placeholder' => 'Rupom',
			),
			array(
				'label' => 'Last name',
				'id' => 'lname',
				'type' => 'text',
				'section' => 'details_section',
				'placeholder' => 'Mohrajul',
			),
			array(
				'label' => 'One',
				'id' => 'one',
				'type' => 'checkbox',
				'section' => 'details_section',
			),
			array(
				'label' => 'Color',
				'id' => 'color',
				'type' => 'select',
				'section' => 'details_section',
				'options' => array(
					'red' => 'Red',
					'green' => 'Green',
					'black' => 'Black',
				),
			),
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'wph_field_callback' ), 'details', $field['section'], $field );
			register_setting( 'details', $field['id'] );
		}
	}

	public function wph_field_callback( $field ) {
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset($field['placeholder']) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
				case 'checkbox':
					printf('<input %s id="%s" name="%s" type="checkbox" value="1">',
						$value === '1' ? 'checked' : '',
						$field['id'],
						$field['id']
				);
				break;
				case 'select':
				case 'multiselect':
					if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
						$attr = '';
						$options = '';
						foreach( $field['options'] as $key => $label ) {
							$options.= sprintf('<option value="%s" %s>%s</option>',
								$key,
								$selected = $value == $key ? 'selected' : '',
								$label
							);
						}
						printf( '<select name="%1$s" id="%1$s" %2$s>%3$s</select>',
							$field['id'],
							$attr,
							$options
						);
					}
				break;
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}
}
new details_Settings_Page();


?>