jQuery(document).ready(function(){
  var ajax_url = LMAPS_reg_vars.LMAPS_ajax_url;
           jQuery("#lmaps_search_box").keyup(function(){
              var test = jQuery(this).val();
              jQuery('.lmaps-product-list').remove();
              
              jQuery.ajax({
                  url : ajax_url,
                  type : 'post',
                  data : {
                    action: 'get_wc_title',
                    search_text : test
                  },dataType:'html',
                  beforeSend: function(response) {
                      
                  },
                  success : function( response ) {
                      jQuery('.lmaps-product-list').remove();
                      jQuery('.lmaps-not-found').remove();
                        if(response){
                          jQuery('.lmaps-product-list-wrapper').addClass('lmaps-open');
                          jQuery(".lmaps-product-list-wrapper").append(response); 
                        }else{
                          jQuery('.lmaps-product-list-wrapper').addClass('lmaps-open');
                          jQuery(".lmaps-product-list-wrapper").append('<div class="lmaps-not-found">找不到該商品。</div>'); 
                        }
                  },
                  complete: function(response) {
                      if(test === ''){
                          jQuery('.lmaps-product-list-wrapper').removeClass('lmaps-open');
                      }  
                  },
                  error: function() {

                  }
                });
           });
          
          jQuery(document).click(function(event) { 
              if(!jQuery(event.target).closest('.lmaps-product-list-wrapper').length) {
                   jQuery('.lmaps-product-list-wrapper').removeClass('lmaps-open');
              }        
          });
 });


