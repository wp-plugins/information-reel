<?php

/*
Plugin Name: Information Reel
Plugin URI: http://www.gopiplus.com/work/2011/04/16/wordpress-plugin-information-reel/
Description: This plugin scroll the entered title, image, and description in your word press website. This is best way to announce your message to user. Live demo availabe in the plugin site.
Author: Gopi.R
Version: 7.0
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
define('WP_IR_LINK', 'Check official website for more information <a target="_blank" href="http://www.gopiplus.com/work/2011/04/16/wordpress-plugin-information-reel/">click here</a>');
define('WP_IR_FAV', 'http://www.gopiplus.com/work/2011/04/16/wordpress-plugin-information-reel/');

function IR_Show() 
{
	global $wpdb;
	$IRhtml = "";
	$IRjsjs = "";
	$IR_x = "";
	$IR_Height = "";
	
	$IR_TextLength = get_option('IR_TextLength');
	$IR_SameTime = get_option('IR_SameTime');
	$IR_Height = get_option('IR_Height');
	$IR_type = get_option('IR_type');
	$IR_random = get_option('IR_random');
	
	if(!is_numeric($IR_SameTime)){ $IR_SameTime = 5; }
	if(!is_numeric($IR_Height)){ $IR_Height = 50; }
	if($IR_type == "" ) { $IR_type="widget"; }

	$sSql = "select IR_path,IR_link,IR_target,IR_title,IR_desc from ".WP_IR_TABLE." where 1=1 and IR_status='YES'";
	$sSql = $sSql . " and IR_type='".$IR_type."'";
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
			$IR_path = mysql_real_escape_string(trim($IR_data->IR_path));
			$IR_link = mysql_real_escape_string(trim($IR_data->IR_link));
			$IR_target = mysql_real_escape_string(trim($IR_data->IR_target));
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
					$IRhtml = $IRhtml . "<a href='$IR_link'>"; 
					$IRjsjs = $IRjsjs . "<a href=\'$IR_link\'>";
				} 
				$IRhtml = $IRhtml . "<img src='$IR_path' al='Test' />"; 
				$IRjsjs = $IRjsjs . "<img src=\'$IR_path\' al=\'Test\' />";
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
					$IRhtml = $IRhtml . "<a href='$IR_link'>"; 
					$IRjsjs = $IRjsjs . "<a href=\'$IR_link\'>";
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
  <div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 3px; height: <?php echo @$IR_height; ?>px;" id="IRHolder"> <?php echo $IRhtml; ?> </div>
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

function IR_Control() 
{
	global $wpdb;
	$IR_Title = get_option('IR_Title');
	$IR_Height = get_option('IR_Height');
	$IR_SameTime = get_option('IR_SameTime');
	$IR_TextLength = get_option('IR_TextLength');
	$IR_type = get_option('IR_type');
	$IR_random = get_option('IR_random');
	
	if (@$_POST['IR_submit']) 
	{
		
		//	Just security thingy that wordpress offers us
		check_admin_referer('IR_form_show');
			
		$IR_Title = stripslashes($_POST['IR_Title']);
		$IR_Height = stripslashes($_POST['IR_Height']);
		$IR_SameTime = stripslashes($_POST['IR_SameTime']);
		$IR_TextLength = stripslashes($_POST['IR_TextLength']);
		$IR_type = stripslashes($_POST['IR_type']);
		$IR_random = stripslashes($_POST['IR_random']);
		
		update_option('IR_Title', $IR_Title );
		update_option('IR_Height', $IR_Height );
		update_option('IR_SameTime', $IR_SameTime );
		update_option('IR_TextLength', $IR_TextLength );
		update_option('IR_type', $IR_type );
		update_option('IR_random', $IR_random );
		
	}
	
	echo '<p>Title:<br><input  style="width: 200px;" type="text" value="';
	echo $IR_Title . '" name="IR_Title" id="IR_Title" /></p>';
	
	echo '<p>Height:<br><input  style="width: 100px;" type="text" value="';
	echo $IR_Height . '" name="IR_Height" id="IR_Height"  maxlength="3" /> (Only number)</p>';
	
	echo '<p>Same Time Display:<br><input  style="width: 100px;" type="text" value="';
	echo $IR_SameTime . '" name="IR_SameTime" id="IR_SameTime" maxlength="2"  /> (Only number)</p>';
	
	echo '<p>Text Length:<br><input  style="width: 100px;" type="text" value="';
	echo $IR_TextLength . '" name="IR_TextLength" id="IR_TextLength" maxlength="3"  /> (Only number)</p>';
	
	echo '<p>Gallery Type:<br>';
	
	?>
	<select name="IR_type" id="IR_type">
		<?php
		$sSql = "SELECT distinct(IR_type) as IR_type FROM `".WP_IR_TABLE."` order by IR_type";
		$myDistinctData = array();
		$arrDistinctDatas = array();
		$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
		$i = 0;
		foreach ($myDistinctData as $DistinctData)
		{
			$arrDistinctData[$i]["IR_type"] = strtoupper($DistinctData['IR_type']);
			$i = $i+1;
		}
		for($j=$i; $j<$i+5; $j++)
		{
			$arrDistinctData[$j]["IR_type"] = "GROUP" . $j;
		}
		$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
		foreach ($arrDistinctDatas as $arrDistinct)
		{
			if(strtoupper($IR_type) == strtoupper($arrDistinct["IR_type"]) ) 
			{ 
				$selected = "selected='selected'"; 
			}
			?>
			<option value='<?php echo $arrDistinct["IR_type"]; ?>' <?php echo $selected; ?>><?php echo strtoupper($arrDistinct["IR_type"]); ?></option>
			<?php
			$selected = "";
		}
		?>
     </select>
	 </p>
	<?php
	wp_nonce_field('IR_form_show');
	echo '<p>Random Option:<br><input  style="width: 100px;" type="text" value="';
	echo $IR_random . '" name="IR_random" id="IR_random"  maxlength="3"  /> (YES/NO)</p>';
	
	echo '<input type="hidden" id="IR_submit" name="IR_submit" value="1" />';
}

function IR_Widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('IR_Title');
	echo $after_title;
	IR_Show();
	echo $after_widget;
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

function IR_Init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Information-Reel', 'Information Reel', 'IR_Widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('Information-Reel', array('Information Reel', 'widgets'), 'IR_Control');
	} 
}

function IR_Deactivation() 
{
	// No action required.
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

add_action('init', 'IR_add_javascript_files');
add_action("plugins_loaded", "IR_Init");
register_activation_hook(__FILE__, 'IR_Install');
register_deactivation_hook(__FILE__, 'IR_Deactivation');
?>