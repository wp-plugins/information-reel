<div class="wrap">
<?php
$IR_errors = array();
$IR_success = '';
$IR_error_found = FALSE;

// Preset the form fields
$form = array(
	'IR_path' => '',
	'IR_link' => '',
	'IR_target' => '',
	'IR_title' => '',
	'IR_desc' => '',
	'IR_order' => '',
	'IR_status' => '',
	'IR_type' => ''
);

// Form submitted, check the data
if (isset($_POST['IR_form_submit']) && $_POST['IR_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('IR_form_add');
	
	$form['IR_path'] = isset($_POST['IR_path']) ? $_POST['IR_path'] : '';
	if ($form['IR_path'] == '')
	{
		$IR_errors[] = __('Please enter the image path.', WP_IR_UNIQUE_NAME);
		$IR_error_found = TRUE;
	}

	$form['IR_link'] = isset($_POST['IR_link']) ? $_POST['IR_link'] : '';
	if ($form['IR_link'] == '')
	{
		$IR_errors[] = __('Please enter the target link.', WP_IR_UNIQUE_NAME);
		$IR_error_found = TRUE;
	}
	
	$form['IR_target'] = isset($_POST['IR_target']) ? $_POST['IR_target'] : '';
	$form['IR_title'] = isset($_POST['IR_title']) ? $_POST['IR_title'] : '';
	$form['IR_desc'] = isset($_POST['IR_desc']) ? $_POST['IR_desc'] : '';
	$form['IR_order'] = isset($_POST['IR_order']) ? $_POST['IR_order'] : '';
	$form['IR_status'] = isset($_POST['IR_status']) ? $_POST['IR_status'] : '';
	$form['IR_type'] = isset($_POST['IR_type']) ? $_POST['IR_type'] : '';

	//	No errors found, we can add this Group to the table
	if ($IR_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".WP_IR_TABLE."`
			(`IR_path`, `IR_link`, `IR_target`, `IR_title`, `IR_desc`, `IR_order`, `IR_status`, `IR_type`)
			VALUES(%s, %s, %s, %s, %s, %d, %s, %s)",
			array($form['IR_path'], $form['IR_link'], $form['IR_target'], $form['IR_title'], $form['IR_desc'], $form['IR_order'], $form['IR_status'], $form['IR_type'])
		);
		$wpdb->query($sql);
		
		$IR_success = __('Details was successfully added.', WP_IR_UNIQUE_NAME);
		
		// Reset the form fields
		$form = array(
			'IR_path' => '',
			'IR_link' => '',
			'IR_target' => '',
			'IR_title' => '',
			'IR_desc' => '',
			'IR_order' => '',
			'IR_status' => '',
			'IR_type' => ''
		);
	}
}

if ($IR_error_found == TRUE && isset($IR_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $IR_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($IR_error_found == FALSE && strlen($IR_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $IR_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=information-reel">Click here</a> to view the details</strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/information-reel/pages/information-reel-setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo WP_IR_TITLE; ?></h2>
	<form name="IR_form" method="post" action="#" onsubmit="return IR_submit()"  >
      <h3>Add new content</h3>
      <label for="tag-image">Enter image path</label>
      <input name="IR_path" type="text" id="IR_path" value="" size="125" maxlength="1024" />
      <p>Where is the picture located on the internet (ex: http://www.gopiplus.com/work/wp-content/uploads/pluginimages/88x88/1.jpg)</p>
      <label for="tag-link">Enter target link</label>
      <input name="IR_link" type="text" id="IR_link" value="" size="125" maxlength="1024" />
      <p>When someone clicks on the picture, where do you want to send them</p>
      <label for="tag-target">Enter target option</label>
      <select name="IR_target" id="IR_target">
        <option value='_blank'>_blank</option>
        <option value='_parent'>_parent</option>
        <option value='_self'>_self</option>
        <option value='_new'>_new</option>
      </select>
      <p>Do you want to open link in new window?</p>
      <label for="tag-title">Enter title</label>
      <input name="IR_title" type="text" id="IR_title" value="" size="125" maxlength="200" />
      <p>Enter reel title in this box.</p>
	  <label for="tag-title">Enter description</label>
      <input name="IR_desc" type="text" id="IR_desc" value="" size="125" maxlength="1024" />
      <p>Enter reel content in this box.</p>
      <label for="tag-select-gallery-group">Select content group/type</label>
      <select name="IR_type" id="IR_type">
        <option value='Select'>Select</option>
	    <?php
		$sSql = "SELECT distinct(IR_type) as IR_type FROM `".WP_IR_TABLE."` order by IR_type";
		echo $sSql;
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
			?><option value='<?php echo $arrDistinct["IR_type"]; ?>'><?php echo $arrDistinct["IR_type"]; ?></option><?php
		}
		?>
      </select>
      <p>This is to group the content. Select your option to group the content. </p>
      <label for="tag-display-status">Display status</label>
      <select name="IR_status" id="IR_status">
        <option value='YES'>Yes</option>
        <option value='NO'>No</option>
      </select>
      <p>Do you want the content to show in your reel?</p>
      <label for="tag-display-order">Display order</label>
      <input name="IR_order" type="text" id="IR_order" size="10" value="" maxlength="3" />
      <p>Content display order in the reel. should it come 1st, 2nd, 3rd, etc.</p>
      <input name="IR_id" id="IR_id" type="hidden" value="">
      <input type="hidden" name="IR_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="Insert Details" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="IR_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="IR_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('IR_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo WP_IR_LINK; ?></p>
</div>