<?php
function table_infoth(){ return ["sitename", "description", "slogan", "copyright", "address", "tva"]; }
function cookies_infoth(){ return [
	'title' => get_infoth('cookieconsent_title'),
	'description' => get_infoth('cookieconsent_description'),
	'cookieusage' => get_infoth('cookieconsent_cookieusage'),
	'necessarycookies' => get_infoth('cookieconsent_necessarycookies'),
	'analyticscookies' => get_infoth('cookieconsent_analyticscookies'),
	'acceptallbtn' => get_infoth('cookieconsent_acceptallbtn'),
	'acceptnecessarybtn' => get_infoth('cookieconsent_acceptnecessarybtn'),
	'showpreferencesbtn' => get_infoth('cookieconsent_showpreferencesbtn'),
	'savepreferencesbtn' => get_infoth('cookieconsent_savepreferencesbtn'),
	'privacypolicy' => get_infoth('cookieconsent_privacypolicy'),
	'termsandconditions' => get_infoth('cookieconsent_termsandconditions'),
	'mail' => get_infoth('admin-email')
]; }
function get_gutenberg_blocks(){
	return [
		"sitename" => sanitize_title(get_infoth('sitename')),
		"infosth" => convert_array(table_infoth()),
		"post" => get_post(),
		"meta" => get_metadata("post", get_the_ID())
	];
}
function infoth($show = ''){ echo get_infoth($show); }
function get_infoth($show = ''){
	switch(strtolower($show)){
		case 'is-close': $output = boolval(get_theme_mod('is_close_website')); break;
			
		case 'close-page': $output = intval(get_theme_mod('close_page')); break;
		case 'page-content':
			if(get_infoth('is-close')) $output = apply_filters('the_content', ((get_theme_mod('close_page')) ? get_the_content(null, false, get_theme_mod('close_page')) : get_the_content()));
			else $output = apply_filters('the_content', get_the_content());
			break;
		case 'logo': $output = get_custom_logo(); break;
		case 'logo-url':
			$logo = get_theme_mod("custom_logo");
			$image = wp_get_attachment_image_src( $logo , 'full' );
			$output = $image[0];
			break;
		case 'home-url': $output = get_home_url(); break;
		case 'admin-email': $output = get_option("admin_email"); break;
		case 'sitename': $output = get_bloginfo("name"); break;
		case 'slogan': $output = get_bloginfo("description"); break;
		case 'description': $output = get_theme_mod("website_description"); break;
		case 'date-format': $output = get_option('date_format'); break;
		case 'hour-format':
		case 'time-format': $output = get_option('time_format'); break;
		case 'full-date-format': $output = get_option('date_format')." ".get_option('time_format'); break;
		case 'is-commentary': $output = !boolval(get_option('active-commentary')); break;
		case 'version': $output = wp_get_theme()->get("Version"); break;
		
		case 'google-maps-key':
		case 'api-key-google-maps': $output = get_option('google-map-api'); break;
		case 'headquarter':
		case 'address':
		case 'address-google-maps': $output = get_theme_mod('address_google_maps', ''); break;
		case 'vat':
		case 'tva': $output = get_theme_mod('tva_company'); break;
		case 'keywords': $output = get_option('meta-keywords'); break;
		case 'google-tagmanager':
		case 'tagmanager': $output = get_option('meta-google-tagmanager'); break;
		case 'google-analytics':
		case 'analytics': $output = get_option('meta-google-analytics'); break;
		case 'copyright': $output = apply_filters('the_content', get_option('copyright-info')); break;
		case 'thumbnail-url': $output = get_the_post_thumbnail_url(get_the_ID()); break;
        case 'date-single':
        case 'date-article': $output = boolval(get_option('display-date-single')); break;
			
		case 'connexion-form': $output = get_theme_mod('connexion_form'); break;
		case 'palette-color':
		case 'palette': $output = (wp_get_global_settings(['color', 'palette'])); break;
		case 'primary-color': $output = (wp_get_global_settings(['color', 'palette', 'theme']))[0]['color']; break;
		case 'secondary-color': $output = (wp_get_global_settings(['color', 'palette', 'theme']))[1]['color']; break;
		case 'grey-color': $output = (wp_get_global_settings(['color', 'palette', 'theme']))[2]['color']; break;
		case 'black-color': $output = (wp_get_global_settings(['color', 'palette', 'default']))[0]['color']; break;
		case 'white-color': $output = (wp_get_global_settings(['color', 'palette', 'default']))[2]['color']; break;
		case 'splashscreen': $output = boolval(get_theme_mod('splashscreen')); break;
		
		case 'cookieconsent_title': $output = get_theme_mod('cookieconsent_title'); break;
		case 'cookieconsent_description': $output = get_theme_mod('cookieconsent_description'); break;
		case 'cookieconsent_cookieusage': $output = get_theme_mod('cookieconsent_cookieusage'); break;
		case 'cookieconsent_necessarycookies': $output = get_theme_mod('cookieconsent_necessarycookies'); break;
		case 'cookieconsent_analyticscookies': $output = get_theme_mod('cookieconsent_analyticscookies'); break;
		case 'cookieconsent_acceptallbtn': $output = get_theme_mod('cookieconsent_acceptallbtn'); break;
		case 'cookieconsent_acceptnecessarybtn': $output = get_theme_mod('cookieconsent_acceptnecessarybtn'); break;
		case 'cookieconsent_showpreferencesbtn': $output = get_theme_mod('cookieconsent_showpreferencesbtn'); break;
		case 'cookieconsent_savepreferencesbtn': $output = get_theme_mod('cookieconsent_savepreferencesbtn'); break;
		case 'cookieconsent_privacypolicy': $output = get_post(get_theme_mod('cookieconsent_privacypolicy')); break;
		case 'cookieconsent_termsandconditions': $output = get_post(get_theme_mod('cookieconsent_termsandconditions')); break;
		case 'cookieconsent_mail': $output = get_theme_mod('cookieconsent_mail'); break;

		default:
			$slug_show = str_ireplace("-", "_", $show);
			if(get_theme_mod($slug_show)) $output = get_theme_mod($slug_show);
			elseif(get_option($slug_show)) $output = get_option($slug_show);
			else $output = "";
			break;
	}
	return $output;
}