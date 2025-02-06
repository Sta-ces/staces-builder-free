<?php
/**
 * Customizer Class
 * Developer : Cedric Staces
 * uri : https://staces.be/
 */
class STHCustomizer
{
	/**
     * Creates a new control in WordPress customizer
     * 
     * @param WP_Customize_Manager $wp Customization manager instance
     * @param string $name Unique control identifier
     * @param array $args Configuration arguments
     *      @type string $label Control label (required)
     *      @type string $section Control section (required)
     *      @type mixed $default Default value
     *      @type string $transport Refresh method ('refresh' or 'postMessage')
     *      @type string $type Control type ('text', 'color', 'image', etc.)
     *      @type array $section_args Optional section arguments
     * @return boolean True if control was created, False otherwise
     */
    function __construct($wp, $name, $args){
        if(!isset($args['label']) || !isset($args['section'])) return false;
        $args = array_merge([
            'default' => '',
            'transport' => 'refresh',
            'type' => 'text',
            'settings' => $name
        ], $args);
		if(!isset($wp->sections()[$args['section']])){
			$section_args = array_merge([
				'title'	=> _st(ucfirst($args['section'])),
				'priority'	=> 150
			], (isset($args['section_args']) ? $args['section_args'] : []));
			$wp->add_section($args['section'], $section_args);
		}
		$wp->add_setting(
			$name,
			array(
				'default'		=> $args['default'],
				'transport'		=> $args['transport']
			)
		);
		switch ($args['type']) {
			case 'color': case 'colors':
				$wp->add_control(new WP_Customize_Color_Control( $wp, $name, $args ));
				break;
			case 'image': case 'images': case 'media':
				$args['mime_type'] = 'image';
				$wp->add_control(new WP_Customize_Media_Control( $wp, $name, $args ));
				break;
			default: $wp->add_control($name, $args); break;
		}
        return true;
	}
}
function stth_customize_register( $wp_customize ) {
	require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	// REMOVE CONTROLS
	$wp_customize->remove_section('static_front_page');
	$wp_customize->remove_section('colors');
	$wp_customize->remove_control('header_text');
	
	// CUSTOMIZERS
	// TITLE_TAGLINE
	new STHCustomizer($wp_customize, 'website_description', [ 'label' => _st('Site description'), 'description' => _st('Try to be concise in your description. (Recommended maximum 160 characters)'), 'type' => 'textarea', 'section' => 'title_tagline', 'input_attrs' => [ 'maxlength' => 160 ] ]);
	// COMPANY
	new STHCustomizer($wp_customize, 'address_google_maps', [ 'label' => _st('Headquarter'), 'section' => 'company' ]);
	new STHCustomizer($wp_customize, 'tva_company', [ 'label' => _st('VAT'), 'section' => 'company' ]);
	// COOKIES
	new STHCustomizer($wp_customize, 'cookieconsent_title', [ 'label' => _st('Title'), 'section' => 'cookies' ]);
	new STHCustomizer($wp_customize, 'cookieconsent_description', [ 'label' => _st('Description'), 'section' => 'cookies', "type" => "textarea" ]);
	new STHCustomizer($wp_customize, 'cookieconsent_cookieusage', [ 'label' => _st('Cookie usage'), 'section' => 'cookies', 'type' => 'textarea' ]);
	new STHCustomizer($wp_customize, 'cookieconsent_necessarycookies', [ 'label' => _st('Strictly Necessary Cookies'), 'section' => 'cookies', 'type' => 'textarea' ]);
	new STHCustomizer($wp_customize, 'cookieconsent_analyticscookies', [ 'label' => _st('Analytics Cookies'), 'section' => 'cookies', 'type' => 'textarea' ]);
	new STHCustomizer($wp_customize, 'cookieconsent_acceptallbtn', [ 'label' => _st('Accept all button'), 'section' => 'cookies', 'description' => _st('Default: `Accept all`') ]);
	new STHCustomizer($wp_customize, 'cookieconsent_acceptnecessarybtn', [ 'label' => _st('Accept necessary button'), 'section' => 'cookies', 'description' => _st('Default: `Reject all`') ]);
	new STHCustomizer($wp_customize, 'cookieconsent_showpreferencesbtn', [ 'label' => _st('Show preferences button'), 'section' => 'cookies', 'description' => _st('Default: `Manage preferences`') ]);
	new STHCustomizer($wp_customize, 'cookieconsent_savepreferencesbtn', [ 'label' => _st('Save preferences button'), 'section' => 'cookies', 'description' => _st('Default: `Save preferences`') ]);
	new STHCustomizer($wp_customize, 'cookieconsent_privacypolicy', [ 'label' => _st('Privacy policy page'), 'section' => 'cookies', 'type' => 'dropdown-pages' ]);
	new STHCustomizer($wp_customize, 'cookieconsent_termsandconditions', [ 'label' => _st('Terms and conditions page'), 'section' => 'cookies', 'type' => 'dropdown-pages' ]);
	// SETTINGS
	if(is_plugin_active('fluentform/fluentform.php')) new STHCustomizer($wp_customize, 'connexion_form', [ 'label' => _st('Select the login form'), 'section' => 'settings', 'description' => _st('Form to be used as login form'), 'type' => 'select', 'choices' => get_all_forms() ]);
    new STHCustomizer($wp_customize, 'is_close_website', [ 'label' => _st('Close the site temporarily'), 'section' => 'settings', 'type' => 'checkbox', 'priority' => 300 ]);
    new STHCustomizer($wp_customize, 'close_page', [ 'label' => _st('Maintenance page'), 'section' => 'settings', 'description' => _st('Select the maintenance page that will be displayed when maintaining your site.'), 'type' => 'dropdown-pages' ]);
	if(function_exists("stth_customizer")) stth_customizer($wp_customize);
}
add_action( 'customize_register', 'stth_customize_register' );