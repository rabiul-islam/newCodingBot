<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              http://selise.ch/
* @since             1.0.0
* @package           Lankabangla_Transactions
*
* @wordpress-plugin
* Plugin Name:       Lankabangla Transactions
* Plugin URI:        http://selise.ch/
* Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
* Version:           1.0.0
* Author:            Selise Team (ITSM)
* Author URI:        http://selise.ch/
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       lankabangla-transactions
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}

/**
* Currently plugin version.
* Start at version 1.0.0 and use SemVer - https://semver.org
* Rename this for your plugin and update it as you release new versions.
*/
define( 'LANKABANGLA_TRANSACTIONS_VERSION', '1.0.0' );

/**
* The code that runs during plugin activation.
* This action is documented in includes/class-lankabangla-transactions-activator.php
*/
function activate_lankabangla_transactions() {
   require_once plugin_dir_path( __FILE__ ) . 'includes/class-lankabangla-transactions-activator.php';
   Lankabangla_Transactions_Activator::activate();
}

/**
* The code that runs during plugin deactivation.
* This action is documented in includes/class-lankabangla-transactions-deactivator.php
*/
function deactivate_lankabangla_transactions() {
   require_once plugin_dir_path( __FILE__ ) . 'includes/class-lankabangla-transactions-deactivator.php';
   Lankabangla_Transactions_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lankabangla_transactions' );
register_deactivation_hook( __FILE__, 'deactivate_lankabangla_transactions' );

/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/class-lankabangla-transactions.php';
 
 


/**
* Begins execution of the plugin.
*
* Since everything within the plugin is registered via hooks,
* then kicking off the plugin from this point in the file does
* not affect the page life cycle.
*
* @since    1.0.0
*/
  


//total post count
add_action('wp_ajax_nopriv_post_count_ajax_action', 'post_count_ajax_function_callback'); 
add_action('wp_ajax_post_count_ajax_action', 'post_count_ajax_function_callback');

//ajax data
function post_count_ajax_function_callback(){   
 
   $activeCategory =  $_REQUEST['activeCategory']; 
   $page = $_REQUEST['page'];  

 //for pagination
 if($activeCategory == 'all'){
   $total_post_args = array(
      'post_type' => 'transactions'  
   );
  }else{
   $total_post_args = array(
      'post_type' => 'transactions',  
      'tax_query' => array(
         'relation' => 'OR',
         array(
            'taxonomy' => 'transactions_category',
            'field'    => 'slug',
            'terms'    => $activeCategory,
         )
      ),
   );
  }

$total_post_args_query = new WP_Query( $total_post_args);
   $post_count =  $total_post_args_query->post_count;  
   //$per_page =  ceil( $post_count / 3); 
   echo $post_count; 
   die();
}


//onload data for transaction ajax data
//ajax add action
add_action('wp_ajax_nopriv_pagination_ajax_action', 'pagination_ajax_function_callback'); 
add_action('wp_ajax_pagination_ajax_action', 'pagination_ajax_function_callback');
function pagination_ajax_function_callback(){   
   
   $activeCategory =  $_REQUEST['activeCategory']; 
   $page = $_REQUEST['page'];   

  //for data
  if($activeCategory == 'all'){
   $args = array(
      'post_type' => 'transactions',
       'posts_per_page' => 3,
      'paged' => $page, 
   );
  }else{
   $args = array(
      'post_type' => 'transactions',
       'posts_per_page' => 3,
      'paged' => $page, 
      'tax_query' => array(
         'relation' => 'OR',
         array(
            'taxonomy' => 'transactions_category',
            'field'    => 'slug',
            'terms'    => $activeCategory,
         )
      ),
   );
  }  

$pagination_query = new WP_Query( $args);
if( $pagination_query->have_posts() ) {
   $i = 1;
   while( $pagination_query->have_posts() ) {
      $pagination_query->the_post();

      $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );  
      $terms = wp_get_object_terms( get_the_ID(), 'transactions_category');

      foreach($terms as $cat){
         $cat_slug_by_post_id = $cat->slug;
      } 

      ?>
      <div class="post_inner_search col-md-4">
         <img src="<?php echo $image[0]; ?>" width="100" alt="<?php the_title();?>">
         <div class="post_details"> 
            <a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title(); ?></a>
            <p><?php the_content();?></p>
            <p><?php the_excerpt(); ?></p>
         </div>
      </div> 

      <?php
    //if($i== $per_page) break; $i++;
    }
}else{
   echo 'No data found..';
} 
die(); 
}

 
//ajax add action
add_action('wp_ajax_nopriv_projects_ajax_function_callback', 'projects_ajax_function_callback'); 
add_action('wp_ajax_projects_ajax_function_callback', 'projects_ajax_function_callback');



//ajax data
function projects_ajax_function_callback(){    
   
  echo $activeCategory = $_REQUEST['activeCategory']; 
    //for data
  if($activeCategory == 'all'){
   $projects_args = array(
      'post_type' => 'crown_projects',
       'posts_per_page' => 300, 
   );
  }else{
   $projects_args = array(
      'post_type' => 'crown_projects',
      'posts_per_page'=> -1,
      'tax_query' => array(
         'relation' => 'OR',
         array(
            'taxonomy' => 'crown_project_category',
            'field'    => 'slug',
            'terms'    => $activeCategory,
         ) 
      ),
   );
}

 
   ?>
    
  
  <div class="carousel-inner">
   <?php 

$projects_query = new WP_Query( $projects_args);
$proj_post_count = $projects_query->post_count;  
if( $projects_query->have_posts() ) {
   $i = 1;
   while( $projects_query->have_posts() ) {
      $projects_query->the_post();

      $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );   

      ?>
      

      <div class="item <?php if ($i ==1){ echo 'active'; } ?>">

         <a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title(); ?></a>
            <p><?php the_content();?></p>

                 <img src="<?php echo $image[0]; ?>" width="100" alt="<?php the_title();?>">
       </div> 


      <?php
      $i++;
    }
}else{
   echo 'No data found..';
} 
 
 
    
   ?>

</div>
                      <ol class="carousel-indicators">
                       <?php
                       $incr = 0;
                        for ($incr = 0; $incr < $proj_post_count; ++$incr) { ?>
                            <li data-target="#crownProjectsCarouselResults" data-slide-to="<?php echo $incr; ?>" class="<?php if($incr == 0){ echo 'active'; } ?>"><?php echo $incr; ?></li> 
                      
                  <?php } ?>

                      </ol>

  
   <?php

   die(); 
}




add_shortcode( 'transactions_shortcode', 'transactions_shortcode_func' );
function transactions_shortcode_func( $atts ) { 
 
   require plugin_dir_path( __FILE__ ) . 'public/partials/lankabangla-transactions-public-display.php';
    
}

function run_lankabangla_transactions() {
   $plugin = new Lankabangla_Transactions();
   $plugin->run();
}
run_lankabangla_transactions();