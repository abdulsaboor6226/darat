<?php
/**
 * Ajax single listing template preloader
 */
if ( ! defined('ABSPATH')) {
	exit;
}
global $ct_options;

$content_type = $ct_options['ct_single_listing_content_layout_type'];

?>
<!-- LISTING INFO TAB CONTINUATION-->
<div class="re7-modal-preloader-row" style="margin-bottom: 50px;">
	<div class="re7-modal-preloader-column full-width">
		<div class="re7-modal-preloader-base" style="height: 37.5px; margin-bottom: 20px;"></div>
		<div class="re7-modal-preloader-base" style="width: 95%"></div>
		<div class="re7-modal-preloader-base" style="width: 75%"></div>
		<div class="re7-modal-preloader-base" style="width: 85%"></div>
		<div class="re7-modal-preloader-base" style="width: 95%"></div>
		<div class="re7-modal-preloader-base" style="width: 75%"></div>
	</div>
</div>
<!-- LISTING INFO TAB CONTINUATION END -->
<?php if ( "accordion" === $content_type ): ?>
<!-- LISTING INFO TAB CONTINUATION (ITEMIZED)-->
<div class="re7-modal-preloader-row">
	<div class="re7-modal-preloader-column" style="width: 18%;">
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 7.5%;">
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
		<div class="re7-modal-preloader-base" style="margin-bottom: 50px;"></div>
	</div>
</div>
<!-- LISTING INFO TAB CONTINUATION (ITEMIZED) END -->
<?php endif; ?>

<!--- IDX Features List -->

<?php if ( "default" === $content_type ): ?>
<div class="re7-modal-preloader-row">
	<div class="re7-modal-preloader-column" style="width: 18%;">
		<div class="re7-modal-preloader-base"></div>
	</div>
</div>
<div class="re7-modal-preloader-row">
	<div class="re7-modal-preloader-column" style="width: 18%;">
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column">
		<div class="re7-modal-preloader-row flex-align-center">
			<div class="re7-modal-preloader-column" style="width: 38%; margin-right: 10px;">
				<div class="re7-modal-preloader-base"></div>
				<div class="re7-modal-preloader-base"></div>
				<div class="re7-modal-preloader-base"></div>
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 38%;">
				<div class="re7-modal-preloader-base"></div>
				<div class="re7-modal-preloader-base"></div>
				<div class="re7-modal-preloader-base"></div>
				<div class="re7-modal-preloader-base"></div>

			</div>
		</div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 18%;">
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>

	</div>
</div>
<!--- IDX Features List End -->

<!-- Virtual Tour -->
<div class="re7-modal-preloader-row">
	<div class="re7-modal-preloader-column full-width">
		<div class="re7-modal-preloader-base" style="width: 18%; margin-bottom: 5px;"></div>
	</div>
</div>
<div class="re7-modal-preloader-row">
	<div class="re7-modal-preloader-column full-width">
		<div class="re7-modal-preloader-base" style="height: 300px;"></div>
	</div>
</div>
<!-- Virtual Tour End -->
<!-- TABS WITH INFO
<div class="re7-modal-preloader-row">
	<div class="re7-modal-preloader-column full-width">
		<div class="re7-modal-preloader-base" style="height: 37.5px; margin-bottom: 20px;"></div>
		<div class="re7-modal-preloader-base" style="width: 95%"></div>
		<div class="re7-modal-preloader-base" style="width: 75%"></div>
		<div class="re7-modal-preloader-base" style="width: 85%"></div>
		<div class="re7-modal-preloader-base" style="width: 95%"></div>
		<div class="re7-modal-preloader-base" style="width: 75%"></div>
	</div>
</div>
 TABS WITH INFO END -->

<!-- Whats Nearby -->
<div class="re7-modal-preloader-row">
	<div class="re7-modal-preloader-column" style="width: 25%;">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 18%">
		<div class="re7-modal-preloader-base" ></div>
	</div>
</div>

<!--{-->
<div class="re7-modal-preloader-row flex-align-start" style="margin-bottom: 10px;">
	<div class="re7-modal-preloader-column" style="width: 20px; margin-right: 10px;">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 15%;">
		<div class="re7-modal-preloader-base"></div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-spacer"></div>
<!--}-->

<!--{-->
<div class="re7-modal-preloader-row flex-align-start" style="margin-bottom: 10px;">
	<div class="re7-modal-preloader-column" style="width: 20px; margin-right: 10px;">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 15%;">
		<div class="re7-modal-preloader-base"></div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-spacer"></div>
<!--}-->

<!--{-->
<div class="re7-modal-preloader-row flex-align-start" style="margin-bottom: 10px;">
	<div class="re7-modal-preloader-column" style="width: 20px; margin-right: 10px;">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 15%;">
		<div class="re7-modal-preloader-base"></div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-row no-base-line">
	<div class="re7-modal-preloader-column" style="width: 28%">
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 165px">
		<div class="re7-modal-preloader-row no-base-line">
			<div class="re7-modal-preloader-column" style="width: 60px">
				<div class="re7-modal-preloader-base"></div>
			</div>
			<div class="re7-modal-preloader-column" style="width: 60px;">
				<div class="re7-modal-preloader-base"></div>
			</div>
		</div>
	</div>
</div>
<div class="re7-modal-preloader-spacer"></div>
<!--}-->

<!-- TABS WITH INFO END -->
    <div class="re7-modal-preloader-spacer"></div>
    <div class="re7-modal-preloader-spacer"></div>
    <div class="re7-modal-preloader-spacer"></div>
    <div class="re7-modal-preloader-spacer"></div>
    <div class="re7-modal-preloader-spacer"></div>
<?php endif ?>
<!-- REQUEST MORE INFO -->


<div class="re7-modal-preloader-row">
	<div class="re7-modal-preloader-column" style="width: 28%;">
		<div class="re7-modal-preloader-base" style="margin-bottom: 40px;"></div>
		<div class="re7-modal-preloader-base" style="height: 146px; margin-bottom: 18px;"></div>
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>
		<div class="re7-modal-preloader-base"></div>
	</div>
	<div class="re7-modal-preloader-column" style="width: 69.5%;">
		<!-- 2nd column -->
		<div class="re7-modal-preloader-base" style="margin-bottom: 40px; width: 75%;"></div>
		<div class="re7-modal-preloader-base" style="height: 36px;"></div>
		<div class="re7-modal-preloader-base" style="height: 36px;"></div>
		<div class="re7-modal-preloader-base" style="height: 36px;"></div>
		<div class="re7-modal-preloader-base" style="height: 36px;"></div>
		<div class="re7-modal-preloader-base" style="height: 75px;"></div>
		<div class="re7-modal-preloader-base" style="height: 36px; width: 25%; margin-top: 20px;"></div>
	</div>
</div>
<div class="re7-modal-preloader-spacer"></div>
<div class="re7-modal-preloader-spacer"></div>
<!-- REQUEST MORE INFO END -->