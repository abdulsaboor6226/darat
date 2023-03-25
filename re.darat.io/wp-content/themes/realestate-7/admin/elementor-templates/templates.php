<?php

defined( 'ABSPATH' ) || die();

?>

<script type="text/template" id="tmpl-contempo-template-library-header-preview">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ contempo.editor.templates.layout.getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-contempo-template-library-template-remote">
	<div class="elementor-template-library-template-body">
		<# if ( 'page' === type ) { #>
			<div class="elementor-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
		<# } else { #>
			<img src="{{ thumbnail }}" />
		<# } #>
		<div class="elementor-template-library-template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
	</div>
	<div class="elementor-template-library-template-footer">
		{{{ contempo.editor.templates.layout.getTemplateActionButton( obj ) }}}
		<div class="elementor-template-library-template-name">{{{ title }}}</div>
	</div>
</script>

<script type="text/template" id="tmpl-contempo-template-library-templates-unlicensed">
	<div class="ct_unlicensed">
		<div class="ct_unlicensed__bg">
			<img src="<?php echo CT_ET_URL . '/assets/upsell.jpg'; ?>" srcset="<?php echo CT_ET_URL . '/assets/upsell.jpg'; ?> 1x, <?php echo CT_ET_URL . '/assets/upsell@2x.jpg'; ?> 2x" />
		</div>
		<div class="ct_unlicensed__title">
			<?php _e('200+ Professionally Designed<br/>Blocks & Pages' , 'contempo'); ?>
		</div><div class="ct_unlicensed__text">
			<?php esc_html_e( 'Exclusive to Real Estate 7 Yearly utilize our collection of 200+ professionally designed Elementor blocks & pages, ready-to-use, click to insert, drag and drop, swap in your content, customize, and build pages quickly and easily — all while looking like a seasoned professional.' , 'contempo'); ?>
		</div>
		<div class="ct_unlicensed__buttons">
			<a class="ct_unlicensed__buttons-item ct_unlicensed__buttons-item--primary" href="https://contempothemes.com/wp-real-estate-7/checkout/?edd_action=add_to_cart&download_id=9797" target="_blank"><?php esc_html_e( 'Buy Now', 'contempo'); ?></a>
			<a class="ct_unlicensed__buttons-item" href="<?php echo admin_url('themes.php?page=realestate-7-license'); ?>"><?php esc_html_e( 'Enter License', 'contempo'); ?></a>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-contempo-template-library-templates">
	<#
		var activeSource = contempo.editor.templates.getFilter('source');
	#>
	<div id="elementor-template-library-toolbar">
		<#
			var activeType = contempo.editor.templates.getFilter('type');
			var config = contempo.editor.templates.getConfig( 'categories-' +  activeType );
			var categories = config && Array.isArray(config) ? config : [];
			#>
			<div id="elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar">
				<# if ( categories && categories.length > 0) { #>
					<div id="elementor-template-library-filter">
						<select id="elementor-template-library-filter-subtype" class="elementor-template-library-filter-select" data-elementor-filter="category">
							<option value=""><?php echo __( 'All', 'contempo' ); ?></option>
							<# categories.forEach( function( category ) {
								var selected = category === contempo.editor.templates.getFilter( 'category' ) ? ' selected' : '';
								#>
								<option value="{{ category }}"{{{ selected }}}>{{{ category }}}</option>
							<# } ); #>
						</select>
					</div>
				<# } #>
			</div>
		
		<div id="elementor-template-library-filter-text-wrapper">
			<label for="elementor-template-library-filter-text" class="elementor-screen-only"><?php echo __( 'Search Templates:', 'contempo' ); ?></label>
			<input id="elementor-template-library-filter-text" placeholder="<?php echo esc_attr__( 'Search', 'contempo' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>
	<div id="elementor-template-library-templates-container"></div>
	
	<div id="elementor-template-library-footer-banner">
			<img class="elementor-nerd-box-icon" src="<?php echo ELEMENTOR_ASSETS_URL . 'images/information.svg'; ?>" />
			<div class="elementor-excerpt"><?php echo __( 'Stay tuned! More awesome templates coming real soon.', 'contempo' ); ?></div>
	</div>
</script>
