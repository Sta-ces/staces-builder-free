<?php
function echo_r($content){ echo "<pre data-pre='dev_info_echo_r'>"; var_dump($content); echo "</pre>"; }
function isDev(){ return isset($_GET["dev"]) && $_GET["dev"] == "true"; }
function dev_r($msg){ if(isDev()) echo_r($msg); }
function _st($word){
	$t = translate($word, "stacesbuilder");
	return ($word === $t) ? translate($word) : $t;
}
function _ste($word){ echo _st($word); }
function get_the_role($first = true){
	$roles = (array) (wp_get_current_user())->roles;
	return ($first && count($roles) > 0) ? $roles[0] : $roles;
}
function isAdmin(){ return get_the_role() === "administrator"; }
function isEditor(){ return get_the_role() === "editor"; }
function convert_array($array){
	$result = [];
	foreach ($array as $label => $value){
		if(!is_object($value) && !is_array($value))
			array_push($result, ["label" => (is_numeric($label) ? $value : $label), "value" => $value]);
	}
	return $result;
}
function get_current_url(){
	$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
    $url.= $_SERVER['HTTP_HOST'];
    $url.= $_SERVER['REQUEST_URI'];
	return $url;
}
function get_years_copyright($debut_date = "now"){
	$now_date = date("Y");
	if($debut_date == 'none' || $debut_date == 0) return "&copy;";
	else if($debut_date == 'now') return "&copy; ".$now_date;
	else if($now_date != $debut_date) return "&copy; ".$debut_date."-".$now_date;
	else return "&copy; ".$debut_date;
}
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function replace_between($str, $delimiter, $replacement) {
    $pos = strpos($str, $delimiter);
    $start = $pos === false ? 0 : $pos + strlen($delimiter);
    $pos = strpos($str, $delimiter, $start);
    $end = $start === false ? strlen($str) : $pos;
    $result = substr_replace($str,$replacement,  $start, $end - $start);
	$result = str_replace($delimiter, "", $result);
	return $result;
}
function create_tag($delimiter, $tag, $subject){
	if($subject == null) return "";
	if(strpos($subject, $delimiter) == false) return $subject;
	return replace_between($subject, $delimiter, "<".$tag.">".get_string_between($subject, "**", "**")."</".$tag.">");
}
function html_map($array, $tag = "span", $classes = "", $attr = ""){
	if(!count(array_filter($array))) return "";
	if(!empty($classes)) $classes = "class='{$classes}'";
	return "<{$tag} {$classes} {$attr}>".implode("</{$tag}><{$tag} {$classes}>", array_map("trim", array_filter($array)))."</{$tag}>";
}
function str_attr($string){ return str_replace("'", "&apos;", $string); }
function stth_convert_string($string){ return create_tag("**", "em", $string); }
function stth_get_the_category($id, $exclude = []){
	$cats = get_the_category($id);
	foreach($cats as $key => $value){
		if(!in_array($value->name, $exclude)) return $value;
	}
}
function getAnimations(){
	return [
		["label" => "Bounce", "value" => "animate__bounce"],
		["label" => "Flash", "value" => "animate__flash"],
		["label" => "Pulse", "value" => "animate__pulse"],
		["label" => "Rubber Band", "value" => "animate__rubberBand"],
		["label" => "Shake X", "value" => "animate__shakeX"],
		["label" => "Shake Y", "value" => "animate__shakeY"],
		["label" => "Head Shake", "value" => "animate__headShake"],
		["label" => "Swing", "value" => "animate__swing"],
		["label" => "Tada", "value" => "animate__tada"],
		["label" => "Wobble", "value" => "animate__wobble"],
		["label" => "Jello", "value" => "animate__jello"],
		["label" => "Heart Beat", "value" => "animate__heartBeat"],

		["label" => "Back in down", "value" => "animate__backInDown"],
		["label" => "Back in left", "value" => "animate__backInLeft"],
		["label" => "Back in right", "value" => "animate__backInRight"],
		["label" => "Back in up", "value" => "animate__backInUp"],

		["label" => "Back out down", "value" => "animate__backOutDown"],
		["label" => "Back out left", "value" => "animate__backOutLeft"],
		["label" => "Back out right", "value" => "animate__backOutRight"],
		["label" => "Back out up", "value" => "animate__backOutUp"],

		["label" => "Bounce in", "value" => "animate__bounceIn"],
		["label" => "Bounce in down", "value" => "animate__bounceInDown"],
		["label" => "Bounce in left", "value" => "animate__bounceInLeft"],
		["label" => "Bounce in right", "value" => "animate__bounceInRight"],
		["label" => "Bounce in up", "value" => "animate__bounceInUp"],

		["label" => "Bounce out", "value" => "animate__bounceOut"],
		["label" => "Bounce out down", "value" => "animate__bounceOutDown"],
		["label" => "Bounce out left", "value" => "animate__bounceOutLeft"],
		["label" => "Bounce out right", "value" => "animate__bounceOutRight"],
		["label" => "Bounce out up", "value" => "animate__bounceOutUp"],

		["label" => "Fade in", "value" => "animate__fadeIn"],
		["label" => "Fade in down", "value" => "animate__fadeInDown"],
		["label" => "Fade in down (Big)", "value" => "animate__fadeInDownBig"],
		["label" => "Fade in left", "value" => "animate__fadeInLeft"],
		["label" => "Fade in left (Big)", "value" => "animate__fadeInLeftBig"],
		["label" => "Fade in right", "value" => "animate__fadeInRight"],
		["label" => "Fade in right (Big)", "value" => "animate__fadeInRightBig"],
		["label" => "Fade in up", "value" => "animate__fadeInUp"],
		["label" => "Fade in up (Big)", "value" => "animate__fadeInUpBig"],
		["label" => "Fade in top left", "value" => "animate__fadeInTopLeft"],
		["label" => "Fade in top right", "value" => "animate__fadeInTopRight"],
		["label" => "Fade in bottom left", "value" => "animate__fadeInBottomLeft"],
		["label" => "Fade in bottom right", "value" => "animate__fadeInBottomRight"],

		["label" => "Fade out", "value" => "animate__fadeOut"],
		["label" => "Fade out down", "value" => "animate__fadeOutDown"],
		["label" => "Fade out down (Big)", "value" => "animate__fadeOutDownBig"],
		["label" => "Fade out left", "value" => "animate__fadeOutLeft"],
		["label" => "Fade out left (Big)", "value" => "animate__fadeOutLeftBig"],
		["label" => "Fade out right", "value" => "animate__fadeOutRight"],
		["label" => "Fade out right (Big)", "value" => "animate__fadeOutRightBig"],
		["label" => "Fade out up", "value" => "animate__fadeOutUp"],
		["label" => "Fade out up (Big)", "value" => "animate__fadeOutUpBig"],
		["label" => "Fade out top left", "value" => "animate__fadeOutTopLeft"],
		["label" => "Fade out top right", "value" => "animate__fadeOutTopRight"],
		["label" => "Fade out bottom left", "value" => "animate__fadeOutBottomLeft"],
		["label" => "Fade out bottom right", "value" => "animate__fadeOutBottomRight"],
		
		["label" => "Flip", "value" => "animate__flip"],
		["label" => "Flip in X", "value" => "animate__flipInX"],
		["label" => "Flip in Y", "value" => "animate__flipInY"],
		["label" => "Flip out X", "value" => "animate__flipOutX"],
		["label" => "Flip out Y", "value" => "animate__flipOutY"],
		
		["label" => "Light speed in right", "value" => "animate__lightSpeedInRight"],
		["label" => "Light speed in left", "value" => "animate__lightSpeedInLeft"],
		["label" => "Light speed out right", "value" => "animate__lightSpeedOutRight"],
		["label" => "Light speed out left", "value" => "animate__lightSpeedOutLeft"],
		
		["label" => "Rotate in", "value" => "animate__rotateIn"],
		["label" => "Rotate in down left", "value" => "animate__rotateInDownLeft"],
		["label" => "Rotate in down right", "value" => "animate__rotateInDownRight"],
		["label" => "Rotate in up left", "value" => "animate__rotateInUpLeft"],
		["label" => "Rotate in up right", "value" => "animate__rotateInUpRight"],
		["label" => "Rotate out", "value" => "animate__rotateOut"],
		["label" => "Rotate out down left", "value" => "animate__rotateOutDownLeft"],
		["label" => "Rotate out down right", "value" => "animate__rotateOutDownRight"],
		["label" => "Rotate out up left", "value" => "animate__rotateOutUpLeft"],
		["label" => "Rotate out up right", "value" => "animate__rotateOutUpRight"],
		
		["label" => "Hinge", "value" => "animate__hinge"],
		["label" => "Jack in the box", "value" => "animate__jackInTheBox"],
		["label" => "Roll in", "value" => "animate__rollIn"],
		["label" => "Roll out", "value" => "animate__rollOut"],

		["label" => "Zoom in", "value" => "animate__zoomIn"],
		["label" => "Zoom in down", "value" => "animate__zoomInDown"],
		["label" => "Zoom in left", "value" => "animate__zoomInLeft"],
		["label" => "Zoom in right", "value" => "animate__zoomInRight"],
		["label" => "Zoom in up", "value" => "animate__zoomInUp"],

		["label" => "Zoom out", "value" => "animate__zoomOut"],
		["label" => "Zoom out down", "value" => "animate__zoomOutDown"],
		["label" => "Zoom out left", "value" => "animate__zoomOutLeft"],
		["label" => "Zoom out right", "value" => "animate__zoomOutRight"],
		["label" => "Zoom out up", "value" => "animate__zoomOutUp"],

		["label" => "Slide in", "value" => "animate__slideIn"],
		["label" => "Slide in down", "value" => "animate__slideInDown"],
		["label" => "Slide in left", "value" => "animate__slideInLeft"],
		["label" => "Slide in right", "value" => "animate__slideInRight"],
		["label" => "Slide in up", "value" => "animate__slideInUp"],

		["label" => "Slide out", "value" => "animate__slideOut"],
		["label" => "Slide out down", "value" => "animate__slideOutDown"],
		["label" => "Slide out left", "value" => "animate__slideOutLeft"],
		["label" => "Slide out right", "value" => "animate__slideOutRight"],
		["label" => "Slide out up", "value" => "animate__slideOutUp"],
	];
}
function getAnimation($name){
	$anims = array_filter(getAnimations(), function($anim) use($name){ return $anim->label === $name; });
	return count($anims) ? $anims[0] : [];
}
function getAnimationsHas($name_contains){
	return array_filter(getAnimations(), function($anim) use($name_contains){ return str_contains($anim->label, $name_contains); });
}
function getCountries(){
    $countries_json = json_decode(file_get_contents("https://raw.githubusercontent.com/fannarsh/country-list/master/data.json"));
    $countries = [];
    foreach($countries_json as $key => $value)
        $countries[$value->code] = $value->name;
	asort($countries);
    return $countries;
}
function getCountry($code){ return getCountries()[$code]; }
function getWidget($id){
	ob_start();
	dynamic_sidebar($id);
	$sidebar = ob_get_clean();
	if ($sidebar) return "<section class='widget-item' id='$id'>" . $sidebar . "</section>";
	return "";
}
function hex2rgba($color, $opacity = 1) {
	$default = 'rgb(0,0,0)';
	if(empty($color)) return $default; 
	if ($color[0] == '#' ) $color = substr( $color, 1 );
	if (strlen($color) == 6)
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	elseif ( strlen( $color ) == 3 )
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	else return $default;
	$rgb = array_map('hexdec', $hex);
	return 'rgba('.implode(",",$rgb).','.$opacity.')';
}
function get_shortcodes(){
    global $shortcode_tags;
    $shortcodes = $shortcode_tags;
	ksort($shortcodes);
    return $shortcodes;
}
function get_meta($post_id, $name, $key, $default = ''){
	$post_key = "_".$name."_".$key."_meta_value";
	$post_meta = get_post_meta($post_id, $post_key, true);
	return (!empty($post_meta)) ? $post_meta : $default;
}
if(!function_exists("createDate")){
	function createDate($date, $format = "", $echo = true){
		if(empty($format)) $format = get_infoth("date-format");
		$df = date_i18n($format, strtotime($date));
		if($echo) echo $df;
		return $df;
	}
}
function get_all_forms(){
    $forms = [];
    if(is_plugin_active('fluentform/fluentform.php')){
        $formApi = fluentFormApi('forms');
    	$atts = [
    		'status' => 'published',
    		'sort_column' => 'id',
    		'sort_by' => 'DESC',
    		'page' => 1
    	 ];
    	 $fluentforms = $formApi->forms($atts, false);
    	 foreach($fluentforms['data'] as $f){
    	     $forms[$f->id] = $f->title;
    	 }
    }
    return $forms;
}
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && strpos($haystack, $needle) !== false;
    }
}
class MediaUpload{
    private $image_id = 0;
    private $image_name = '';
    private $is_multiple = false;
    function __construct($args){
        $this->image_id = $args['id'];
        $this->image_name = $args['name'];
        if(isset($args['multiple'])) $is_multiple = $args['multiple'];
        $this->render();
    }
    function getID(){ return $this->image_id; }
    function setID($id){ $this->image_id; }
    function render(){
        $image = wp_get_attachment_url( $this->image_id );
        if( preg_match("/(\.pdf)$/", $image) ) $image = includes_url('/images/media/document.png');
        $content = ($image) ? "<img src='".$image."' width='100' height='auto'>" : _st('Upload image');
        $style_remove = ($image) ? 'inherit' : 'none';
        $hidden = ($image) ? $this->image_id : '';
        ?>
            <a href="#" class="staces-upl"><?php echo $content; ?></a>
            <a href="#" class="staces-rmv" style="display:<?php echo $style_remove; ?>"><?php _ste("Remove image"); ?></a>
            <input type="hidden" name="<?php echo $this->image_name; ?>" id="<?php echo $this->image_name; ?>" value="<?php echo $hidden; ?>">
        <?php
    }
}