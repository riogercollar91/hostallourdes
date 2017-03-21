(function()
{
	function activate_editor( textarea_id )
	{
		var $textarea = jQuery( 'textarea#' + textarea_id );

		// stops when textarea can't be found

		if ( $textarea.length === 0 )
		{
			return false;
		};

		// checks if textarea is part of wp editor

		var $wp_editor = jQuery( '#wp-' + textarea_id + '-wrap' );

		if ( $wp_editor.length == 0 )
		{
			return false;
		}

		// switches to text view when mce is active

		if ( $wp_editor.hasClass( 'tmce-active' ) )
		{
			if ( typeof tinymce !== 'undefined' )
			{
				var mce = tinymce.get( textarea_id );

				if ( mce && mce.initialized )
				{
					switchEditors.go( textarea_id, 'html' );
				}

				else
				{
					// TODO : unbind event handlers

					tinymce.on( 'SetupEditor', function( editor )
					{
						if ( editor.id == 'content' )
						{
							editor.on( 'init', function()
							{
								switchEditors.go( textarea_id, 'html' );
							});
						}
					});
				}
			};
		};

		// creates editor

		var $wrap = $wp_editor.closest( '#postdivrich' );
		
		var $mmtl_wrap = jQuery( '<div class="postbox"></div>' );

		$mmtl_wrap.insertAfter( $wrap.hide() );

		MMTL_Editor.create( $mmtl_wrap, textarea_id, MMTL_Options );

		jQuery( 'body' ).removeClass( 'mmtl-inactive' ).addClass( 'mmtl-active' );

		return true;
	};

	function deactivate_editor( textarea_id )
	{
		var $mmtl_wrap = jQuery( '#mmtl-' + textarea_id + '-wrap' );

		// checks if active

		if ( $mmtl_wrap.length == 0 )
		{
			return false;
		};

		// checks if textarea is part of wp editor

		var $wp_editor = jQuery( '#wp-' + textarea_id + '-wrap' ), $wrap;

		if ( $wp_editor.length == 0 )
		{
			return false;
		};

		$wrap = $wp_editor.closest( '#postdivrich' );

		// sets 'Visual' view.
		// also cause of problem: sometimes 'Visual' view mixed with 'Text' view
		// changing view fixes this

		switchEditors.go( textarea_id, 'html' );
		switchEditors.go( textarea_id, 'tmce' );

		// removes editor

		MMTL_Editor.remove( textarea_id );

		$mmtl_wrap.remove();

		$wrap.show();

		jQuery( 'body' ).removeClass( 'mmtl-active' ).addClass( 'mmtl-inactive' );

		return true;
	};

	function do_request( args, done )
	{
		args = jQuery.extend( args,
		{
			[ MMTL_Options.noncename ] : MMTL_Options.nonce
		});

		return jQuery.post( MMTL_Options.ajaxurl, args, done );
	};

	jQuery( document ).ready(function()
	{
		jQuery( '.mmtl-activate' ).on( 'click', function( event )
		{
			var active = activate_editor( MMTL_Options.post_editor_id );

			// saves editor state

			if ( active )
			{
				do_request( { action : 'mmtl_set_editor_state', post_id : MMTL_Options.post_id, active : 1 } );
			};

			return false;
		});

		jQuery( '.mmtl-deactivate' ).on( 'click', function( event )
		{
			var inactive = deactivate_editor( MMTL_Options.post_editor_id );

			// saves editor state
			
			if ( inactive ) 
			{
				do_request( { action : 'mmtl_set_editor_state', post_id : MMTL_Options.post_id, active : 0 } );
			};

			return false;
		});

		if ( jQuery( 'body' ).hasClass( 'mmtl-active' ) )
		{
			activate_editor( MMTL_Options.post_editor_id );
		}
	});

})();