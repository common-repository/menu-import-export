<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Menu_Import_Export
 * @subpackage Menu_Import_Export/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="modal" id="menuImportExport"  data-easein="flipXIn" tabindex="3" role="dialog" aria-labelledby="menuImportExportTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?php esc_html_e('Export or Import Menus', 'different-menu'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <button type="button" class="btn btn-success btn-export_menus" title=" Click here to export current menus. "><?php esc_html_e('Export Menus', 'different-menu'); ?></button>
        <span class="description"><?php esc_html_e('Click here to export current menus.', 'different-menu'); ?></span>

		<br>
		<br>
		<br>
        

        <input type="file" id="import_menus" accept=".csv">
        <br>
        <span class="description"><?php esc_html_e('Select a backup file to import menus.', 'different-menu'); ?></span><br><br>

        <div class="description text-danger"><span class="font-weight-bold">Please Note:</span> Current menus will be deleted after successfully import. Only active <span class="text_menu_location font-weight-bold" style="cursor: pointer; " title='Only active menus will import' data-content='<span class="description">These are active menu locations. Only these menus will import</span><br><a href="<?php echo plugin_dir_url( __DIR__ ) . 'images/menu-locations.png'; ?>" target="_blank"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'images/menu-locations.png'; ?>" width="250"></a> <span class="description">If you have extra menus without set any menu location then these will not imported. <a href="https://myrecorp.com/menu-import-export/?p=menu-import-export&clk=wp&a=pro">Go to premium</a> to unlock all features</span>' data-toggle="popover" data-html="true">`Menu Location`</span> menus will be imported in free version. To import all menus (unselected menu location menus) or need more features please upgrade to premium!</div>

			
		<div class="more_plugins pt-3">
			<div class="more_free_plugins">
				<span class="">More free plugins you may like</span>
				<a href="https://wordpress.org/plugins/different-menus-in-different-pages/">Different Menu in Different Pages</a>
			</div>
			
		</div>
        <div class="import_menus_data_as_txt hidden" style="display: none">
        	
        </div>
        
      </div>
      <div class="modal-footer">
      	<span class="go_pro float-left"><a href="https://myrecorp.com/menu-import-export/?p=menu-import-export&clk=wp&a=pro">Go to pro</a></span>

        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php esc_html_e('Close', 'different-menu'); ?></button>
        <button type="button" class="btn btn-primary btn-import_menus"><?php esc_html_e('Import', 'different-menu'); ?></button>
      </div>
    </div>
  </div>
</div>

<script>

	(function ($) {
	  jQuery('.menu-edit .publishing-action').prepend('<button type="button" class="btn btn-success backup-restore" data-toggle="modal" data-target="#menuImportExport" style="font-size: 12px;">Export & Import</button>');

	        jQuery(".modal").each(function (l) {jQuery(this).on("show.bs.modal", function (l) {var o = jQuery(this).attr("data-easein");"shake" == o ? jQuery(".modal-dialog").velocity("callout." + o) : "pulse" == o ? jQuery(".modal-dialog").velocity("callout." + o) : "tada" == o ? jQuery(".modal-dialog").velocity("callout." + o) : "flash" == o ? jQuery(".modal-dialog").velocity("callout." + o) : "bounce" == o ? jQuery(".modal-dialog").velocity("callout." + o) : "swing" == o ? jQuery(".modal-dialog").velocity("callout." + o) : jQuery(".modal-dialog").velocity("transition." + o);});});


	<?php
		if(isset($_GET['success']) && $_GET['success'] == "true" ){
			?>
				$.notify({
					// options
					message: 'Thanks for installing our plugin! If you need support then please contact us on <a href="https://myrecorp.com/?a=popup&p=menu-import-export">myrecorp.com</a>. We will help you immediately. We created some very useful plugins that need almost every users. Please visit our <a href="https://myrecorp.com/?a=popup&p=menu-import-export">site</a> to see. Thanks!' 
				},{
					// settings
					type: 'success',
					placement: {
						from: "top",
						align: "center"
					},
					animate:{
						enter: "animated fadeInDown",
						exit: "animated fadeOutUp"
					},
					delay: 30000
				});

			<?php
		}
	 ?>
	}( jQuery ));
	

</script>