jQuery( function ( $ ) {
	let mediaUploader;
	$( '.media-upload' ).on( 'click', function ( e ) {
		e.preventDefault();
		if ( mediaUploader ) {
			mediaUploader.open();
			return;
		}
		mediaUploader = wp.media( {
			title: 'Insert image',
			button: {
				text: 'Use this image',
			},
			library: {
				type: 'image',
			},
			multiple: false,
		} );
		mediaUploader.on( 'select', function () {
			const images = mediaUploader.state().get( 'selection' );
			images.each( function ( data ) {
				$( '#media' ).val( '' );
				$( '.image-preview' ).empty();
				$( '#media' ).val( data.attributes.url );
				$( '.image-preview' ).append(
					'<img src=" ' +
						data.attributes.url +
						' " style="max-width: 300px;" >'
				);
			} );
		} );
		mediaUploader.open();
	} );

	$( '.media-remove' ).on( 'click', function () {
		$( '#media' ).val( '' );
		$( '.image-preview' ).empty();
	} );
} );
