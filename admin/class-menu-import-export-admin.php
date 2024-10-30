<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Menu_Import_Export
 * @subpackage Menu_Import_Export/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Menu_Import_Export
 * @subpackage Menu_Import_Export/admin
 * @author     ReCorp <admin@myrecorp.com>
 */
class Menu_Import_Export_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


		/*if (current_user_can('administrator')) {
			add_action('admin_menu', array($this, 'menu_import_export_create_menu') );
		}*/

		include_once(ABSPATH . 'wp-includes/pluggable.php');
		
		if ( current_user_can("administrator") && strpos($_SERVER['PHP_SELF'], "nav-menus.php")) {
			add_action('admin_footer', array($this, 'load_settings_page'));
		}


		add_action('wp_ajax_recorp_export_menus', array( $this, 'recorp_export_menus') );
		add_action('wp_ajax_nopriv_recorp_export_menus', array( $this, 'recorp_export_menus') );


		add_action('wp_ajax_recorp_import_menus', array( $this, 'recorp_import_menus') );
		add_action('wp_ajax_nopriv_recorp_import_menus', array( $this, 'recorp_import_menus') );


		//Adding premium link to plugin action row
		add_filter( 'plugin_row_meta', array( $this, 'custom_plugin_row_meta'), 10, 2 );


	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Menu_Import_Export_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Menu_Import_Export_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/menu-import-export-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'animate', plugin_dir_url( __FILE__ ) . 'css/animate.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'bootstrap-min', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Menu_Import_Export_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Menu_Import_Export_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'bootstrap-min-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery', 'poper-js' ), $this->version, false );

		wp_enqueue_script( 'bootstrap-notify', plugin_dir_url( __FILE__ ) . 'js/bootstrap-notify.js', array( 'jquery', 'bootstrap-min-js' ), $this->version, false );


		wp_enqueue_script( 'poper-js', 'https://unpkg.com/popper.js@1.15.0/dist/umd/popper.min.js', array( 'jquery' ), '1.2.2', true );


		wp_enqueue_script( 'velocity-min', plugin_dir_url( __FILE__ ) . 'js/velocity.min.js', array( 'bootstrap-notify' ), $this->version, false );

		wp_enqueue_script( 'velocity-ui-min', plugin_dir_url( __FILE__ ) . 'js/velocity-ui.min.js', array( 'velocity-min' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/menu-import-export-admin.js', array( 'velocity-ui-min' ), $this->version, false );

			wp_add_inline_script( $this->plugin_name, '
				/* <!\[CDATA\[ */
					var recorp_menu = {"theme_url":"'.  get_stylesheet_directory_uri() .'",
						"members_url":"'.  get_home_url() . '/members' .'",
						"home_url":"'.  get_home_url() .'",
						"ajax_url":"'. get_admin_url() . 'admin-ajax.php' . '",
						"nonce": "'.wp_create_nonce( "recorp_nonce" ).'",
						};
				/* \]\]> */
			');

	}


/*	public function menu_import_export_create_menu(){

		add_submenu_page( 
			'tools.php', 
			'Menu Import and Export - Menu Backup', 
			'Menu Import Export', 
			'manage_options', 
			'menu-import-export', 
			array(
				$this,
				'load_settings_page'
			)
		);

	}
*/

	public function load_settings_page(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/menu-import-export-admin-display.php';

	}



public function custom_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'menu-import-export.php' ) !== false ) {
		$new_links = array(
				'menu_import_export' => '<a style="color: red;font-weight: bold;" href="https://myrecorp.com/product/menu-import-export/?clk=wp" target="_blank">Premium support</a>'
				);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}


public function recorp_export_menus(){

	check_ajax_referer( 'recorp_nonce', 'nonce' );

	$posts = get_posts(
		array(
			'post_type' 		=> 'nav_menu_item', 
			'posts_per_page' 	=> -1, 
			'post_status' 		=> 'publish'
		)
	);

	//print_r($posts);
	$theme_slug = get_stylesheet();
	$menus = array();

	if (!empty($posts)) {
		foreach ($posts as $key => $post) {
			$taxonomy_id 	= $this->recorp_get_term_taxonomy_id($post->ID); //object id to term_texonomy_id
			$post_meta = get_post_meta($post->ID);

			$term_id = $this->recorp_get_term_id($taxonomy_id); 
			$term_name 	= $this->recorp_get_term_name( $term_id);
			$term_slug 		= $this->recorp_get_term_slug( $term_id);
			$active_location	= $this->recorp_get_menu_location_slug($term_id); 
			//if location selected else return false;

			$menu = array();
			$menu['term_id'] = $term_id;
			$menu['term_name'] = $term_name;
			$menu['term_slug'] = $term_slug;
			$menu['active_theme'] = $theme_slug;

			$menu['location'] = ( !empty($active_location) ) ? $active_location : "not_active";
			$menu['item'] = $post;
			$menu['meta'] = $post_meta;

			$menus[] = $menu;

		}
	}

	echo serialize($menus) ;

	die();
}


/*
$locations 	= get_nav_menu_locations();
$menu_items = wp_get_nav_menus();

*/

public function recorp_get_term_taxonomy_id($object_id = 0){
	global $wpdb;
	$get_tax = $wpdb->get_results("SELECT term_taxonomy_id FROM " . $wpdb->prefix . "term_relationships WHERE object_id = '".$object_id."'" );

	return $get_tax[0]->term_taxonomy_id;
}


public function recorp_get_taxonomy_id_by_term($term_id = 0){
	global $wpdb;
	$get_tax = $wpdb->get_results("SELECT term_taxonomy_id FROM " . $wpdb->prefix . "term_taxonomy WHERE term_id = '".$term_id."'" );

	return $get_tax[0]->term_taxonomy_id;
}

public function recorp_get_term_id($term_taxonomy_id = 0){
	global $wpdb;
	$get_tax = $wpdb->get_results("SELECT term_id FROM " . $wpdb->prefix . "term_taxonomy WHERE term_taxonomy_id = '".$term_taxonomy_id."'" );

	return $get_tax[0]->term_id;
}
public function recorp_get_term_name($term_id = 0){
	global $wpdb;
	$term_name = $wpdb->get_results("SELECT name FROM " . $wpdb->prefix . "terms WHERE term_id = '".$term_id."'" );

	return $term_name[0]->name;
}

public function recorp_get_term_slug($term_id = 0){
	global $wpdb;
	$term_name = $wpdb->get_results("SELECT slug FROM " . $wpdb->prefix . "terms WHERE term_id = '".$term_id."'" );

	return $term_name[0]->slug;
}

public function recorp_get_menu_location_slug($term_id = 0){
	$menu_locations = get_nav_menu_locations();

	if (isset($menu_locations)) {
		foreach ($menu_locations as $location => $value) {
			if ($term_id == $value) {
				return $location;
			}
		}
	}
	
}


public function recorp_term_slug_to_term_id($slug = ""){
	global $wpdb;
	$term_name = $wpdb->get_results("SELECT term_id FROM " . $wpdb->prefix . "terms WHERE slug = '".$slug."'" );

	return $term_name[0]->term_id;
}

public function recorp_add_term_relationships($term_id, $texonomy_id){
	global $wpdb;
	$wpdb->insert( 
		$wpdb->prefix . 'term_relationships', 
		array( 
			'object_id' => $term_id, 
			'term_taxonomy_id' => $texonomy_id
		), 
		array( 
			'%d', 
			'%s' 
		) 
	);

}


public function recorp_import_menus(){

	check_ajax_referer( 'recorp_nonce', 'nonce' );

$csv_data = sanitize_text_field($_POST['csv_data']);

$csv_data = $csv_data;

$menus = unserialize(stripslashes($csv_data));


	/*
	* Removing previous menus
	*/

	$posts = get_posts(
		array(
			'post_type' 		=> 'nav_menu_item', 
			'posts_per_page' 	=> -1, 
			'post_status' 		=> 'publish'
		)
	);

	if (!empty($posts)) {
		foreach ($posts as $key => $post) {
			wp_delete_post($post->ID);
		}
	}

$terms = array();

/*Trim the term ids*/
foreach ($menus as $key => $value) {
	if($value['location'] !== "not_active"){
		if (!empty($value['term_slug'])) {
			$term_name = $value['term_name'];
			$term_id = $value['term_id'];
			$term_slug = $value['term_slug'];

			$terms[$term_slug]['name'] = $term_name;
			$terms[$term_slug]['term_id'] = $term_id;
			$terms[$term_slug]['term_slug'] = $term_slug;
		}
	}

}


$imported_terms = array();

foreach ($terms as $term ) {

	//print_r($term);
	
	/*Previous terms remove*/
	wp_delete_term( $this->recorp_term_slug_to_term_id($term['term_slug']), 'nav_menu' );


	/*New term add*/
	$ids = wp_insert_term($term['name'], 'nav_menu');

		//print_r($ids);

		$term_id = $ids['term_id'];
		$taxonomy_id = $ids['term_taxonomy_id'];
		$term_slug = $this->recorp_get_term_slug($term_id);

	$imp_terms['term_id'] = $term_id;
	$imp_terms['taxonomy_id'] = $taxonomy_id;
	$imp_terms['term_slug'] = $term_slug;
	$imported_terms[] = $imp_terms;

}

//print_r($imported_terms);

update_option('recorp_menu_import_datas', $imported_terms);
//print_r($terms);


$join_tax_with_post = array();
$term = array();

foreach ($menus as $key => $value) {

	if($value['location'] !== "not_active"){

		$title = $value['item']->post_title;
		$name = $value['item']->post_name;
		$menu_order = $value['item']->menu_order;


		$post_args = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title' => $title,
			'post_name' => $name,
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_type' => 'nav_menu_item',
			'menu_order' => $menu_order,
			'post_status' => 'publish',

		);

		$post_id = wp_insert_post($post_args);


	//Setting the post types
		foreach ($value['meta'] as $meta_key => $meta_value) {
			$meta_key = $meta_key;
			$meta_value = $meta_value[0];

			if ( ! add_post_meta( $post_id, $meta_key, $meta_value, true ) ) { 
			   update_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		$term_slug = $value['term_slug'];
		$term_id = $this->recorp_term_slug_to_term_id($term_slug);
		$taxonomy_id = $this->recorp_get_taxonomy_id_by_term($term_id);

		$this->recorp_add_term_relationships($post_id, $taxonomy_id);

		$locations = get_theme_mod( 'nav_menu_locations' );
		$locations[$value['location']] = $term_id;
		set_theme_mod ( 'nav_menu_locations', $locations );

		//$hi = wp_set_object_terms($post_id, $term_id, 'nav_menu');

		//print_r($hi);

	$join['post_id'] = $post_id;
	$join['term_id'] = $term_id;
	$join['term_slug'] = $term_slug;
	$join['taxonomy_id'] = $taxonomy_id;

	$join_tax_with_post[] = $join;

	$term[] = $term_id;
	}

}

	
echo $term[0];
	die();
}

}
