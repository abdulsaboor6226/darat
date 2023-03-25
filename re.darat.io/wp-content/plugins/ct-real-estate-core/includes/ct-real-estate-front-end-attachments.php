<?php

/*-----------------------------------------------------------------------------------*/
/* Front End Set Attachment As Featured */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_set_attachment_featured')) {
	function ct_set_attachment_featured( $post ) {
	    $msg = 'Attachment ID [' . $_POST['att_ID'] . '] set as featured!';
	    if( set_post_thumbnail($_POST['post_ID'], $_POST['att_ID'])) {
	        echo esc_html($msg);
	    }
	    die();
	}
}
add_action( 'wp_ajax_ct_set_attachment_featured', 'ct_set_attachment_featured' );

/*-----------------------------------------------------------------------------------*/
/* Front End Image Upload */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_front_img_upload')) {
	function ct_front_img_upload( $post ) {
		if (empty($_FILES) || $_FILES['file']['error']) { die('{"OK": 0, "info": "Failed to move uploaded file."}'); }

		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
		$wp_upload_dir = wp_upload_dir();
		$filePath = $wp_upload_dir['path'].'/'.$fileName;
		$filePath2 = $wp_upload_dir['url'].'/'.$fileName;


		// Open temp file
		$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
		  $in = @fopen($_FILES['file']['tmp_name'], "rb");

		  if ($in) {
		    while ($buff = fread($in, 4096))
		      {fwrite($out, $buff);}
		  } else
		    {die('{"OK": 0, "info": "Failed to open input stream."}');}

		  @fclose($in);
		  @fclose($out);

		  @unlink($_FILES['file']['tmp_name']);
		} else
		  {die('{"OK": 0, "info": "Failed to open output stream."}');}

		$name=$filePath2.'.part';
		if(!$chunks || $chunk == $chunks - 1) {
		  $name=$filePath;
		  rename($filePath.".part", $filePath);

			$filename2 = $filePath;
			$filetype = wp_check_filetype( basename( $filename2 ), null );
			$wp_upload_dir = wp_upload_dir();
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename2 ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename2 ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
			if(isset($_GET['postid'])) {$attach_id = wp_insert_attachment( $attachment, $filename2, $_GET['postid'] );}
			else {$attach_id = wp_insert_attachment( $attachment, $filename2 );}
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename2 );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			if(is_int($attach_id)){
				$link=wp_get_attachment_url($attach_id);
				die('{"jsonrpc" : "2.0", "success" : true, "id" : "id", "id_att" : "'.$attach_id.'", "link" : "'.$link.'"}');
			}
			else {die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Error uplaoding file."}, "id" : "id"}');}
		}

		die('{"OK": 1, "info": "Upload successful.", "link": "'.$name.'"}');
	}
}
add_action( 'wp_ajax_ct_front_img_upload', 'ct_front_img_upload' );

/*-----------------------------------------------------------------------------------*/
/* Front End Delete Attachment */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_delete_attachment_edit')) {
	function ct_delete_attachment_edit( $post ) {
	    if( wp_delete_attachment( $_POST['att_ID'], true )) {
	        echo 'Attachment ID [' . $_POST['att_ID'] . '] has been deleted!';
	    }
	    die();
	}
}
add_action( 'wp_ajax_ct_delete_attachment_edit', 'ct_delete_attachment_edit' );