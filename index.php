<?php
if ( ! function_exists( 'partdo_header_attribute_search' ) ) {
	function partdo_header_attribute_search(){
		$custombutton = get_theme_mod('partdo_header_attribute_search_toggle','0'); 
		if($custombutton == '1'){ ?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
			  jQuery(".theme-select").on("change", function() {

			  	console.log(jQuery(this).val());

			  	var selected_id = jQuery(this).attr("data_select_id");
			  	var selected_name = jQuery(this).val();
			  	var next_selected_id = parseInt(selected_id) + 1;
			  	next_selected_id = next_selected_id.toString();
			  	var next_selected_name = jQuery("select[data_select_id='"+next_selected_id+"']").attr("data_item_name");

			  	if (jQuery("select[data_select_id='"+next_selected_id+"']").length) {
			  		var mySelect = jQuery("select[data_select_id='"+next_selected_id+"']");
			  		mySelect.children().not("option[data_option='default']").remove();
			  		data_option="default"

			        var data = {
			            'action': 'check_next_attribute',
			            'selected_name': selected_name,
			            'next_selected_name':next_selected_name
			        };

			        jQuery.post('<?php echo admin_url('admin-ajax.php');?>', data, function(response) {
					    var data = JSON.parse(response);

					    for (var i = 0; i < data.length; i++) {
					        var option = jQuery("<option>", {
					            value: data[i].slug,
					            text: data[i].name
					        });
					        mySelect.append(option);
					    }
			        });

			  	}

			  });
			});
		</script>
		<div class="quick-button custom-button">
			<div class="quick-button-inner">
				<div class="quick-icon" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="partdo-tooltip white arrow-hide" data-bs-title="<?php echo esc_attr(get_theme_mod('partdo_header_attribute_search_title')); ?>" data-klbth-modal="service-modal"><i class="klbth-icon-garage-house"></i></div>
				<div class="klbth-modal-holder" id="service-modal" tabindex="-1" aria-labelledby="service-modal" aria-modal="true" role="dialog"> 
					<div class="klbth-modal-inner size--sm"> 
						<div class="klbth-modal-header"> 
							<h3 class="entry-title"><?php echo esc_html(get_theme_mod('partdo_header_attribute_search_title')); ?></h3>
							<div class="site-close"> <a href="#" aria-hidden="false"> <i class="klbth-icon-xmark"></i></a></div>
						</div>
						<div class="klbth-modal-body"> 
							<div class="service-search-modal">
								<?php if(get_theme_mod( 'partdo_header_attribute_search_image' )){ ?>
									<img src="<?php echo esc_url( wp_get_attachment_url(get_theme_mod( 'partdo_header_attribute_search_image' )) ); ?>" alt="<?php esc_attr_e('search','partdo'); ?>"/>
								<?php } ?>
								
								<div class="entry-description">
									<p><?php echo partdo_sanitize_data(get_theme_mod('partdo_header_attribute_search_subtitle')); ?></p>
								</div>

								<?php
									$attribute_items = get_theme_mod('partdo_header_attribute_search_attribute_name');
									
									if($attribute_items){
										
										wp_enqueue_script('partdo-attribute-filter');
										
										$str = str_replace(' ','',$attribute_items);
										$attribute_array = explode(',',$str);
								 
										echo '<form class="service-search-form" id="klb-attribute-filter" action="' . wc_get_page_permalink( 'shop' ) . '" method="get">';
										$count = 0;
										foreach($attribute_array as $item_key => $item_name) {

											$terms = get_terms( 'pa_'.$item_name, array(
												'orderby' => 'menu_order',
												'hide_empty' => true,
												'parent' => 0,
											));

											$label_name = wc_attribute_label( 'pa_'.$item_name );

											echo '<div class="form-column">';
											echo '<select class="theme-select" name="filter_'.esc_attr($item_name).'" id="filter_'.esc_attr($item_name).'" tax="pa_'.$item_name.'" data-placeholder="'.esc_attr__('Select', 'partdo').' '.esc_attr($label_name).'" data-search="true" data-searchplaceholder="'.esc_attr__('Search item...', 'partdo').'" data_select_id="'.$item_key.'" data_item_name="'.$item_name.'">';
											
											echo '<option data_option="default" value="">'.sprintf('Select %s', $label_name).'</option>';
											foreach ($terms as $term) {
												if($count == 0) {
													echo '<option id="'.esc_attr($term->term_id).'" value="'.esc_attr($term->slug).'">'.esc_html($term->name).'</option>';
												}
											}
											echo '</select>';
											echo '</div>';
											
											$childcount = 1;
											foreach ($terms as $term) {
												$term_children = get_term_children( $term->term_id, 'pa_'.$item_name );
												
												if($term_children && $childcount == 1){
													echo '<div class="form-column">';
													echo '<select class="child-attr theme-select" id="child_filter_'.esc_attr($item_name).'" name="filter_'.esc_attr($item_name).'" data-placeholder="'.esc_attr__('Select Model', 'partdo').'" data-search="true" data-searchplaceholder="'.esc_attr__('Search item...', 'partdo').'" disabled>';
													echo '<option value="0">'.sprintf('Select %s First', $item_name).'</option>';
													echo '</select>';
													echo '</div>';
												}
												$childcount++;
											}
										
											$count++;
											echo '<input type="text" id="klb_filter_'.esc_attr($item_name).'" name="filter_'.esc_attr($item_name).'" value="" hidden/>';
										}
										echo '<div class="form-column">';
										echo '<button class="btn primary">'.esc_html__('Find Auto Parts','partdo').'</button>';
										echo '</div>';
										
										echo '</form>';
									}
								?>
								<div class="service-description"> 
									<p><?php echo esc_html(get_theme_mod('partdo_header_attribute_search_second_subtitle')); ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="klbth-modal-overlay"></div>
				</div>
			</div>
		</div>

		<?php  }
	}
}
