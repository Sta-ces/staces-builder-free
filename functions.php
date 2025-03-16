<?php
function get_theme_path($path){
	if(file_exists(get_stylesheet_directory() . $path)) return get_theme_file_path($path);
	elseif(file_exists(get_template_directory() . $path)) return get_parent_theme_file_path($path);
	else return null;
}
function get_path_uri($path){
	return file_exists(get_stylesheet_directory().$path) ? get_stylesheet_directory_uri().$path : get_template_directory_uri().$path;
}
function require_theme_path($path){ if(get_theme_path($path)) require_once(get_theme_path($path)); }
define("ISLOG", is_user_logged_in());
define("ASSETS", get_theme_path("/assets"));
require_theme_path( '/inc/tools.php' );
require_theme_path( '/inc/infoth-manager.php' );
require_theme_path( '/inc/customizer.php' );
require_theme_path( '/inc/settings.php' );
require_theme_path( '/inc/scriptsIncluder.php' );
require_theme_path( '/inc/shortcodes.php' );
require_theme_path( '/child-functions.php' );
function page_closed() {
	if (!(isAdmin() || isEditor()) && get_infoth('is-close') && !is_page(get_infoth("close-page"))){
		exit( wp_redirect( get_permalink( get_infoth("close-page") ) ) );
    }
}
add_action( 'template_redirect', 'page_closed' );
$reading_website = new CustomSettings("more-reading-settings", _st("Additional settings"), "reading");
$google_settings = new CustomSettings("google-settings", _st("Google settings"), "google-settings-tag", _st("Google keys (SEO)"));
$reading_website->add_fields([
	[
		"name"=>"active-commentary",
		"title"=>_st("Disable comments?"),
		"type"=>"checkbox"
	]
]);
$google_settings->add_fields([
	[
		"name"=>"meta-google-tagmanager",
		"title"=>_st("Google TagManager"),
		"placeholder"=>"GT-xxxxxx"
	],
	[
		"name"=>"meta-google-analytics",
		"title"=>_st("Google Analytics"),
		"placeholder"=>"G-xxxxxxxxxx"
	],
	[
		"name"=>"google-map-api",
		"title"=>_st("Google Map (API key)"),
		"placeholder"=>"xxxxxxx"
	],
	[
		"name"=>"meta-keywords",
		"title"=>_st("Keywords"),
		"type"=>"textarea",
		"placeholder"=>_st("design,belgium,web,...")
	]
]);
function stath_custom_header_setup(){
	load_theme_textdomain( 'staces-builder', get_template_directory() . '/lang' );
	/** THEME SUPPORT */
    add_theme_support( 'custom-logo', array(
		'header-text' => array( 'site-title', 'site-description' )
	) );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'custom-background' );
    add_theme_support( 'title-tag' );
	add_post_type_support('page', 'excerpt');
	add_post_type_support('post', 'page-attributes');
	if(!get_infoth('is-commentary')) remove_theme_support( 'comments' );
	/** END THEME SUPPORT */
}
add_action('after_setup_theme', 'stath_custom_header_setup');
function stath_admin_menus(){
	if(!get_infoth('is-commentary')){
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}
	add_submenu_page( 'tools.php', _st('Tricks'), _st('Tricks'), 'administrator', 'staces-tricks', 'settings_staces_callback' );
}
add_action('admin_menu', 'stath_admin_menus');
function stath_init(){	
	if(!get_infoth('is-commentary')){
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );
	}
}
add_action( 'init', 'stath_init' );
function settings_staces_callback(){
	$content_class = [
		"hidden" => _st("Hides the HTML element"),
		"italic" => _st("Puts text in <span class='italic'>italic</span>"),
		"bold" => _st("Puts text in <span class='bold'>bold</span>"),
		"lowercase" => _st("Puts text in <span class='lowercase'>lowercase</span>"),
		"uppercase" => _st("Puts text in <span class='uppercase'>uppercase</span>"),
		"absolute" => _st("Positions the HTML element as <em>absolute</em>"),
		"fixed" => _st("Positions the HTML element in a <em>fixed</em> way")
	];
	$content_shortcode = [
		"copyright" => _st("<strong>[copyright date='".(date('Y')-1)."']</strong> : Displays a start year followed by the current year with the copyright symbol (&copy;).<br/><em> Default: if the date is not specified, it will automatically return the active year (".date('Y').").</em>"),
		"info" => _st("<strong>[info name='{info-name}' tag='p' displaytag='true']</strong> : Displays site informations. <br/><em>Possible info list: ".implode(", ", table_infoth())."</em>"),
		"birth" => _st("<strong>[birth date='2009']</strong> : Displays age from year of birth."),
		"sitename" => _st("<strong>[sitename]</strong> : Displays the name of the site."),
		"tag" => _st("<strong>[tag slug='{tag-slug}' id='{tag-id}']</strong> : Displays the category or tag with the slug or id.")
	];
	$contents = [_st("Classes") => $content_class, _st("Shortcodes (only for PRO)") => $content_shortcode];
	?>
	<div class="wrap">
		<h2><?php _ste("Tips and tricks from Staces"); ?></h2>
		<?php foreach($contents as $ks => $vs): ?>
			<h3><?php echo $ks; ?></h3>
			<table class="wp-list-table widefat striped table-view-list pages">
				<thead>
					<tr>
						<th width="100"><strong>Name</strong></th>
						<th><strong>Description</strong></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($vs as $k => $v): ?>
						<tr><td><strong><?php echo $k; ?></strong></td><td><?php _ste($v); ?></td></tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endforeach; ?>
	</div>
	<?php
}
function add_to_head_end(){
	$description = get_the_excerpt();
	if(empty($description)) $description = get_infoth('description');
	$url = get_site_url()."/".get_page_uri(get_the_ID());
	$terms = get_the_terms(get_the_ID(), 'keywords');
	$tags = "";
	if(!isset($terms->errors) && $terms) foreach($terms as $t){ $tags .= $t->name.","; }
	$tags .= get_infoth('keywords');
	?>
	<meta name="author"						content="<?php infoth('sitename'); ?>">
	<meta name="keywords"					content="<?php echo $tags; ?>">
	<meta property="fb:app_id"				content="2908287049300335" />
	<meta property="og:url"					content="<?php echo $url; ?>" />
	<meta property="og:type"				content="website" />
	<meta property="og:locale"				content="fr_FR" />
	<meta property="og:title"				content="<?php echo get_the_title()." | ".get_infoth('sitename'); ?>" />
	<meta property="og:description"			content="<?php echo $description; ?>" />
	<meta property="og:image"				content="<?php echo get_background_image(); ?>" />
	<meta property="og:image:secure_url"	content="<?php echo get_background_image(); ?>" />
	<meta property="og:image:width"			content="1200" />
	<meta property="og:image:height"		content="800" />
	<?php
}
add_action('wp_head', 'add_to_head_end', 100);
function add_to_head_before(){
	$idGoogleTagManager = get_infoth('tagmanager');
	?>
	<?php if($idGoogleTagManager): ?>
	<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo $idGoogleTagManager; ?>');</script>
	<!-- End Google Tag Manager -->
	<?php endif; ?>
	<?php
}
add_action('wp_head', 'add_to_head_before', 0);
function add_to_body_before(){
	$idGoogleTagManager = get_infoth('tagmanager');
	?>
	<!-- Google Tag Manager (noscript) -->
	<?php if($idGoogleTagManager): ?>
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $idGoogleTagManager; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<?php endif; ?>
	<!-- End Google Tag Manager (noscript) -->
	<?php
}
add_action('wp_body_open', 'add_to_body_before', 0);
// Removes from admin bar
function stth_admin_bar_render() {
    global $wp_admin_bar;
    if(!get_infoth('is-commentary')) $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'stth_admin_bar_render' );
function remove_body_classes( $classes ) { 
    $remove_classes = ['custom-background'];
    $classes = array_diff($classes, $remove_classes);
    return $classes;
}
add_filter('body_class', 'remove_body_classes');
// fluent forms connexion
add_action('fluentform_before_insert_submission', function ($insertData, $data, $form) {
	if($form->id != get_infoth('connexion-form')) return;
	$confirmation = $form->settings["confirmation"];
	$RedirectPageID = $confirmation["customPage"];
	$redirectUrl = ($RedirectPageID) ? get_permalink($RedirectPageID) : home_url(); // You can change the redirect url after successful login
	if($confirmation["enable_query_string"] === "yes") $redirectUrl .= "?".$confirmation["query_strings"];
	// if you have a field as refer_url as hidden field and value is: {http_referer} then
	// We can use that as a redirect URL. We will redirect if it's the same domain
	// If you want to redirect to a fixed URL then remove the next 3 lines
	if(!empty($data['refer_url']) && strpos($data['refer_url'], site_url()) !== false) {
		$redirectUrl = $data['refer_url'];
	}
	if (get_current_user_id()) { // user already login
		wp_send_json_success([
			'result' => [
				'redirectUrl' => $redirectUrl,
				'message' => _st('You are already logged in. Redirection in progress...')
			]
		]);
	}
	$email = \FluentForm\Framework\Helpers\ArrayHelper::get($data, 'email'); // your form should have email field
	$password = \FluentForm\Framework\Helpers\ArrayHelper::get($data, 'password'); // your form should have password field
	if(!$email || !$password) {
		wp_send_json_error([
			'errors' => [_st('Please provide an email and password')]
		]);
	}
	$user = get_user_by_email($email);
	if($user && wp_check_password($password, $user->user_pass, $user->ID)) {
		wp_clear_auth_cookie();
		wp_set_current_user($user->ID);
		wp_set_auth_cookie($user->ID);
		/* user is not logged in.
		* If you use wp_send_json_success the the submission will not be recorded
		* If you remove the wp_send_json_success then it will record the data in fluentform
		* in that case you should redirect the user on form submission settings
		*/
		wp_send_json_success([
			'result' => [
				'redirectUrl' => $redirectUrl,
				'message' => _st('You are logged in, please wait while you are redirected...')
			]
		]);
	} else {
		// password or user don't match
		wp_send_json_error([
			'errors' => [_st('Email/ password is incorrect')]
		]);
	}
}, 10, 3);
/** DUPLICATE POSTS */
/**
 * Adds a "Duplicate" link next to the other actions on each element in WordPress administration
 * and duplicates the post when clicking this link.
 *
 * @param array $actions Existing actions for the element
 * @param WP_Post $post The current subject of publication
 * @return array Actions updated with the "Duplicate" link added
 */
function add_duplicate_action($actions, $post) {
    if ((current_user_can('edit_posts') || current_user_can('edit_pages') || current_user_can('edit_others_posts') || current_user_can('edit_others_pages')) && $post->post_type !== 'attachment') {
        $duplicate_nonce = wp_create_nonce('duplicate_post_' . $post->ID);
        $duplicate_url = admin_url('admin.php?action=duplicate_post&post=' . $post->ID . '&_wpnonce=' . $duplicate_nonce);
        $actions['duplicate'] = '<a href="' . esc_url($duplicate_url) . '" title="' . _st('Duplicate this publication') . '">' . _st('Duplicate') . '</a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'add_duplicate_action', 10, 2);
add_filter('page_row_actions', 'add_duplicate_action', 10, 2);
/**
 * Duplicates a post in WordPress when a query is sent with the 'duplicate_post' action
 */
function duplicate_post_action() {
    // Checks permissions and presence of the nonce
    if (isset($_GET['action']) && $_GET['action'] === 'duplicate_post' && isset($_GET['post']) && wp_verify_nonce($_GET['_wpnonce'], 'duplicate_post_' . $_GET['post'])) {
        $post_id = absint($_GET['post']);
        $new_post_id = duplicate_post($post_id);

        if (!is_wp_error($new_post_id)) {
            // Redirects to the new publication edit screen
            wp_safe_redirect(admin_url('post.php?post=' . $new_post_id . '&action=edit'));
            exit;
        } else {
            wp_die($new_post_id->get_error_message());
        }
    }
}
add_action('admin_init', 'duplicate_post_action');
/**
 * Duplicate a post in WordPress
 *
 * @param int $post_id Publication ID to duplicate
 * @return int|WP_Error New publication ID or error if duplication failed
 */
function duplicate_post($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return new WP_Error('post_not_found', _st('Publication not found'));
    }
    $args = array(
        'post_title' => $post->post_title . ' ('._st('Copy').')',
        'post_content' => $post->post_content,
        'post_status' => $post->post_status,
        'post_type' => $post->post_type,
        'post_author' => $post->post_author,
    );
    $new_post_id = wp_insert_post($args);
    if (is_wp_error($new_post_id)) {
        return $new_post_id;
    }
    // Duplicate the publication metadata
    $meta_data = get_post_meta($post_id);
    foreach ($meta_data as $key => $value) {
        foreach ($value as $meta_value) {
            add_post_meta($new_post_id, $key, $meta_value);
        }
    }
    return $new_post_id;
}