<?php

/**
* Provide a public-facing view for the plugin
*
* This file is used to markup the public-facing aspects of the plugin.
*
* @link       http://selise.ch/
* @since      1.0.0
*
* @package    Lankabangla_Transactions
* @subpackage Lankabangla_Transactions/public/partials
*/

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="transaction_wrapper">
<div class="container" > 
<div class="mixitup_inner">
<div class="controls">
<button type="button" class="control mixitup-control-active" data-filter="all">All</button>
<?php
$cat_args=array( 
'taxonomy' => 'crown_project_category',
'hierarchical' => 0, 
'orderby' => 'term_id',
'order'  => 'ASC'

);
$categories = get_categories($cat_args); 
foreach ($categories as $key=>$category) { ?> 
<button type="button" class="control " data-filter=".<?php echo $category->slug; ?>"><?php echo $category->name; ?></button>
<?php } ?> 


<div id="crownProjectsCarouselResults" style="border:1px solid red; min-height:400px;" class="carousel slide" data-ride="carousel"> 



<div class="carousel-inner"> 

<?php

$projects_args = array(
'post_type' => 'crown_projects',
'posts_per_page' => 30, 
);


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
</div>

 













            <?php
            $cat_args=array( 
                'taxonomy' => 'transactions_category',
                'hierarchical' => 0, 
                'orderby' => 'term_id',
                'order'  => 'ASC'

            );
            $categories = get_categories($cat_args);
            $cat_increment = 1;
            foreach ($categories as $key=>$category) {
                 //echo '<pre>'; print_r($category); echo '</pre>';

                ?>

            <button type="button" class="control " data-filter=".<?php echo $category->slug; ?>"><?php echo $category->name; ?></button>
                <?php
            }
            ?> 
        <div class="filter_panel">
            <div>Filter by: <div  class="open_modal">Year/Sector</div></div>
        </div> 

        <?php 
            $args = array(      
                'post_type' => 'transactions',
                'post_status' => 'publish',  
                'order' => 'ASC',                 
            ); 
            $post = new WP_Query($args); 
            $post_count = $post->post_count + 1;//pagination 1 post hide  
             
        ?>
        <div class="containers" data-ref="containers">
            <div class="row data-container" id="results">  
                
        </div>
        
        <div id="pagination-list" class="pagination"></div> <!--pagination Elements close-->   

      </div>
    </div><!-- mixitup inner-->
</div>
</div> 

<div class="modal fade" id="year_sectors_modal" >
  <div class="modal-dialog modal-lg">
    <form method="post" id="FormId">
      <div class="modal-content" style="min-height: 500px; padding: 70px;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 

        <div class="search_list_inner"> 

          <div class="alert alert-danger select_alert" role="alert">
            Please select at least one years/sectors
          </div>
          <h3>Year</h3> 

          <div class="InputGroup years"> 
            <?php
            $trans_years_args =array( 
              'taxonomy' => 'transactions_years',
              'hierarchical' => 0, 
              'orderby' => 'term_id',
              'order'  => 'ASC'
            );
            $trans_years_query = get_categories($trans_years_args);
            foreach ($trans_years_query as $key=>$years) { ?>                 
              <input type="radio" name="years_selector" id="<?php echo $years->slug; ?>" value="<?php echo $years->slug; ?>">
              <label for="<?php echo $years->slug; ?>"><?php echo $years->name; ?></label> 
              <?php
            }
            ?>   
          </div>  
        </div>


        <div class="search_list_inner">
          <h3>Sectors</h3>
          <div class="InputGroup sectors"> 
            <?php
            $trans_sectors_args =array( 
              'taxonomy' => 'transactions_sectors',
              'hierarchical' => 0, 
              'orderby' => 'term_id',
              'order'  => 'ASC'
            );
            $trans_sectors_query = get_categories($trans_sectors_args);
            foreach ($trans_sectors_query as $key=>$sectors) {?>                  
              <input type="radio" name="sectors_selector" id="<?php echo $sectors->slug; ?>" value="<?php echo $sectors->slug; ?>">
              <label for="<?php echo $sectors->slug; ?>"><?php echo $sectors->name; ?></label> 
            <?php } ?>
          </div>

        </div>

        <div class="btn_area">
          <div class="align-items-center spinner">             
            <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
          </div>
          <input type="submit" name="submit_btn" class="submit_btn" value="Submit">
          <input type="Reset" name="Reset_btn" value="Reset">
        </div>

      </div>
    </form>
  </div>
</div>

 <script src="http://localhost/fresh_wordpress/wp-content/plugins/lankabangla-transactions/public/js/pagination.js"></script>
 <script>  
 //onload data
 categoryWisePaginationFunc(<?php echo $post_count; ?>);
 
 function categoryWisePaginationFunc(post_count){
    
    var container = jQuery('#pagination-list');
        var sources = function () {
        var result = [];  
        for (var i = 1; i < post_count ; i++) {
        result.push(i);
        } 
        return result;
    }();

    var options = { 
        pageSize: 3, 
        dataSource: sources,
            callback: function (response, pagination) { 
            
            var page = jQuery('.active').attr('data-num'); 
            var active_cat = jQuery('.mixitup-control-active').attr('data-filter'); 
            var cat = active_cat.split(".");  
            var active_category = cat[1]; 
            //console.log(post_count); 

            if(active_cat == 'all'){
                var active_category = 'all';       
            }else{
                var active_category = cat[1];      
            }

            jQuery.ajax({ 
                url: search_ajax.ajax_url,   
                type: "POST", 
                data: {
                    action:"pagination_ajax_action",
                    activeCategory: active_category,
                    page: page, 
                }, 
                success: function(response){  
                    //pagination data onload 
                    container.prev().html(response); 
                },error: function(errorThrown){
                    //console.log(errorThrown);
                } 
            });

        }
    };

    //jQuery.pagination(container, options); 

    container.addHook('beforeInit', function () {
      window.console && console.log('beforeInit...');
    });
    container.pagination(options);

    container.addHook('beforePageOnClick', function () {
      window.console && console.log('beforePageOnClick...');
      //return false
    });

 
}
jQuery('.controls button').on('click',function(){   

    jQuery('.controls button').removeClass('mixitup-control-active');
    jQuery(this).addClass('mixitup-control-active');
   
    var thisCategory = jQuery(this).attr('data-filter');
    var active_cat = jQuery('.mixitup-control-active').attr('data-filter'); 
    var cat = thisCategory.split(".");  
    var active_category = cat[1];         

    if(active_cat == 'all'){
        var active_category = 'all';       
    }else{
        var active_category = cat[1];      
    }
     alert(active_category);
    jQuery.ajax({ 
    url: search_ajax.ajax_url,   
    type: "POST", 
    data: {
        action:"projects_ajax_function_callback",
        activeCategory: active_category,
        page: 1, 
    }, 
    success: function(response){  
        //pagination data onload 
        jQuery('#crownProjectsCarouselResults').html(response);
         console.log(response);
    },error: function(errorThrown){
        //console.log(errorThrown);
    } 
    });

});


/*//when clicking button search others data hide
jQuery('.controls button').on('click',function(){    
   
    jQuery('.controls button').removeClass('mixitup-control-active');
    jQuery(this).addClass('mixitup-control-active');
 
   //ajax start
    var page = jQuery('.active').attr('data-num');  
    var active_cat = jQuery('.mixitup-control-active').attr('data-filter'); 

    var cat = active_cat.split(".");  
    var active_category = cat[1];         

    if(active_cat == 'all'){
        var active_category = 'all';       
    }else{
        var active_category = cat[1];      
    }

    jQuery.ajax({ 
        url: search_ajax.ajax_url,   
        type: "POST", 
        data: {
            action:"post_count_ajax_action",
            activeCategory: active_category,
            page: page, 
        }, 
        success: function(response){   
             console.log(response); 
             //for pagination  
              var response = parseInt(response) + parseInt(1); 
          
            categoryWisePaginationFunc(response);
        } 
    });//ajax close 
     
}); //function close*/
 

 </script>
 <script type="text/javascript" src="http://localhost/fresh_wordpress/wp-content/plugins/lankabangla-transactions/public/js/lankabangla-transactions-public.js"></script>

 <style type="text/css">
     .paginationjs-pages ul li{
        list-style: none;
        display: inline-block; 
     }
     .paginationjs-pages ul li a {
        position: relative;
        float: left;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #337ab7;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
}
.paginationjs-pages ul li.active a{
      background-color: #187A53;
      color: #fff;  
}
.data-container{
    display: inline-block;
}
 #pagination-list{
    width: 100%;
    margin: 0 auto;
    text-align: center;
}
.post_inner_search{
   
    min-height: 233px; 
}

 </style>
 
 
