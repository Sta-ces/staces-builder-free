<?php
function info_shortcode($atts){
   $tp_atts = shortcode_atts(array( 
      'name' =>  null,
      'tag' => "p",
      'displaytag' => true
   ), $atts);
   $html = "";
   if($tp_atts['name']){
      $html .= (boolval($tp_atts['displaytag'])) ? "<".$tp_atts['tag']." class='".str_replace(" ", "", strtolower($tp_atts['name']))."'>" : "";
      $html .= get_infoth($tp_atts['name']);
      $html .= (boolval($tp_atts['displaytag'])) ? "</".$tp_atts['tag'].">" : "";
   }
   return $html;
}
function post_content_shortcode($atts){
   if(!isset($atts['id'])) return;
  return apply_filters("the_content", (get_post($atts['id']))->post_content);
}
function get_copyright($atts){
   $tp_atts = shortcode_atts(array( 
      'date' =>  "2021",
   ), $atts);
   return get_years_copyright($tp_atts["date"]);
}
function get_birth($atts){
   $date = isset($atts["date"]) ? intval($atts["date"]) : 2009;
   return (intval(date("Y")) - $date);
}
function sitename_shortcode($atts){ return get_infoth('sitename'); }
function tag_shortcode($atts) {
   $atts = shortcode_atts(array(
      'slug' => '',
      'id' => ''
   ), $atts);
   if (empty($atts['slug']) && empty($atts['id']))
       return _st('Please provide a valid slug or tag ID.');
   if (!empty($atts['slug'])) $tag = get_term_by('slug', $atts['slug'], 'post_tag');
   if (!empty($atts['id'])) $tag = get_term_by('id', $atts['id'], 'post_tag');
   return ($tag) ? $tag->name : _st('No tags found for the slug : ') . $atts['slug'];
}
function icon_displayer($atts){
	$params = array_merge([
		"inner-text" => "",
      "name" => "",
		"prefix" => "fa",
      "type" => "solid",
      "name" => "",
		"color" => "var(--wpem-primary-color)",
		"font-size" => "inherit",
		"styles" => "",
		"classes" => ""
	], $atts);
	if(!isset($params["type"]) || !isset($params["name"])) return "";
	return "<i class='{$params['prefix']}-{$params['type']} {$params['prefix']}-{$params['name']} {$params['classes']} dashicons' style='color: {$params['color']}; font-size: {$params['font-size']}; {$params['styles']}'>{$params['inner-text']}</i>";
}
function shortcodes_init(){
   add_shortcode('info', 'info_shortcode');
	add_shortcode('post-content', 'post_content_shortcode');
   add_shortcode('copyright', 'get_copyright');
   add_shortcode('sitename', 'sitename_shortcode');
   add_shortcode('birth', 'get_birth');
	add_shortcode('tag', 'tag_shortcode');
   if(!shortcode_exists('icon')) add_shortcode('icon', 'icon_displayer');
   if(function_exists("shortcodes_child")) shortcodes_child();
}
add_action('init', 'shortcodes_init');