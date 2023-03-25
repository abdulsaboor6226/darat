<?php

	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-tabs.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-adspace.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-agentinfo.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-agentsotherlistings.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-blogauthor.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-brokerinfo.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-search.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-latestposts.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-listingbooktour.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-listings.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-listingscrolltocontact.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-listingssearch.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-listingssocial.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-contactinfo.php';
	require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-testimonials.php';
	
	if(class_exists('ctIdxPro')) {
		require plugin_dir_path( __FILE__ ) . 'widgets/ct-widget-idxmlscompliance.php';
	}

?>