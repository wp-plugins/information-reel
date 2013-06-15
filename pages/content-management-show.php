<?php
// Form submitted, check the data
if (isset($_POST['frm_IR_display']) && $_POST['frm_IR_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$IR_success = '';
	$IR_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_IR_TABLE."
		WHERE `IR_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('IR_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_IR_TABLE."`
					WHERE `IR_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$IR_success_msg = TRUE;
			$IR_success = __('Selected record was successfully deleted.', WP_IR_UNIQUE_NAME);
		}
	}
	
	if ($IR_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $IR_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php echo WP_IR_TITLE; ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=information-reel&amp;ac=add">Add New</a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_IR_TABLE."` order by IR_type, IR_order";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/information-reel/pages/information-reel-setting.js"></script>
		<form name="frm_IR_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="row"><input type="checkbox" name="IR_group_item[]" /></th>
			<th scope="col">Title</th>
			<th scope="col">Description</th>
			<th scope="col">Type</th>
			<th scope="col">Status</th>
			<th scope="col">Order</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="row"><input type="checkbox" name="IR_group_item[]" /></th>
			<th scope="col">Title</th>
			<th scope="col">Description</th>
			<th scope="col">Type</th>
			<th scope="col">Status</th>
			<th scope="col">Order</th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			$displayisthere = FALSE;
			foreach ($myData as $data)
			{
				if(strtoupper($data['IR_status']) == 'YES') 
				{
					$displayisthere = TRUE; 
				}
				$IR_desc = substr(esc_html(stripslashes($data['IR_desc'])), 0, 100);
				?>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<td align="left"><input type="checkbox" value="<?php echo $data['IR_id']; ?>" name="IR_group_item[]"></th>
					<td>
					<?php echo esc_html(stripslashes($data['IR_title'])); ?>
					<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=information-reel&amp;ac=edit&amp;did=<?php echo $data['IR_id']; ?>">Edit</a> | </span>
						<span class="trash"><a onClick="javascript:IR_delete('<?php echo $data['IR_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
					</div>
					</td>
					<td><?php echo $IR_desc; ?>...</td>
					<td><?php echo $data['IR_type']; ?></td>
					<td><?php echo $data['IR_status']; ?></td>
					<td><?php echo $data['IR_order']; ?></td>
				</tr>
				<?php 
				$i = $i+1; 
				} 
			?>
			<?php 
			if ($displayisthere == FALSE) 
			{ 
				?><tr><td colspan="6" align="center">No records available.</td></tr><?php 
			} 
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('IR_form_show'); ?>
		<input type="hidden" name="frm_IR_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=information-reel&amp;ac=add">Add New</a>
	  <!--<a class="button add-new-h2" href="<?php //echo get_option('siteurl'); ?>/wp-admin/admin.php?page=information-reel&amp;ac=set">Widget setting</a>-->
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_IR_FAV; ?>">Help</a>
	  </h2>
	  </div>
	  <br />
	<h3>Plugin configuration option</h3>
	<ul>
		<li>Go to widget link under Appearance tab, Drag and drop <b>Information Reel</b> into your side bar.</li>
	</ul>
	  <p class="description"><?php echo WP_IR_LINK; ?></p>
	</div>
</div>