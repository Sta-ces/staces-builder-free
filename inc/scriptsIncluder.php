<?php
function scriptIncluder($params){
	for ($p=0; $p < count($params); $p++) {

		$params[$p] = array_merge([
			"extension" => "js",
			"type" => "js",
			"name" => "",
			"excludes" => [],
			"modules" => [],
			"url" => ""
		], $params[$p]);

		$ext = $params[$p]["extension"];
		$type = $params[$p]["type"];
		$not = $params[$p]["excludes"];
		$modules = $params[$p]["modules"];
		$name_script = $params[$p]["name"];
		$url = $params[$p]["url"];

		if($ext == "cdn"){
			if(!empty($url)){
				if($type == "js"){
					if(boolval($modules)) wp_enqueue_script_module( $name_script, $url );
					else wp_enqueue_script( $name_script, $url );
				}
				elseif($type == "css") wp_enqueue_style( $name_script, $url );
			}
		}
		else{
			$dir = "/assets/".$ext;
			$tab_script = scandir(get_parent_theme_file_path($dir));

			foreach ($tab_script as $script) {
				if(!in_array($script, $not) && preg_match("/\.".$ext."$/", $script)){
					$file = get_path_uri($dir.'/'.$script);
					$custom = empty($name_script) ? 'custom-'.$script : $name_script.'-'.$script;
					$path = get_theme_path($dir."/".$script);
					if(is_file($path) && file_exists($path)){
						if($ext == "js"){
							if(in_array($script, $modules)) wp_enqueue_script_module( $custom, $file, [], get_infoth('version') );
							else wp_enqueue_script( $custom, $file, [], get_infoth('version') );
						}
						else if($ext == "css"){ wp_enqueue_style( $custom, $file, [], get_infoth('version') ); }
					}
				}
			}
		}
	}
}

function adder_script(){
	scriptIncluder([
		[
			"extension"	=> "js",
			"excludes"	=> [ "custom.min.js" ],
			"modules"	=> [ "base.min.js", "modules.min.js", "cookieconsent-config.min.js" ]
		],
		[
			"extension"	=> "css",
			"excludes"	=> []
		],
		[
			"extension"	=> "cdn",
			"type"		=> "js",
			"name"		=> "dom-tools",
			"url"		=> "https://cdn.jsdelivr.net/gh/Sta-ces/dom-tools/tools.min.js"
		],
		[
			"extension"	=> "cdn",
			"type"		=> "css",
			"name"		=> "fontawesome-free",
			"url"		=> "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
		]
	]);

	// CUSTOM FILES
	$path_custom_js = '/assets/js/custom.min.js';
	if(file_exists(get_theme_path($path_custom_js)))
		wp_enqueue_script('custom_file_js', get_path_uri($path_custom_js), [], get_infoth('version'));

	wp_localize_script('custom-index.min.js', 'cookiesconsents', cookies_infoth());
}
add_action( 'wp_enqueue_scripts', 'adder_script' );
add_action( 'admin_enqueue_scripts', 'adder_script', 10, 1 );

function custom_login_stylesheet() {
	$primary = get_infoth('primary-color');
	$grey = get_infoth('grey-color');
	$black = get_infoth('black-color');
	$white = get_infoth('white-color');
	?>
	<style type="text/css">
		body.login{
			background-color: <?php echo $grey; ?>;
			background-image: url(<?php echo get_background_image(); ?>);
			background-size: cover;
			background-position: center;
			position: relative;
			display: flex;
		}
		body.login::before{
			position: absolute;
			top: 0; left: 0; right: 0; bottom: 0;
			content: "";
			display: block;
			background-color: <?php echo $grey; ?>;
			opacity: .85;
			z-index: -1;
		}
		body.login div#login{
			background-color: <?php echo $white; ?>;
			border: solid .25em <?php echo $primary; ?>;
			padding: 2em;
			margin: auto;
			position: relative;
		}
		body.login div#login h1,
		body.login div#login::before{
			position: absolute;
			top: 0; left: 50%;
			-webkit-transform: translate(-50%, -50%);
			-moz-transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			-o-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
			border-radius: 50%;
			width: 100px; height: 100px;
			padding: .75em;
			background-color: <?php echo $white; ?>;
		}
		body.login div#login::before{
			content: "";
			display: block;
			padding: 1.25em;
			z-index: -1;
			background-color: <?php echo $black; ?>;
			border: solid .5em <?php echo $primary; ?>;
		}
		body.login div#login h1 a{
			background-image: url(<?php infoth("logo-url"); ?>);
			background-position: center;
			background-size: contain;
		}
		body.login div#login form#loginform{
			background-color: transparent;
			padding: 0;
			margin-top: 4em;
			border: none;
		}
		body.login div#login form#loginform *{ float: none; }
		body.login div#login form#loginform > *{ text-align: center; }
		body.login div#login form#loginform input[type=text],
		body.login div#login form#loginform input[type=password]{
			border: none;
			border-bottom: solid 2px <?php echo $black; ?>;
			font-size: 20px;
			margin-bottom: 24px;
			box-shadow: none;
		}
		body.login div#login form#loginform input[type=text]:focus,
		body.login div#login form#loginform input[type=password]:focus{
			border: solid 2px <?php echo $black; ?>;
			box-shadow: none;
		}
		body.login div#login form#loginform input[type=checkbox]{ border-color: <?php echo $black; ?>; }
		body.login div#login form#loginform input[type=checkbox]::before{ color: <?php echo $black; ?>; }
		body.login div#login form#loginform .forgetmenot{ margin-bottom: 1em; }
		body.login div#login form#loginform .submit input[type=submit]{
			border-radius: 0;
			background-color: <?php echo $black; ?>;
			border: none;
			text-shadow: none;
			text-transform: uppercase;
		}
		body.login div#login #nav,
		body.login div#login #backtoblog{ text-align: center; }
		body.login div#login .password-input + .button,
		body.login div#login .password-input + .button-secondary,
		body.login div#login .password-input + .button:hover,
		body.login div#login .password-input + .button-secondary:hover,
		body.login div#login .password-input + .button:focus,
		body.login div#login .password-input + .button-secondary:focus{
			color: <?php echo $grey; ?>;
			border: none;
			box-shadow: none;
		}
		body.login div#login #login_error{
			border: solid 4px red;
			margin-top: 50px;
			text-align: center;
		}
	</style>
	<script>
		addEventListener("load", function(){
			if(document.querySelector("body.login #login a") != null)
				document.querySelector("body.login #login a").setAttribute("href", "<?php echo get_infoth('home-url'); ?>");
		});
	</script>
	<?php
}
add_action( 'login_enqueue_scripts', 'custom_login_stylesheet' );