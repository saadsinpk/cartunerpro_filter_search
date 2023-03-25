
function check_next_attribute() {

    $selectedName = isset($_POST['selected_name']) ? sanitize_text_field($_POST['selected_name']) : '';
    $next_selected_name = isset($_POST['next_selected_name']) ? sanitize_text_field($_POST['next_selected_name']) : '';

    // select term_id from wp_terms where slug = selected_name;
    // Select object_id From wp_term_relationships where term_taxonomy_id = term_id
    // Select term_taxonomy_id FROM wp_term_relationships WHERE object_id = object_id
    // Select term_id FROM wp_term_taxonomy WHERE taxnomy = pa_model and term_id = term_taxonomy_id

	global $wpdb;

	// Get the term ID for the selected name
	$termId = $wpdb->get_var($wpdb->prepare(
	  "SELECT term_id FROM {$wpdb->terms} WHERE slug = %s",
	  $selectedName
	));
	// Get the object IDs for the term ID
	$objectIds = $wpdb->get_col($wpdb->prepare(
	  "SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d",
	  $termId
	));

	// Get the term taxonomy IDs for the object IDs
	$termTaxonomyIds = $wpdb->get_col($wpdb->prepare(
	  "SELECT term_taxonomy_id FROM {$wpdb->term_relationships} WHERE object_id IN (" . implode(',', $objectIds) . ")"
	));
	$termTaxonomyIds = array_unique($termTaxonomyIds);

	// Get the term IDs for the term taxonomy IDs with a specific taxonomy and term ID
	$taxonomy = 'pa_'.$next_selected_name;
	$termTaxonomyId = $wpdb->get_col($wpdb->prepare(
	  "SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy} WHERE taxonomy = %s AND term_id IN (" . implode(',', $termTaxonomyIds) . ")",
	  $taxonomy
	));

	$matchingTermIds = $wpdb->get_results($wpdb->prepare(
	  "SELECT name, slug FROM {$wpdb->terms} WHERE term_id IN (" . implode(',', $termTaxonomyId) . ")"
	));

	print_r(json_encode($matchingTermIds));
	// return $matchingTermIds;

    wp_die();
}
add_action('wp_ajax_check_next_attribute', 'check_next_attribute');
add_action('wp_ajax_nopriv_check_next_attribute', 'check_next_attribute');
