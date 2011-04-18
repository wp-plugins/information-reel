<?php

/*
Plugin Name: Information Reel
Plugin URI: http://www.gopiplus.com/work/2011/04/16/wordpress-plugin-information-reel/
Description: This plugin scroll the entered title, image, and description in your word press website. This is best way to announce your message to user. Live demo availabe in the plugin site.
Author: Gopi.R
Version: 1.0
Author URI: http://www.gopiplus.com/work/
Donate link: http://www.gopiplus.com/work/2011/04/16/wordpress-plugin-information-reel/
Tags: Announcement, Scroller, Message, Scroll, Text scroll, News
*/


global $wpdb, $wp_version;
define("WP_IR_TABLE", $wpdb->prefix . "information_reel");

function IR_Show() 
{
	global $wpdb;
	
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
			$IR_title = mysql_real_escape_string(trim($IR_data->IR_title));
			$IR_desc = mysql_real_escape_string(trim($IR_data->IR_desc));
			
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
  <div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 3px; height: <?php echo $IR_height; ?>px;" id="IRHolder"> <?php echo $IRhtml; ?> </div>
</div>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/information-reel/information-reel.js"></script> 
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
		echo "<div style='padding-bottom:5px;padding-top:5px;'>No data available!</div>";
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
		$sSql = $sSql . "VALUES ('http://www.gopiplus.com/work/wp-content/uploads/pluginimages/100x100/100x100_1.jpg','http://www.gopiplus.com/work/','_blank','Gopiplus.com','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.','1', 'YES', 'widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_IR_TABLE . "` (`IR_path`, `IR_link`, `IR_target` , `IR_title` , `IR_desc` , `IR_order` , `IR_status` , `IR_type` , `IR_date`)"; 
		$sSql = $sSql . "VALUES ('http://www.gopiplus.com/work/wp-content/uploads/pluginimages/100x100/100x100_2.jpg','http://www.gopiplus.com/work/','_blank','Gopiplus.com','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.','2', 'YES', 'widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_IR_TABLE . "` (`IR_path`, `IR_link`, `IR_target` , `IR_title` , `IR_desc` , `IR_order` , `IR_status` , `IR_type` , `IR_date`)"; 
		$sSql = $sSql . "VALUES ('http://www.gopiplus.com/work/wp-content/uploads/pluginimages/100x100/100x100_3.jpg','http://www.gopiplus.com/work/','_blank','Gopiplus.com','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.','3', 'YES', 'widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_IR_TABLE . "` (`IR_path`, `IR_link`, `IR_target` , `IR_title` , `IR_desc` , `IR_order` , `IR_status` , `IR_type` , `IR_date`)"; 
		$sSql = $sSql . "VALUES ('http://www.gopiplus.com/work/wp-content/uploads/pluginimages/100x100/100x100_4.jpg','http://www.gopiplus.com/work/','_blank','Gopiplus.com','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.','4', 'YES', 'widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
	}
	
	add_option('IR_Title', "Information Reel");
	add_option('IR_Height', "140");
	add_option('IR_SameTime', "3");
	add_option('IR_TextLength', "125");
	add_option('IR_type', "widget");
	add_option('IR_random', "YES");
}

function IR_Control() 
{
	$IR_Title = get_option('IR_Title');
	$IR_Height = get_option('IR_Height');
	$IR_SameTime = get_option('IR_SameTime');
	$IR_TextLength = get_option('IR_TextLength');
	$IR_type = get_option('IR_type');
	$IR_random = get_option('IR_random');
	
	if ($_POST['IR_submit']) 
	{
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
	echo $IR_Height . '" name="IR_Height" id="IR_Height" /> (YES/NO)</p>';
	
	echo '<p>Same Time Display:<br><input  style="width: 100px;" type="text" value="';
	echo $IR_SameTime . '" name="IR_SameTime" id="IR_SameTime" /> (YES/NO)</p>';
	
	echo '<p>Text Length:<br><input  style="width: 100px;" type="text" value="';
	echo $IR_TextLength . '" name="IR_TextLength" id="IR_TextLength" /> (YES/NO)</p>';
	
	echo '<p>Fallery Group:<br><input  style="width: 100px;" type="text" value="';
	echo $IR_type . '" name="IR_type" id="IR_type" /> </p>';
	
	echo '<p>Random Option:<br><input  style="width: 100px;" type="text" value="';
	echo $IR_random . '" name="IR_random" id="IR_random" /> (YES/NO)</p>';
	
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
	?>
<div class="wrap">
  <?php
  	global $wpdb;
    $title = __('Information Reel');
    $mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=information-reel/information-reel.php";
    $DID=@$_GET["DID"];
    $AC=@$_GET["AC"];
    $submittext = "Insert Message";
	if($AC <> "DEL" and trim($_POST['IR_link']) <>"")
    {
			if($_POST['IR_id'] == "" )
			{
					$sql = "insert into ".WP_IR_TABLE.""
					. " set `IR_path` = '" . mysql_real_escape_string(trim($_POST['IR_path']))
					. "', `IR_link` = '" . mysql_real_escape_string(trim($_POST['IR_link']))
					. "', `IR_target` = '" . mysql_real_escape_string(trim($_POST['IR_target']))
					. "', `IR_title` = '" . mysql_real_escape_string(trim($_POST['IR_title']))
					. "', `IR_desc` = '" . mysql_real_escape_string(trim($_POST['IR_desc']))
					. "', `IR_order` = '" . mysql_real_escape_string(trim($_POST['IR_order']))
					. "', `IR_status` = '" . mysql_real_escape_string(trim($_POST['IR_status']))
					. "', `IR_type` = '" . mysql_real_escape_string(trim($_POST['IR_type']))
					. "'";	
			}
			else
			{
					$sql = "update ".WP_IR_TABLE.""
					. " set `IR_path` = '" . mysql_real_escape_string(trim($_POST['IR_path']))
					. "', `IR_link` = '" . mysql_real_escape_string(trim($_POST['IR_link']))
					. "', `IR_target` = '" . mysql_real_escape_string(trim($_POST['IR_target']))
					. "', `IR_title` = '" . mysql_real_escape_string(trim($_POST['IR_title']))
					. "', `IR_desc` = '" . mysql_real_escape_string(trim($_POST['IR_desc']))
					. "', `IR_order` = '" . mysql_real_escape_string(trim($_POST['IR_order']))
					. "', `IR_status` = '" . mysql_real_escape_string(trim($_POST['IR_status']))
					. "', `IR_type` = '" . mysql_real_escape_string(trim($_POST['IR_type']))
					. "' where `IR_id` = '" . $_POST['IR_id'] 
					. "'";	
			}
			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".WP_IR_TABLE." where IR_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".WP_IR_TABLE." where IR_id=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) $IR_id_x = htmlspecialchars(stripslashes($data->IR_id)); 
		if ( !empty($data) ) $IR_path_x = htmlspecialchars(stripslashes($data->IR_path)); 
        if ( !empty($data) ) $IR_link_x = htmlspecialchars(stripslashes($data->IR_link));
		if ( !empty($data) ) $IR_target_x = htmlspecialchars(stripslashes($data->IR_target));
        if ( !empty($data) ) $IR_title_x = htmlspecialchars(stripslashes($data->IR_title));
		if ( !empty($data) ) $IR_desc_x = htmlspecialchars(stripslashes($data->IR_desc));
		if ( !empty($data) ) $IR_order_x = htmlspecialchars(stripslashes($data->IR_order));
		if ( !empty($data) ) $IR_status_x = htmlspecialchars(stripslashes($data->IR_status));
		if ( !empty($data) ) $IR_type_x = htmlspecialchars(stripslashes($data->IR_type));
        $submittext = "Update Message";
    }
    ?>
  <h2><?php echo wp_specialchars( $title ); ?></h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/information-reel/information-reel-setting.js"></script>
  <form name="IR_form" method="post" action="<?php echo $mainurl; ?>" onsubmit="return IR_submit()"  >
    <table width="100%">
      <tr>
        <td colspan="2" align="left" valign="middle">Enter image url:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="IR_path" type="text" id="IR_path" value="<?php echo $IR_path_x; ?>" size="125" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter target link:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="IR_link" type="text" id="IR_link" value="<?php echo $IR_link_x; ?>" size="125" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter target option:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="IR_target" type="text" id="IR_target" value="<?php echo $IR_target_x; ?>" size="50" />
          ( _blank, _parent, _self, _new )</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter image title:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="IR_title" type="text" id="IR_title" value="<?php echo $IR_title_x; ?>" size="125" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter image description:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="IR_desc" type="text" id="IR_desc" value="<?php echo $IR_desc_x; ?>" size="125" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter gallery type (This is to group the images):</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="IR_type" type="text" id="IR_type" value="<?php echo $IR_type_x; ?>" size="50" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle">Display Status:</td>
        <td align="left" valign="middle">Display Order:</td>
      </tr>
      <tr>
        <td width="22%" align="left" valign="middle"><select name="IR_status" id="IR_status">
            <option value="">Select</option>
            <option value='YES' <?php if($IR_status_x=='YES') { echo 'selected' ; } ?>>Yes</option>
            <option value='NO' <?php if($IR_status_x=='NO') { echo 'selected' ; } ?>>No</option>
          </select></td>
        <td width="78%" align="left" valign="middle"><input name="IR_order" type="text" id="IR_rder" size="10" value="<?php echo $IR_order_x; ?>" maxlength="3" /></td>
      </tr>
      <tr>
        <td height="35" colspan="2" align="left" valign="bottom"><table width="100%">
            <tr>
              <td width="50%" align="left"><input name="publish" lang="publish" class="button-primary" value="<?php echo $submittext?>" type="submit" />
                <input name="publish" lang="publish" class="button-primary" onclick="IR_redirect()" value="Cancel" type="button" /></td>
              <td width="50%" align="right"></td>
            </tr>
          </table></td>
      </tr>
      <input name="IR_id" id="IR_id" type="hidden" value="<?php echo $IR_id_x; ?>">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".WP_IR_TABLE." order by IR_type,IR_order");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		return;
	}
	?>
    <form name="frm_IR_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="10%" align="left" scope="col">Type
              </td>
            <th width="52%" align="left" scope="col">Title
              </td>
            <th width="10%" align="left" scope="col">Target
              </td>
            <th width="8%" align="left" scope="col">Order
              </td>
            <th width="7%" align="left" scope="col">Display
              </td>
            <th width="13%" align="left" scope="col">Action
              </td>
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
		if($data->IR_status=='YES') { $displayisthere="True"; }
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->IR_type)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->IR_title)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->IR_target)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->IR_order)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->IR_status)); ?></td>
            <td align="left" valign="middle"><a href="options-general.php?page=information-reel/information-reel.php&DID=<?php echo($data->IR_id); ?>">Edit</a> &nbsp; <a onClick="javascript:IR_delete('<?php echo($data->IR_id); ?>')" href="javascript:void(0);">Delete</a></td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
        <?php if($displayisthere<>"True") { ?>
        <tr>
          <td colspan="6" align="center" style="color:#FF0000" valign="middle">No message available with display status 'Yes'!' </td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
<?php
}

function IR_Add_To_Menu() 
{
	add_options_page('Information Reel', 'Information Reel', 'manage_options', __FILE__, 'IR_Admin_Options' );
}

function IR_Init()
{
	if(function_exists('register_sidebar_widget')) 
	{
		register_sidebar_widget('Information Reel', 'IR_Widget');
	}
	
	if(function_exists('register_widget_control')) 
	{
		register_widget_control(array('Information Reel', 'widgets'), 'IR_Control');
	} 
}

function IR_Deactivation() 
{
	
}

if (is_admin()) 
{
	add_action('admin_menu', 'IR_Add_To_Menu');
}

add_action("plugins_loaded", "IR_Init");
register_activation_hook(__FILE__, 'IR_Install');
register_deactivation_hook(__FILE__, 'IR_Deactivation');
add_action('admin_menu', 'IR_Add_To_Menu');

?>
