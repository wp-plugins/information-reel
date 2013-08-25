<?php

/*
Plugin Name: Information Reel
Plugin URI: http://www.gopiplus.com/work/2011/04/16/wordpress-plugin-information-reel/
Description: This plugin scroll the entered title, image, and description in your word press website. This is best way to announce your message to user. Live demo availabe in the plugin site.
Author: Gopi.R
Version: 7.1
Author URI: http://www.gopiplus.com/work/
Donate link: http://www.gopiplus.com/work/2011/04/16/wordpress-plugin-information-reel/
Tags: Announcement, Scroller, Message, Scroll, Text scroll, News
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("WP_IR_TABLE", $wpdb->prefix . "information_reel");
define("WP_IR_UNIQUE_NAME", "information-reel");
define("WP_IR_TITLE", "Information Reel");
define('WP_IR_FAV', 'http://www.gopiplus.com/work/2011/04/16/wordpress-plugin-information-reel/');
define('WP_IR_LINK', 'Check official website for more information <a target="_blank" href="'.WP_IR_FAV.'">click here</a>');

function IR_Show() 
{
	$arr = array();
	IR_Show_Widget($arr);
}

function IR_Show_Widget( $atts ) 
{
	global $wpdb;
	$IRhtml 		= "";
	$IRjsjs 		= "";
	$IR_x 			= "";
	$IR_Height 		= "";
	$IR_TextLength 	= "";
	$IR_SameTime 	= "";
	$IR_Height	 	= "";
	$IR_type 		= "";
	$IR_random 		= "";

	if ( is_array( $atts ) )
	{
		foreach(array_keys($atts) as $key)
		{
			if($key == "IR_TextLength")
			{
				$IR_TextLength = $atts["IR_TextLength"];
			}
			elseif($key == "IR_SameTime")
			{
				$IR_SameTime = $atts["IR_SameTime"];
			}
			elseif($key == "IR_Height")
			{
				$IR_Height = $atts["IR_Height"];
			}
			elseif($key == "IR_type")
			{
				$IR_type = $atts["IR_type"];
			}
			elseif($key == "IR_random")
			{
				$IR_random = $atts["IR_random"];
			}
		}
	}
	
	if($IR_TextLength == "")
	{
		$IR_TextLength = get_option('IR_TextLength');
	}
	if($IR_SameTime == "")
	{
		$IR_SameTime = get_option('IR_SameTime');
	}
	if($IR_Height == "")
	{
		$IR_Height = get_option('IR_Height');
	}
	if($IR_type == "")
	{
		$IR_type = get_option('IR_type');
	}
	if($IR_random == "")
	{
		$IR_random = get_option('IR_random');
	}
	
	if(!is_numeric($IR_SameTime)){ $IR_SameTime = 5; }
	if(!is_numeric($IR_Height)){ $IR_Height = 50; }

	$sSql = "select IR_path,IR_link,IR_target,IR_title,IR_desc from ".WP_IR_TABLE." where 1=1 and IR_status='YES'";
	if($IR_type <> "" )
	{
		$sSql = $sSql . " and IR_type='".$IR_type."'";
	}
	if($IR_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY IR_order"; }
	$IR_data = $wpdb->get_results($sSql);
	
	?>
    <style type="text/css">
	.IR-regimage img {
		float: left;
		vertical-align:bottom;
		padding: 3px;
	}
	</style>
    <?php
	if ( ! empty($IR_data) ) 
	{
		$IR_count = 0;
		$IRhtml = "";
		foreach ( $IR_data as $IR_data ) 
		{
			$IR_path = trim($IR_data->IR_path);
			$IR_link = trim($IR_data->IR_link);
			$IR_target = trim($IR_data->IR_target);
			$IR_title = trim($IR_data->IR_title);
			$IR_desc = trim($IR_data->IR_desc);
			
			if(is_numeric($IR_TextLength))
			{
				if($IR_TextLength <> "" && $IR_TextLength > 0 )
				{
					$IR_desc = substr($IR_desc, 0, $IR_TextLength);
				}
			}
			
			$IR_Heights = $IR_Height."px";	
			
			$IRhtml = $IRhtml . "<div class='IR_div' style='height:$IR_Heights;padding:1px 0px 1px 0px;'>"; 
			
			if($IR_path <> "" )
			{
				$IRhtml = $IRhtml . "<div class='IR-regimage'>"; 
				$IRjsjs = "<div class=\'IR-regimage\'>"; 
				if($IR_link <> "" ) 
				{ 
					$IRhtml = $IRhtml . "<a target='$IR_target' href='$IR_link'>"; 
					$IRjsjs = $IRjsjs . "<a target=\'$IR_target\' href=\'$IR_link\'>";
				} 
				$IRhtml = $IRhtml . "<img src='$IR_path' al='' />"; 
				$IRjsjs = $IRjsjs . "<img src=\'$IR_path\' al=\'\' />";
				if($IR_link <> "" ) 
				{ 
					$IRhtml = $IRhtml . "</a>"; 
					$IRjsjs = $IRjsjs . "</a>";
				}
				$IRhtml = $IRhtml . "</div>";
				$IRjsjs = $IRjsjs . "</div>";
			}
			
			if($IR_title <> "" )
			{
				$IRhtml = $IRhtml . "<div style='padding-left:4px;'><strong>";	
				$IRjsjs = $IRjsjs . "<div style=\'padding-left:4px;\'><strong>";				
				if($IR_link <> "" ) 
				{ 
					$IRhtml = $IRhtml . "<a target='$IR_target' href='$IR_link'>"; 
					$IRjsjs = $IRjsjs . "<a target=\'$IR_target\' href=\'$IR_link\'>";
				} 
				$IRhtml = $IRhtml . $IR_title;
				$IRjsjs = $IRjsjs . $IR_title;
				if($IR_link <> "" ) 
				{ 
					$IRhtml = $IRhtml . "</a>"; 
					$IRjsjs = $IRjsjs . "</a>";
				}
				$IRhtml = $IRhtml . "</strong></div>";
				$IRjsjs = $IRjsjs . "</strong></div>";
			}
			
			if($IR_desc <> "" )
			{
				$IRhtml = $IRhtml . "<div style='padding-left:4px;'>$IR_desc</div>";	
				$IRjsjs = $IRjsjs . "<div style=\'padding-left:4px;\'>$IR_desc</div>";	
			}
			
			$IRhtml = $IRhtml . "</div>";
			
			
			$IR_x = $IR_x . "IR[$IR_count] = '<div class=\'IR_div\' style=\'height:$IR_Heights;padding:1px 0px 1px 0px;\'>$IRjsjs</div>'; ";	
			$IR_count++;
		}
		
		$IR_Height = $IR_Height + 4;
		if($IR_count >= $IR_SameTime)
		{
			$IR_count = $IR_SameTime;
			$IR_Height_New = ($IR_Height * $IR_SameTime);
		}
		else
		{
			$IR_count = $IR_count;
			$IR_Height_New = ($IR_count  * $IR_Height);
		}
		?>
<div style="padding-top:8px;padding-bottom:8px;">
  <div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 3px; height: <?php echo @$IR_Height; ?>px;" id="IRHolder"> <?php echo $IRhtml; ?> </div>
</div>
<script type="text/javascript">
		var IR	= new Array();
		var objIR	= '';
		var IR_scrollPos 	= '';
		var IR_numScrolls	= '';
		var IR_heightOfElm = '<?php echo $IR_Height; ?>'; // Height of each element (px)
		var IR_numberOfElm = '<?php echo $IR_count; ?>';
		var IR_scrollOn 	= 'true';
		function createIRScroll() 
		{
			<?php echo $IR_x; ?>
			objIR	= document.getElementById('IRHolder');
			objIR.style.height = (IR_numberOfElm * IR_heightOfElm) + 'px'; // Set height of DIV
			IRContent();
		}
		</script> 
<script type="text/javascript">
		createIRScroll();
		</script>
<?php
	}
	else
	{
		echo "<div style='padding-bottom:5px;padding-top:5px;'>No data available! Please check widget setting.</div>";
	}
}

function IR_Install() 
{
	global $wpdb;

	if($wpdb->get_var("show tables like '". WP_IR_TABLE . "'") != WP_IR_TABLE) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". WP_IR_TABLE . "` (";
		$sSql = $sSql . "`IR_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`IR_path` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`IR_link` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`IR_target` VARCHAR( 50 ) NOT NULL ,";
		$sSql = $sSql . "`IR_title` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "`IR_desc` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`IR_order` INT NOT NULL ,";
		$sSql = $sSql . "`IR_status` VARCHAR( 10 ) NOT NULL ,";
		$sSql = $sSql . "`IR_type` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`IR_date` INT NOT NULL ,";
		$sSql = $sSql . "PRIMARY KEY ( `IR_id` )";
		$sSql = $sSql . ")";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_IR_TABLE . "` (`IR_path`, `IR_link`, `IR_target` , `IR_title` , `IR_desc` , `IR_order` , `IR_status` , `IR_type` , `IR_date`)"; 
		$sSql = $sSql . "VALUES ('".get_option('siteurl')."/wp-content/plugins/information-reel/images/sing_1.jpg','#','_self','Lorem Ipsum is simply.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.','1', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_IR_TABLE . "` (`IR_path`, `IR_link`, `IR_target` , `IR_title` , `IR_desc` , `IR_order` , `IR_status` , `IR_type` , `IR_date`)"; 
		$sSql = $sSql . "VALUES ('".get_option('siteurl')."/wp-content/plugins/information-reel/images/sing_2.jpg','#','_blank','Lorem Ipsum is simply.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.','2', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_IR_TABLE . "` (`IR_path`, `IR_link`, `IR_target` , `IR_title` , `IR_desc` , `IR_order` , `IR_status` , `IR_type` , `IR_date`)"; 
		$sSql = $sSql . "VALUES ('".get_option('siteurl')."/wp-content/plugins/information-reel/images/sing_3.jpg','#','_self','Lorem Ipsum is simply.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.','3', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_IR_TABLE . "` (`IR_path`, `IR_link`, `IR_target` , `IR_title` , `IR_desc` , `IR_order` , `IR_status` , `IR_type` , `IR_date`)"; 
		$sSql = $sSql . "VALUES ('".get_option('siteurl')."/wp-content/plugins/information-reel/images/sing_4.jpg','#','_self','Lorem Ipsum is simply.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.','4', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
	}
	
	add_option('IR_Title', "Information Reel");
	add_option('IR_Height', "160");
	add_option('IR_SameTime', "3");
	add_option('IR_TextLength', "125");
	add_option('IR_type', "widget");
	add_option('IR_random', "YES");
}

function IR_Admin_Options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-management-edit.php');
			break;
		case 'add':
			include('pages/content-management-add.php');
			break;
		case 'set':
			include('pages/content-setting.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

function IR_Add_To_Menu() 
{
	add_options_page('Information Reel', 'Information Reel', 'manage_options', 'information-reel', 'IR_Admin_Options' );
}

function IR_Deactivation() 
{
	// No action required.
}

function IR_Uninstall()
{
	global $wpdb;
	delete_option('IR_Title');
	delete_option('IR_Height');
	delete_option('IR_SameTime');
	delete_option('IR_TextLength');
	delete_option('IR_type');
	delete_option('IR_random');
	if($wpdb->get_var("show tables like '". WP_IR_TABLE . "'") == WP_IR_TABLE) 
	{
		$wpdb->query("DROP TABLE ". WP_IR_TABLE);
	}
}

if (is_admin()) 
{
	add_action('admin_menu', 'IR_Add_To_Menu');
}

function IR_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'information-reel', get_option('siteurl').'/wp-content/plugins/information-reel/information-reel.js');
	}	
}

class IR_widget_register extends WP_Widget 
{
	function __construct() 
	{
		$widget_ops = array('classname' => 'ir_widget', 'description' => __('Information Reel'), 'information-reel');
		parent::__construct('InformationReel', __('Information Reel', 'information-reel'), $widget_ops);
	}
	
	function widget( $args, $instance ) 
	{
		extract( $args, EXTR_SKIP );
		$IR_Title 		= apply_filters( 'widget_title', empty( $instance['IR_Title'] ) ? '' : $instance['IR_Title'], $instance, $this->id_base );
		$IR_Height		= $instance['IR_Height'];
		$IR_SameTime	= $instance['IR_SameTime'];
		$IR_TextLength	= $instance['IR_TextLength'];
		$IR_type		= $instance['IR_type'];
		$IR_random		= $instance['IR_random'];
		
		echo $args['before_widget'];
		if ( ! empty( $IR_Title ) )
		{
			echo $args['before_title'] . $IR_Title . $args['after_title'];
		}
		
		// Call widget method
		$arr = array();
		$arr["IR_TextLength"] = $IR_TextLength;
		$arr["IR_SameTime"] = $IR_SameTime;
		$arr["IR_Height"] = $IR_Height;
		$arr["IR_type"] = $IR_type;
		$arr["IR_random"] = $IR_random;
		IR_Show_Widget($arr);
		// Call widget method
		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) 
	{
		$instance 					= $old_instance;
		$instance['IR_Title'] 		= ( ! empty( $new_instance['IR_Title'] ) ) ? strip_tags( $new_instance['IR_Title'] ) : '';
		$instance['IR_Height'] 		= ( ! empty( $new_instance['IR_Height'] ) ) ? strip_tags( $new_instance['IR_Height'] ) : '';
		$instance['IR_SameTime'] 	= ( ! empty( $new_instance['IR_SameTime'] ) ) ? strip_tags( $new_instance['IR_SameTime'] ) : '';
		$instance['IR_TextLength'] 	= ( ! empty( $new_instance['IR_TextLength'] ) ) ? strip_tags( $new_instance['IR_TextLength'] ) : '';
		$instance['IR_type'] 		= ( ! empty( $new_instance['IR_type'] ) ) ? strip_tags( $new_instance['IR_type'] ) : '';
		$instance['IR_random'] 		= ( ! empty( $new_instance['IR_random'] ) ) ? strip_tags( $new_instance['IR_random'] ) : '';
		return $instance;
	}
	
	function form( $instance ) 
	{
		$defaults = array(
			'IR_Title' 		=> '',
            'IR_Height' 	=> '',
            'IR_SameTime' 	=> '',
            'IR_TextLength' => '',
			'IR_type' 		=> '',
			'IR_random' 	=> ''
        );
		$instance 		= wp_parse_args( (array) $instance, $defaults);
        $IR_Title 		= $instance['IR_Title'];
        $IR_Height 		= $instance['IR_Height'];
        $IR_SameTime 	= $instance['IR_SameTime'];
        $IR_TextLength 	= $instance['IR_TextLength'];
		$IR_type 		= $instance['IR_type'];
		$IR_random 		= $instance['IR_random'];
		?>
		<p>
            <label for="<?php echo $this->get_field_id('IR_Title'); ?>"><?php _e('Title', 'information-reel'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('IR_Title'); ?>" name="<?php echo $this->get_field_name('IR_Title'); ?>" type="text" value="<?php echo $IR_Title; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('IR_Height'); ?>"><?php _e('Height', 'information-reel'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('IR_Height'); ?>" name="<?php echo $this->get_field_name('IR_Height'); ?>" type="text" value="<?php echo $IR_Height; ?>" maxlength="3" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('IR_SameTime'); ?>"><?php _e('Same time display', 'information-reel'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('IR_SameTime'); ?>" name="<?php echo $this->get_field_name('IR_SameTime'); ?>" type="text" value="<?php echo $IR_SameTime; ?>" maxlength="3" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('IR_TextLength'); ?>"><?php _e('Text length', 'information-reel'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('IR_TextLength'); ?>" name="<?php echo $this->get_field_name('IR_TextLength'); ?>" type="text" value="<?php echo $IR_TextLength; ?>" maxlength="3" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('IR_type'); ?>"><?php _e('Content group', 'information-reel'); ?></label>
			<select class="" id="<?php echo $this->get_field_id('IR_type'); ?>" name="<?php echo $this->get_field_name('IR_type'); ?>" style="width:130px;">
				<option value="">Select</option>
				<?php
				$arrData = array();
				$arrData = $this->IR_loadtype();
				if(count($arrData) > 0)
				{
					foreach ($arrData as $arrData)
					{
						?><option value="<?php echo $arrData["IR_type"]; ?>" <?php $this->IR_render_selected($arrData["IR_type"] == $IR_type); ?>><?php echo $arrData["IR_type"]; ?></option><?php
					}
				}
				?>
			</select>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('IR_random'); ?>"><?php _e('Random order', 'information-reel'); ?></label>
			<select class="" id="<?php echo $this->get_field_id('IR_random'); ?>" name="<?php echo $this->get_field_name('IR_random'); ?>" style="width:130px;">
				<option value="">Select</option>
				<option value="YES" <?php $this->IR_render_selected($IR_random=='YES'); ?>>YES</option>
				<option value="NO" <?php $this->IR_render_selected($IR_random=='NO'); ?>>NO</option>
			</select>
        </p>
		<p>For more information <a target="_blank" href="<?php echo WP_IR_FAV; ?>">click here</a></p>
		<?php
	}
	
	function IR_render_selected($var) 
	{
		if ($var==1 || $var==true) 
		{
			echo 'selected="selected"';
		}
	}
	
	function IR_loadtype() 
	{
		global $wpdb;
		$arrData = array();
		$sSql 	 = "SELECT distinct(IR_type) as IR_type FROM ".WP_IR_TABLE." order by IR_type";
		$myData  = $wpdb->get_results($sSql, ARRAY_A);
		$i 		 = 0;
		if(count($myData) > 0 )
		{
			foreach ($myData as $data)
			{
				$arrData[$i]["IR_type"] = stripslashes($data['IR_type']);
				$i=$i+1;
			}
		}
		return $arrData;
	}
}

function IR_widget_loading()
{
	register_widget( 'IR_widget_register' );
}

add_action('init', 'IR_add_javascript_files');
add_action( 'widgets_init', 'IR_widget_loading');
register_activation_hook(__FILE__, 'IR_Install');
register_deactivation_hook(__FILE__, 'IR_Deactivation');
register_uninstall_hook(__FILE__, 'IR_Uninstall' );
?>