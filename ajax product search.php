<?php
/*
* Plugin Name: ajax product search
* Description: 
* Author: Kyrie
* Author URI: 
* Network: false
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class LMAPS_ajax_search extends WP_Widget{
     
    
    function __construct(){
				$widget_options = array( 
					'classname' => 'lmaps_ajax_search',
					'description' => '即時搜尋商品',
				);
				parent::__construct( 'lmaps_ajax_search', '即時搜尋商品', $widget_options );
        add_action( 'wp_ajax_get_wc_title', array( $this, 'ajax_product' ) );
        add_action( 'wp_ajax_nopriv_get_wc_title', array( $this, 'ajax_product' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'lmaps_register_user_scripts') );
    }
	
    public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', $instance[ 'title' ] );
			echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; 
			?>
				<div class="lmaps">
          <input id="lmaps_search_box" name="search_text_haha" type="text" placeholder="請輸入關鍵字">
          <div class="lmaps-product-list-wrapper">
          </div>
        </div>
        <style>
          .lmaps{
            width: 100%;
          }
			#lmaps_search_box{
				height: 40px!important;
				margin: 0;
				padding: .7em;
				border: 1px solid #ddd;
				color: #666;
				border-radius:5px;
				width: 100%;
			}
          .lmaps-product-list-wrapper{
            border: 1px solid #ccc;
            border-radius: 5px;
            background: white;
            margin-top: 5px;
						word-wrap: break-word;
            display:none;
						position: absolute;
    				z-index: 2;
						width: 100%;
		    max-height: 300px;
    		overflow: auto;
          }
					.lmaps-product-list{
						display: inline-block;
						width: 100%;
						height: 70px;
					}
          .lmaps-product-list-content{
            margin:0 5px;
						display: inline-block;
						width:100%;
						height: 70px;
						position: relative;
          }
					.lmaps-product-list-content > a{
						height: 100%;
						width: 100%;
						position: absolute;
					}
          .lmaps-open{
            display:block;
          }
					.lmaps-not-found{
						margin:20px 10px;
					}
					.lmaps-image{
						width: fit-content;
						margin: 5px 0;
						display: block;
						float: left;
					}
					.lmaps-image > img{
						width:50px;
						height:50px;
					}
					.lmaps-title, .lmaps-price{
						display: block;
    				height: 50%;
						overflow: hidden;
					}
					.lmaps-info{
						float: left;
    					display: block;
						height: 100%;
						margin: 0 5px;
						max-width: 120px;
						margin-top: 1px;
					}
					.lmaps-price{
						margin-top: 5px;
					}
					.lmaps-title{
						font-size: 13px;
    				line-height: normal;
					}
        </style>
			<?php echo $args['after_widget'];
		}
	
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">標題:</label>
				<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
			</p><?php 
		}
		
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
			return $instance;
		}
	
    public function lmaps_register_user_scripts(){
            wp_register_script('LMAPS_reg_script', plugin_dir_url( __FILE__ ) . 'ajax-get-all-product.js', array('jquery'), '1.0.57', true);
            wp_enqueue_script('LMAPS_reg_script');
            wp_localize_script( 'LMAPS_reg_script', 'LMAPS_reg_vars', array(
                'LMAPS_ajax_url' => admin_url( 'admin-ajax.php' ),
              )
             );
    }
    
    public function ajax_product(){
         $search_text = $_POST['search_text'];
         $search_text_lowercase = strtolower($search_text);
         $args = array(
              'post_type' => 'product',
              'posts_per_page' => -1
          );
          	$loop = new WP_Query( $args );

            if ( $loop->have_posts() ){ 
				while ( $loop->have_posts() ){ 
				$loop->the_post();
                global $product;
                $title = $product->get_title();
                $title_lowercase = strtolower($title);
                $price = $product->get_price_html();
				$link = $product->get_permalink();
				$thum_image = $product->get_image( $size = 'shop_thumbnail', $attr = array(), $placeholder = true  );
               	if(strpos($title_lowercase, $search_text_lowercase) !== false){
                  ?>
                    <div class="lmaps-product-list">
                        <div class="lmaps-product-list-content">
												<a href="<?php echo $link; ?>"></a>
													<div class="lmaps-image"><?php echo $thum_image; ?></div>
													<div class="lmaps-info">
														<span class="lmaps-title"><?php echo $title; ?></span>
														<span class="lmaps-price"><?php echo $price; ?></span>
													</div>
                        </div>
                    </div>
                  <?php
								}
							}
						}
						wp_reset_postdata();
            die();       
    }    
}

function LMAPS_ajax_search_widget() { 
  register_widget( 'LMAPS_ajax_search' );
}
add_action( 'widgets_init', 'LMAPS_ajax_search_widget' );
