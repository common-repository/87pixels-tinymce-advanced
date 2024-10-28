<?php

/**
 * Plugin Name: Full TinyMCE WordPress Editor
 * Plugin URI: http://87pixels.co.za/full-tinymce-wordpress-editor
 * Version: 1.3.1
 * Author: 87Pixels
 * Author URI: http://www.87pixels.co.za
 * Description: Extends and enhances the default WordPress editor
 * License: GPL2
 */
 
 
/**
 * Full TinyMCE WordPress Editor is free software: you can redistribute it 
 * and/or modify it under the terms of the GNU General Public License as 
 * published by the Free Software Foundation, either version 2 of the 
 * License, or any later version.
	 
 * Full TinyMCE WordPress Editor is distributed in the hope that it will be 
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
	 
 * You should have received a copy of the GNU General Public License
 * along with Full TinyMCE WordPress Editor.
 */

if ( !class_exists( 'Full_TinyMCE_Editor_Class' ) ) {

	class Full_TinyMCE_Editor_Class {
		
		public function Full_TinyMCE_Editor_check_version() {
			if ( Full_TinyMCE_Editor_VERSION !== get_option('full_tinymce_editor_version'))
    		$this->update();
		}
		
		/**
		 * __construct
		 * class constructor will set the needed filter and action hooks
		 */
		public function __construct() {
			if ( ! defined( 'Full_TinyMCE_Editor_VERSION' ) ) { define('Full_TinyMCE_Editor_VERSION', '1.3.1'); }
			if ( ! defined( 'FULL_TMCE_URL' ) ) { define( 'FULL_TMCE_URL', plugin_dir_url( __FILE__ ) ); }
			if ( ! defined( 'FULL_TMCE_PATH' ) ) { define( 'FULL_TMCE_PATH', plugin_dir_path( __FILE__ ) ); }
			
			
			if ( is_admin() ) {
				add_action( 'plugins_loaded', array( $this, 'Full_TinyMCE_Editor_check_version' ) );
				add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_settings_link' ) );
				add_action( 'admin_menu', array( $this, 'settings_submenu' ) );
				add_action( 'admin_head', array( $this, 'admin_head') );
				add_action( 'admin_enqueue_scripts', array( $this , 'admin_enqueue_scripts' ) );
				add_action( 'admin_init', array( $this, 'editor_option_settings' ) );
				add_action( 'admin_init', array( $this, 'settings_update' ) );
				register_activation_hook( ( __FILE__ ), array( $this, 'activate' ));
				register_deactivation_hook( ( __FILE__ ), array( $this, 'deactivate' ));
			}
		}
		
		
		
		/**
		 * shortcode_handler
		 * @param  array  $atts shortcode attributes
		 * @param  string $content shortcode content
		 * @return string
		 */
		public function shortcode_handler($atts , $content = null) {
		}
		
		
		
		/**
		 * admin_head
		 * calls your functions into the correct filters
		 * @return [type] [description]
		 */
		public function admin_head() {
			// check user permissions
			if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
				return;
			}
			
			// check if WYSIWYG is enabled
			if ( 'true' == get_user_option( 'rich_editing' ) ) {
				add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
				add_filter( 'tiny_mce_before_init', array( $this, 'tinymce_btns' ) );
			}
		}
		
		
		
		/**
		 * Default settings
		 */
		private function defaults() {
			return array(
				'options' => array( 'menubar' => 0, 'advlist' => 0, 'contextmenu' => 0, 'link' => 0 ),
				
				'plugins' => array( 
					'advlist' 			=> 0,
					'contextmenu' 		=> 0,
					'link' 				=> 1,
					'anchor'				=> 0,
					'code'				=> 0,
					'emoticons'			=> 0,
					'insertdatetime'	=> 0,
					'nonbreaking'		=> 0,
					'print'				=> 0,
					'searchreplace'	=> 0,
					'table'				=> 0,
					'visualblocks'		=> 0,
					'visualchars'		=> 0,
					'textcolor'			=> 1
				),
				
				'toolbar' => array(
					'formatselect' 	=> 1,
					'fontselect'		=> 0,
					'fontsizeselect'	=> 0,
					'bold' 				=> 1,
					'italic' 			=> 1,
					'underline' 		=> 0,
					'bullist' 			=> 1,
					'numlist' 			=> 1,
					'blockquote' 		=> 1,
					'alignleft' 		=> 1,
					'aligncenter' 		=> 1,
					'alignright' 		=> 1,
					'alignjustify' 	=> 0,
					'alignnone' 		=> 0,
					'link' 				=> 1,
					'unlink' 			=> 1,
					'wp_more'			=> 1,
					'wp_adv'				=> 1,
					'strikethrough'	=> 1,
					'hr'					=> 1,
					'forecolor'			=> 1,
					'backcolor'			=> 0,
					'pastetext'			=> 1,
					'removeformat'		=> 1,
					'charmap'			=> 1,
					'outdent'			=> 1,
					'indent'				=> 1,
					'undo'				=> 1,
					'redo'				=> 1,
					'wp_help'			=> 1,
					'anchor'				=> 0,
					'code'				=> 0,
					'emoticons'			=> 0,
					'insertdatetime'	=> 0,
					'nonbreaking'		=> 0,
					'print'				=> 0,
					'searchreplace'	=> 0,
					'table'				=> 0,
					'visualblocks'		=> 0,
					'visualchars'		=> 0,
					'subscript'			=> 0,
					'superscript'		=> 0,
					'wp_page'			=> 0,
					'ltr'					=> 0,
					'rtl'					=> 0
				),
				
				'naming' => array(
					'bullist' 			=> 'Bulleted List',
					'numlist' 			=> 'Numbered List',
					'formatselect' 	=> 'Paragraph',
					'fontselect'		=> 'Font Family',
					'fontsizeselect'	=> 'Font Size',
					'bold' 				=> 'Bold',
					'italic' 			=> 'Italic',
					'underline' 		=> 'Underline',
					'blockquote' 		=> 'Blockquote',
					'alignleft' 		=> 'Align Left',
					'aligncenter' 		=> 'Align Center',
					'alignright' 		=> 'Align Right',
					'alignjustify' 	=> 'Justify',
					'alignnone' 		=> 'Align None',
					'link' 				=> 'Link',
					'unlink' 			=> 'Unlink',
					'wp_more'			=> 'Read More',
					'wp_adv'				=> 'Toolbar Toggle',
					'strikethrough'	=> 'Strikethrough',
					'hr'					=> 'Horizontal Line',
					'forecolor'			=> 'Text Colour',
					'backcolor'			=> 'Background Colour',
					'pastetext'			=> 'Paste as Text',
					'removeformat'		=> 'Clear Formatting',
					'charmap'			=> 'Special Character',
					'outdent'			=> 'Decrease Indent',
					'indent'				=> 'Increase Indent',
					'undo'				=> 'Undo',
					'redo'				=> 'Redo',
					'wp_help'			=> 'Keyboard Shortcuts',
					'anchor'				=> 'Anchor',
					'code'				=> 'Source Code',
					'emoticons'			=> 'Emoticons',
					'insertdatetime'	=> 'Insert Date and Time',
					'nonbreaking'		=> 'Nonbreaking Space',
					'print'				=> 'Print',
					'searchreplace'	=> 'Find and Replace',
					'table'				=> 'Table',
					'visualblocks'		=> 'Show Blocks',
					'visualchars'		=> 'See invisible characters',
					'subscript'			=> 'Subscript',
					'superscript'		=> 'Superscript',
					'wp_page'			=> 'Page Break',
					'ltr'					=> 'Left to Right',
					'rtl'					=> 'Right to Left'
				)
			);
		}
		
		
		
		/**
		 * Adds our tinymce plugins
		 */
		public function mce_external_plugins() {
			$options = get_option( 'full_tmce_options' );
			$plugins = $options['plugins'] ? $options['plugins'] : '';
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			
			foreach ( $plugins as $plugin_load => $set ) {
				if ( $set === 1 ) {
					$plugin_array["$plugin_load"] = FULL_TMCE_URL . 'plugins/' . $plugin_load . "/plugin{$suffix}.js";
				}
			}
			
			return $plugin_array;
		}
		
		
		
		/**
		 * Get active buttons 
		 */
		public function get_buttons() {
			$options = get_option( 'full_tmce_options' );
			$toolbar = $options['toolbar'];
			
			foreach ( $toolbar as $toolbar_btn => $set ) {
				if ( $set === 1 ) {
					$toolbar_set_btn[] = $toolbar_btn;
				}
			}
			
			return $toolbar_set_btn = ! empty( $toolbar_set_btn ) ? $toolbar_set_btn : '';
			
		}
		
		
		
		/**
		 * Adds our tinymce buttons
		 */
		public function tinymce_btns( $in ) {
			$options = get_option( 'full_tmce_options' );
			
			$in['plugins'] = ! empty ( $this->mce_external_plugins() ) ? implode( ' ', $this->mce_external_plugins() ) : '';
			$in['plugins'] = 'lists,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,hr,textcolor,paste,charmap,directionality';
			
			$in['menubar'] = $options['options']['menubar'] == '1' ? true : false;
			$menuitems = 'edit insert view format table tools';
			$in['menubar'] = $in['menubar'] == true ? $menuitems : '';
			
			$toolbars = array_chunk( $this->get_buttons(), ceil( count( $this->get_buttons() ) / 2 ) );
			$in['toolbar1'] = ! empty ( $this->get_buttons() ) ? implode( ' ', $toolbars[0] ) : 0;
			$in['toolbar2'] = ! empty ( $this->get_buttons() ) ? implode( ' ', $toolbars[1] ) : 0;
			
			$in['toolbar3'] = '';
			$in['toolbar4'] = '';
			
			$in['wordpress_adv_hidden'] = FALSE;
			$in['skin'] = 'lightgray';
			
			$in['verify_html'] = FALSE;
			
			return $in;
		}
		
		
		
		/**
		 * Used to enqueue custom styles
		 */
		public function admin_enqueue_scripts(){
			wp_enqueue_style( 'tmce-style', get_option('siteurl') . '/wp-includes/css/editor.min.css' );
			wp_enqueue_style( 'tmce-skin', includes_url( 'js/tinymce/skins/lightgray/skin.min.css' ) );
			wp_enqueue_style( 'custom-style', plugins_url( 'css/style.css', __FILE__ ) );
		}
		
		
		
		/**
		 * Add settings link on plugin page
		 */
		public function plugin_settings_link( $links ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=full-tmce-settings' ) . '">Settings</a>';
			return $links;
		}
		
		
		
		/**
		 * Add submenu to WordPress menu under Settings
		 */
		public function settings_submenu() {
			add_submenu_page (
				'options-general.php',					// Register this submenu with the parent menu
				'Full TinyMCE Editor Settings',		// The text to the display in the browser when this menu item is active
				'Full TinyMCE Editor Settings',		// The text for this menu item
				'administrator',							// Which type of users can see this menu
				'full-tmce-settings',					// The unique ID - the slug - for this menu item
				array ( $this, 'settings_page' )		// The function used to render the menu for this page to the screen
			);
		}
		
		
		
		/**
		 * Include settings page view 
		 */
		public function settings_page() {
			if ( !current_user_can( 'manage_options' ) ) { return; }
			include_once( FULL_TMCE_PATH . 'admin.php' );
			
			wp_enqueue_style( 'bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css' );
			wp_enqueue_style( 'font-awesome', FULL_TMCE_URL . 'css/font-awesome-4.7.0/css/font-awesome.min.css' );
		}
		
		
		
		/**
		 * Editor option settings 
		 */
		public function editor_option_settings() {
			// Register a section. This is necessary since all future options must belong to one.
			add_settings_section(
				'full_tmce_options',		// ID used to identify this section and with which to register options
				'<h4>Options</h4>',		// Title to be displayed on the administration page
				'',							// Callback used to render the description of the section
				'full-tmce-settings'		// Page on which to add this section of options
			);
			
			
					// Fields for toggling visibility of content elements.
					add_settings_field( 
						'menubar',									// ID used to identify the field throughout the theme
						'Menubar',									// The label to the left of the option interface element
						array( $this, 'options_html'),		// The name of the function responsible for rendering the option interface
						'full-tmce-settings',					// The page on which this option will be displayed
						'full_tmce_options',						// The name of the section to which this field belongs
						array(										// The array of arguments to pass to the callback. In this case, just a description.
							'Enable the editor menubar.',
							'menubar'
						) 
					);
					
					
					add_settings_field( 
						'advlist',
						'Advanced List Style Options',
						array( $this, 'options_html'),
						'full-tmce-settings',
						'full_tmce_options',
						array(
							'Enable more list style options such as upper or lower case letters for ordered lists, and disk or square for unordered lists. <br /><small><i>NOTE : Bulleted and/or Numbered lists must be enabled for this option to work.</i></small>',
							'advlist'
						)
					);
					
					
					add_settings_field( 
						'contextmenu',
						'Context Menu',
						array( $this, 'options_html'),
						'full-tmce-settings',
						'full_tmce_options',
						array(
							'Replace the browser context (right-click) menu. <br /><small><i>NOTE : Must have one of the following enabled to work: <br />Link, Table.</i></small>' ,
							'contextmenu'
						)
					);
					
					
					add_settings_field( 
						'link',
						'Alternative Link Dialog Popup',
						array( $this, 'options_html'),
						'full-tmce-settings',
						'full_tmce_options',
						array(
							'Open the TinyMCE link dialog popup when using the link button on the toolbar or the link menu item.',
							'link' 
						)
					);
		}
		
		
		
		/**
		 * Editor option settings display html
		 */
		public function options_html( $args ) {
			// ID and name attribute of element should match that of ID in call to add_settings_field
			// Take first argument of array and add it to a label next to the checkbox
			$settings = get_option('full_tmce_options');
			$setting_name = $settings['options'];
			
			if ( array_key_exists( $args[1], $setting_name ) ) {
				$set = $setting_name[$args[1]];
			}
			
			$html ='
			<div class="checkbox">
				<label for="full_tmce_options[options][' . $args[1] . ']">
					<input type="checkbox" class="' . $args[1] . '" id="full_tmce_options[options][' . $args[1] . ']" 
						name="full_tmce_options[options][' . $args[1] . ']" value="1" ' . checked(1, $set, false) . '/>
					<span class="cr"><i class="cr-icon fa fa-check"></i></span>
					'  . $args[0] . '
				</label>
			</div>';
			
			echo $html;
		}
		
		
		
		/**
		 * Register editor settings
		 */
		public function settings_update() {
			register_setting( 'full-tmce-settings', 'full_tmce_options', array($this, 'validate_settings'));
		}
		
		
		
		/**
		 * Validate editor settings
		 */
		public function validate_settings( $input ) {
			$settings = get_option('full_tmce_options');
			$setting_name = $settings;
			
			$valid = array();
			
			foreach ( $setting_name as $setting_key => $setting_value ) {
				if ( $setting_key != 'naming' ) {
					foreach ( $setting_name[$setting_key] as $set_key => $set_value ) {
						$valid[$setting_key][$set_key] = 0;
						
						foreach ( $input as $input_key => $input_value ) {
							if ( $input_key != 'naming' ) {									
								foreach ( $input[$input_key] as $key => $value ) {
									if ($set_key == $key) {
										$valid[$setting_key][$key] = 1;
									}
								}
							} else {
								foreach ( $input[$input_key] as $naming_input_key => $naming_input_value ) {
									$valid[$input_key][$naming_input_key] = $naming_input_value;
								}
							}
						}
					}
				} else {
					foreach ( $setting_name[$setting_key] as $naming_key => $naming_value ) {
						$valid[$setting_key][$naming_key] = $naming_value;
					}
				}
			}
			return $valid;
		}
		
		
		
		/**
		 * Save and delete default settings in database
		 */
		public function activate() {
			update_option( 'full_tmce_options', $this->defaults() );
			update_option( 'full_tinymce_editor_version', Full_TinyMCE_Editor_VERSION );
		}
		
		
		public function update() {
			if ( get_option( 'full_tmce_options' ) === false ) {
				update_option( 'full_tinymce_editor_version', Full_TinyMCE_Editor_VERSION );
				
				$option = get_option('full_tmce_options');
				$option_to_store = array_merge( $this->defaults(), $option );
				
				update_option( 'full_tmce_options', $option_to_store );
			}
		}
		
		
		public function deactivate() {
			delete_option( 'full_tmce_options' );
		}
		
		
		
		
	}
		
	$full_tmce_editor_class = new Full_TinyMCE_Editor_Class;
    
}
?>