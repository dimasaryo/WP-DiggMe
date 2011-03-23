<?php
/*
Plugin Name: WP DiggMe
Version: 0.1
Description: Add Digg Button to post and page
Author: Dimas Aryo
Author URL: http://www.dimasaryo.com
*/

//Avoid name collisions.
if(!class_exists('WPDiggMe')) :
class WPDiggMe{
	//this variable will hold url to plugin
	var $plugin_url;
	
	//name for WPDiggMe options in the DB
	var $db_option = 'WPDiggMe_Options';
	
	//handle plugin options
	function get_options(){
		//default values
		$options = array(
			'button_class'=>'DiggMedium',
			'posts'=>'',
			'pages'=>'',
		);
		
		//get saved options
		$saved = get_option($this->db_option);
		
		//asign them
		if(!empty($saved)){
			foreach($saved as $key=>$option)
				$options[$key]=$option;
		}
		
		//update the options if necessary
		if($saved != $options)
			update_option($this->db_option, $options);
			
		//return the options
		return $options;
	}
	
	//Set up everything
	function install(){
		//set default options
		$this->get_options();
	}
	
	//Uninstall plugins
	function uninstall(){
		$options = $this->get_options();
		delete_option($this->db_option,$options);
	}
	
	//handle the option page
	function handle_options(){
		$options = $this->get_options();
		if(isset($_POST['submitted'])){
			//check security
			check_admin_referer('wpdiggme-nonce');
			$options = array();
			$options['posts'] = $_POST['posts'];
			$options['pages'] = $_POST['pages'];
			$options['button_class'] = $_POST['button_class'];
			
			update_option($this->db_option,$options);
			echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
		}
		
		$posts = $options['posts'] == 'on' ? 'checked' : '';
		$pages = $options['pages'] == 'on' ? 'checked' : '';
		$button_class = $options['button_class'];
		
		//URL for from submit, equals our current page;
		$action_url = $_SERVER['REQUEST_URL'];
		include('wpdiggme-options.php');
	}
	
	function WPDiggMe(){
		/* Version check */
		global $wp_version;
		$exit_msg = 'WP Digg Me requires Wordpress 3.1 or newer. <a href="http//codex.wordpress.org/Upgrading_WordPress">Please update!</a>';
		if(version_compare($wp_version,"2.8","<")){
			exit($exit_msg);
		}
		$this->plugin_url=trailingslashit(get_bloginfo('wpurl')).PLUGINDIR.'/'.dirname(plugin_basename(__FILE__));
		add_action('admin_menu',array(&$this,'admin_menu'));
		add_filter('the_content',array(&$this,'WPDiggMe_Show'));
	}
	
	function admin_menu(){
		add_options_page('WP DiggMe Options','WP DiggMe',8, basename(__FILE__),array(&$this,'handle_options'));
	}
	
	/*Adding javascript for digg button*/
	function WPDiggMe_Javascript(){
		$javascript ="
			<script src='http://widgets.digg.com/buttons.js' type='text/javascript'></script>
			<script type='text/javascript'>
				(function() {
					var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
					s.type = 'text/javascript';
					s.async = true;
					s.src = 'http://widgets.digg.com/buttons.js';
					s1.parentNode.insertBefore(s, s1);
				})();
			</script>";
	return $javascript;
	}
	
	function WPDiggMe_Button()
	{
		global $post;
		$options= $this->get_options();
		//button class
		$button_class=$options['button_class'];
	
		//get the URL to the post
		$link = urlencode(get_permalink($post->ID));
	
		//get the post title
		$title=urlencode($post->post_title);
	
		//get the content
		$text=urlencode(substr(strip_tags($post->post_content),0,350));
	
		//create a Digg button and return it
		$button ="
			".$this->WPDiggMe_Javascript()."
			<a class='DiggThisButton ".$button_class."' href='http://digg.com/submit?url=".$link."&amp;title=".$title."'>
			<span style='display:none'>
				".$text."
			</span>
			</a>";

		$button = '
			<div style="float:right; margin-left:10px; margin-bottom:4px;">
				'.$button.'
			</div>
		';
		return $button;
	}
	
	function WPDiggMe_Show($content){
		$options = $this->get_options();
		if($options['posts']=='on' && is_single()){
			return $this->WPDiggMe_Button().$content;
		} else {
			return $content;
		}
		if($options['pages']=='on' && is_page()){
			return $this->WPDiggMe_Button().$content;
		} else {
			return $content;
		}
	}
}

else :
	exit("Class WPDiggMe already declared!");
endif;

$WPDiggMe = new WPDiggMe;
if(isset($WPDiggMe)){
	//register the activation function by passing the reference to our instance
	register_activation_hook(__FILE__,array(&$WPDiggMe,'install'));
}
if ( function_exists('register_uninstall_hook') ){
	register_uninstall_hook(__FILE__,array(&$WPDiggMe,'uninstall'));
}
?>