<?php
/*
Plugin Name: Pars Slider
Plugin URI: http://wordpress.org/plugins/pars-slider/
Description: by this plugin you can make a Jquery Slider , Simple easy and fast
Version: 0.7
Author: Mohammadreza dehqany
Author URI: http://parsgraph.net
License: Copy Right bY ParsGraph.net on 2014
*/
register_activation_hook( __FILE__, 'pars_slider_install' );
function pars_slider_install() {
add_option("parsslider_width");
add_option("parsslider_height");
add_option("parsslider_limit");
add_option("parsslider_direction");
update_option("parsslider_width","980");
update_option("parsslider_height","350");
update_option("parsslider_limit","5");
update_option("parsslider_direction","right");
}
add_action( 'init', 'parsslider_post_type' );
function parsslider_post_type() {
	register_post_type( 'parsslider',
		array(
			'labels' => array(
				'name' => __( 'ParsSlider' ),
				'singular_name' => __( 'parsslider' )
			),
		'public' => true,
		'has_archive' => true,
		'capability_type' => 'post',
		'supports' => array('title'),
		)
	);
}
function parss_wid(){
global $post;
echo '<style>input[type=text]{width:830px} </style>';
$thumb = get_post_meta($post->ID,"thumb",true);
$pic = get_post_meta($post->ID,"pic",true);
$url = get_post_meta($post->ID,"url",true);
$title1 = get_post_meta($post->ID,"title1",true);
$title2 = get_post_meta($post->ID,"title2",true);
echo '<input type="text" name="thumb" value="'.$thumb.'" placeholder="عکس کوچک"><br>';
echo '<input type="text" name="pic" value="'.$pic.'" placeholder="عکس بزرگ"><br>';
echo '<input type="text" name="url" value="'.$url.'" placeholder="لینک"><br>';
echo '<input type="text" name="title1" value="'.$title1.'" placeholder="عنوان 1"><br>';
echo '<input type="text" name="title2" value="'.$title2.'" placeholder="عنوان 2"><br>';
}
function post_wid_get(){
global $post;
$thumb_get = $_POST['thumb'];
$pic_get = $_POST['pic'];
$url_get = $_POST['url'];
$title1_get = $_POST['title1'];
$title2_get = $_POST['title2'];
if(!empty($thumb_get)){update_post_meta($post->ID,"thumb",$thumb_get); }
if(!empty($pic_get)){update_post_meta($post->ID,"pic",$pic_get); }
if(!empty($url_get)){update_post_meta($post->ID,"url",$url_get); }
if(!empty($title1_get)) {update_post_meta($post->ID,"title1",$title1_get); }
if(!empty($title2_get)) {update_post_meta($post->ID,"title2",$title2_get); }
}
function parsslider_setpanell(){
    add_meta_box( 
        'myplugin_sectionid',
        'اطلاعات عمومي',
        'parss_wid',
        'parsslider' 
    );
	
add_action( 'save_post', 'post_wid_get' );
}
function deregister_qjuery() {  
    if ( !is_admin() ) {
        wp_deregister_script('jquery');
		wp_register_script('jquery', "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js", FALSE, '1.6.2');
		wp_enqueue_script("jquery-migrate");
    }
}  

add_action('wp_enqueue_scripts', 'deregister_qjuery'); 
function headneed(){
	echo "<link rel='stylesheet' href='".plugins_url()."/pars-slider/pars-slider.css' type='text/css' media='all' />\n";
	echo "<style>
	.pars-slider {
		width:".get_option("parsslider_width").";
		height:".get_option("parsslider_height").";
	}
	#pars-slider li {
		width:".get_option("parsslider_width").";
		height:".get_option("parsslider_height").";
	}
	</style>";
	echo "<script src='".plugins_url()."/pars-slider/unslider.min.js' type='text/javascript'></script>\n";
	echo '<script>
	$(function() { 
	$("#pars-slider").unslider({delay: 4000, keys: true,dots: true }); 
	});
	</script>'."\n";
}
add_action('wp_head', 'headneed');
add_action('admin_menu', 'parsslider_setpanell'); 
function pars_slider(){
$limit = get_option("parsslider_limit");
$width = get_option("parsslider_width");
$height= get_option("parsslider_height");
echo '<div class="pars-slider"><div id="pars-slider"><ul>';
query_posts("showposts=$limit&post_type=parsslider"); 
if(have_posts()) : while(have_posts()) : the_post(); 
global $post;
?>
<li style="background-image: url('<?php echo get_post_meta($post->ID,"pic",true); ?>');">
<a href="<?php echo get_post_meta($post->ID,"url",true); ?>"></a>
<div class="title"><?php echo get_post_meta($post->ID,"title1",true); ?></div>
<div class="title2"><?php echo get_post_meta($post->ID,"title2",true); ?></div>
</li>

<?php
endwhile; 
endif; 
wp_reset_query();
echo "</ul></div></div>\n";
}
function parss_setpanell(){
add_submenu_page('options-general.php','پارس اسلایدر', 'پارس اسلایدر', 'manage_options','front-page-elements', 'parss_settings'); 
}
function parss_settings(){
echo '<center><div style="width:400px;height:auto;padding:5px;border-radius:3px;background:#f7f7f7;border:1px solid #c7c7c7;margin:15px;text-align:right;">';
if(isset($_POST['save'])){
update_option("parsslider_width",$_POST['width']);
update_option("parsslider_height",$_POST['height']);
update_option("parsslider_limit",$_POST['limit']);
echo "تنظیمات ذخیره شد";
}
echo '<form method="post">
طول اسلایدر : <input type="text" name="width" value="'.get_option("parsslider_width").'"><br>
عرض اسلایدر : <input type="text" name="height" value="'.get_option("parsslider_height").'"><br>
تعداد نمایش اسلایدر ها : <input type="text" name="limit" value="'.get_option("parsslider_limit").'"><br>
<input type="submit" value="ذخیره" name="save"><br>
کلیه ی حقوق این افزونه مربوط به وب سایت ParsGraph.Net میباشد 
</form></div></center>';
}
add_action('admin_menu', 'parss_setpanell'); 
?>