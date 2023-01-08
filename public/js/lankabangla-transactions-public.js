(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */ 

 
jQuery("ul.pagination li a").on('click', function(){  
    jQuery('.pagination li a').removeClass('active_pagination');
    jQuery(this).addClass('active_pagination'); 
    
     
      activeCategoryWithPaginateData();  
   
});  
  

//pagination function
function activeCategoryWithPaginateData(){     
    
   var page = jQuery('.active_pagination').attr('id');  

    var active_cat = jQuery('.mixitup-control-active').attr('data-filter'); 
    var cat = active_cat.split(".");  
    var active_category = cat[1]; 
    //console.log(post_count);

    //alert(active_cat);

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
        //console.log(response);
        jQuery(".spinner").hide(); 
        jQuery(".mix").hide();  
        
        var per_page_split = response.split('==='); 
        var per_page = jQuery('#per_page').attr('value', per_page_split[1]); 
        jQuery("#results").html(response);   
        //console.log(per_page_split[1]);
       
        var per_page = parseInt(per_page_split[1]) + parseInt(1);
        console.log(per_page);
        var text = '';
        let i = 1;
         for (i = 1; i < per_page; i++) {
           text += '<li><a href="javascript:void(0);" id="'+i+'" class="pagination_number">'+i+'</a></li>'; 
             
         } 
        
         var prev = '<li><a href="javascript:void(0);" id="prev">previous</a></li>';
         var next = '<li><a href="javascript:void(0);" id="next">Next</a></li>';
         jQuery('.pagination').load();
         jQuery('.pagination').html();
         jQuery('.pagination').trigger("change");;
       

         setTimeout(function() { 
          jQuery('.pagination').html();
          jQuery('.pagination').load();
          jQuery(".pagination").html( prev+text+next ); 
      }, 2000);


   /* jQuery(document).ready(function(){
      jQuery("#pagination").load("http://localhost/fresh_wordpress", function(response, status) {

      if(status === 'error') {
      alert("Failed to load menu.html");
      }else {
        jQuery('.pagination').load();
      }    
      });    
    }); */
        //console.log(prev+text+next);
     
      },error: function(errorThrown){
      //alert(errorThrown);
      } 
    });
}

 
 









	 
jQuery("#FormId").submit(function(event){
    event.preventDefault(); 
    //alert(search_ajax.ajax_url);
    
    var years_selector = jQuery(".InputGroup input[name='years_selector']:checked").val();
    var sectors_selector = jQuery(".InputGroup input[name='sectors_selector']:checked").val(); 
 

    //alert(sectors_selector);  
    if((typeof years_selector === "undefined") && ( typeof sectors_selector === "undefined")){
      jQuery(".select_alert").show();  
    }else{
    jQuery.ajax({ 
      url: search_ajax.ajax_url, //did not same function wordpress such as ajax_url
      //url: 'https://stage-lankabangla.selise.biz/wp-admin/admin-ajax.php',
   
      dataType: 'json',
      contentType: 'application/json',
       timeout: 500,
      type: "POST", 
      data: {
        action:"selection_ajax_function_callback",
        yearsSelector: years_selector,
        sectorsSelector: sectors_selector
      }, 
      success: function(response){
        console.log(response);
        jQuery(".spinner").hide(); 
        jQuery(".mix").hide(); 
        jQuery("#year_sectors_modal .close").click()
        jQuery("#results").prepend(response); 
      //alert(response); 
      },error: function(errorThrown){
      //alert(errorThrown);
      }
     
  
    });
    }



});  



//custom open modal
 jQuery('.open_modal').click(function(){  
    jQuery("#year_sectors_modal").modal('show');
});


  

})( jQuery );