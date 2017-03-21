(function()
{

/*
INDEX
-----
- General
- Sticky header
- Full Screen
- Component Toggle
- Component Sorting
- Media
- Row Shortcode
- Column Shortcode

------------------------------------------------------------------------------------------------------------------------
 General
------------------------------------------------------------------------------------------------------------------------
*/

MMTL_Editor.eventManager.add( 'setup', function( event, ed )
{
	// parses content before import

	ed.add_filter( 'source_content', function( content )
	{
		// removes starting empty paragraph
		content = content.replace( /^\s*<p>\s*<\/p>/gi, '' );

		// removes paragraphs around shortcodes
		content = content.replace( /<p>\s*(\[)/ig, '$1' );
		content = content.replace( /(\])\s*<\/p>/ig, '$1' );

		return content;

	}, 1 );

	// translates shortcode callback return value to component

	ed.add_filter( 'shortcode_replacement', function( replacement, shortcode )
	{
		return ed.components.create( shortcode, replacement );
		
	}, 15 );

	ed.on( 'component_added', function( event, component )
	{
		ed.updateSource();
	});

	ed.on( 'component_removed', function( event, component )
	{
		ed.updateSource();
	});

	// control click

	ed._elem.on( 'click', 'a.mmtl-control', function( event )
	{
		var control = ed.controls.get( jQuery(this).data( 'type' ) );

		var $component = jQuery(this).closest( '.mmtl-component' );

		control.click( $component );

		jQuery( this ).blur();

		return false;
	});

	// add component button

	ed.on( 'init', function( event )
	{
		ed._elem.on( 'click', '.mmtl-add-component-button', function( event )
		{
			var $button = jQuery( this );

			$button.blur();

			var tag = $button.data( 'type' );

			if ( ed.shortcodes.hasTag( tag ) )
			{
				var shortcode = new wp.shortcode( { tag : tag } );

				var component = ed.components.create( shortcode );

				ed.components.add( component, null );
			};

			return false;
		});
	});

	// controls

	ed.add_control( 'edit',
	{
		text : ed.option( 'control_label_edit' ),
		title : ed.option( 'control_label_edit' ),
		icon : 'pencil',
		click : function( component )
		{ 
			var shortcode = ed.components.getShortcode( component );

			ed.screens.render( 'edit_' + shortcode.tag, component );
		}
	});

	ed.add_control( 'copy',
	{
		text : ed.option( 'control_label_copy' ),
		title : ed.option( 'control_label_copy' ),
		icon : 'duplicate',
		click : function( component )
		{
			var copy = ed.components.copy( component );

			ed.components.add( copy, jQuery( component ).parent(), jQuery( component ).index() + 1 );
		}
	});

	ed.add_control( 'delete',
	{
		text : ed.option( 'control_label_delete' ),
		title : ed.option( 'control_label_delete' ),
		icon : 'bin',
		click : function( component )
		{
			if ( ! window.confirm( ed.options.get( 'confirm_delete' ) ) )
			{
				return;
			};

			ed.components.remove( component );
		}
	});

	ed.add_control( 'add_before',
	{
		text : ed.option( 'control_label_add' ),
		title : ed.option( 'control_label_add' ),
		icon : 'plus',
		click : function( component )
		{
			var $component = jQuery( component );
			var shortcode = ed.components.getShortcode( $component );

			var sc = new wp.shortcode( { tag : shortcode.tag } );
			var c = ed.components.create( sc );

			ed.components.add( c, $component.parent(), $component.index() );
		}
	});

	ed.add_control( 'add_after',
	{
		text : ed.option( 'control_label_add' ),
		title : ed.option( 'control_label_add' ),
		icon : 'plus',
		click : function( component )
		{
			var $component = jQuery( component );
			var shortcode = ed.components.getShortcode( $component );

			var sc = new wp.shortcode( { tag : shortcode.tag } );
			var c = ed.components.create( sc );

			ed.components.add( c, $component.parent(), $component.index() + 1 );
		}
	});

	ed.add_filter( 'default_options', function( options )
	{
		return jQuery.extend( options,
		{
			confirm_delete : 'Are you sure you want to delete this component?',
			control_label_add    : 'Add',
			control_label_edit   : 'Edit',
			control_label_copy   : 'Copy',
			control_label_delete : 'Delete'
		});
	});
});

/*
------------------------------------------------------------------------------------------------------------------------
 Sticky header
------------------------------------------------------------------------------------------------------------------------
*/

function stickHeader( ed, stick )
{
	if ( typeof stick === 'undefined' )
	{
		stick = true;
	};

	var $header = ed._elem.find( '.mmtl-header' )

	if ( ! stick )
	{
		$header.trigger( 'sticky_kit:detach' )

		ed._elem.removeClass( 'mmtl-header-stuck' )

		return;
	};

	$header
		.stick_in_parent(
		{
			offset_top: jQuery( '#wpadminbar' ).outerHeight(),
			sticky_class : 'mmtl-stuck'
		})

		.on( 'sticky_kit:stick', function( e )
		{
			ed._elem.addClass( 'mmtl-header-stuck' );
		})

		.on( 'sticky_kit:unstick', function( e )
		{
			ed._elem.removeClass( 'mmtl-header-stuck' );
		});
}

MMTL_Editor.eventManager.add( 'setup', function( event, ed )
{
	ed.on( 'init', function( event )
	{
		stickHeader( ed );
	});
	
	ed.on( 'fullscreen_toggle', function( event, active )
	{
		if ( active )
		{
			stickHeader( ed, false );
		}

		else
		{
			stickHeader( ed );
		}
		
	});
});

/*
------------------------------------------------------------------------------------------------------------------------
 Full Screen
------------------------------------------------------------------------------------------------------------------------
*/

function fullScreenKeyPress( event )
{
	var ed = event.data.ed;

	if ( event.keyCode == 27 )
	{
		toggleFullScreen( ed );
	};
}

function toggleFullScreen( ed )
{
	var $control = ed._elem.find( '.mmtl-control[data-type="fullscreen"]' );

	var $overlay = jQuery( '#mmtl-overlay' );

	if ( ! jQuery( 'body' ).hasClass( 'mmtl-full-screen' ) )
	{
		$overlay.data( 'mmtl_editor',
		{
			wrap : ed._elem.parent()
		});

		$overlay.empty().append( ed._elem );

		jQuery( 'body' ).addClass( 'mmtl-full-screen' );

		$control.find( '.glyphicons' )
			.removeClass( 'glyphicons-fullscreen' )
			.addClass( 'glyphicons-fit-frame-to-image' );

		jQuery( document ).on( 'keyup', { ed : ed }, fullScreenKeyPress );

		ed.trigger( 'fullscreen_toggle', [ true ] );
	}

	else
	{
		var data = $overlay.data( 'mmtl_editor' );

		data.wrap.append( ed._elem );

		jQuery.removeData( $overlay, 'mmtl_editor' );

		$overlay.empty();

		$control.find( '.glyphicons' )
			.removeClass( 'glyphicons-fit-frame-to-image' )
			.addClass( 'glyphicons-fullscreen' );

		jQuery( 'body' ).removeClass( 'mmtl-full-screen' );

		jQuery( document ).off( 'keyup', fullScreenKeyPress );

		ed.trigger( 'fullscreen_toggle', [ false ] );
	}
}

MMTL_Editor.eventManager.add( 'setup', function( event, ed )
{
	ed.add_control( 'fullscreen',
	{
		text : ed.option( 'control_label_fullscreen' ),
		title : ed.option( 'control_label_fullscreen' ),
		icon : 'fullscreen',
		click : function()
		{
			toggleFullScreen( ed );
		}
	});

	ed.add_filter( 'header_controls', function( controls )
	{
		controls.push( 'fullscreen' );

		return controls;
	}, 15 );

	ed.on( 'init', function()
	{
		jQuery( 'body' ).append( '<div id="mmtl-overlay"></div>' );
	});

	ed.on( 'destroy', function()
	{
		jQuery( 'body' ).find( '> #mmtl-overlay' ).remove();
	});

	ed.add_filter( 'default_options', function( options )
	{
		return jQuery.extend( options,
		{
			control_label_fullscreen : 'Toggle full screen'
		});
	});
});

/*
------------------------------------------------------------------------------------------------------------------------
 Component Toggle
------------------------------------------------------------------------------------------------------------------------
*/

MMTL_Editor.eventManager.add( 'setup', function( event, ed )
{
	ed.add_control( 'toggle',
	{
		text : ed.option( 'control_label_toggle' ),
		title : ed.option( 'control_label_toggle' ),
		icon : 'chevron-up'
	});

	ed.on( 'init', function( event )
	{
		ed._elem.on( 'click', '.mmtl-component-header, .mmtl-control[data-type="toggle"]', function( event )
		{
			event.stopPropagation();

			var $component = jQuery(this).closest( '.mmtl-component' );

			var $control = $component.find( '> .mmtl-component-inner > .mmtl-component-header .mmtl-control[data-type="toggle"]' );

			if ( $component.hasClass( 'mmtl-closed' ) )
			{
				$component.removeClass( 'mmtl-closed' );

				$control.find( '.glyphicons' )
					.removeClass( 'glyphicons-chevron-down' )
					.addClass( 'glyphicons-chevron-up' );
			}

			else
			{
				$component.addClass( 'mmtl-closed' );

				$control.find( '.glyphicons' )
					.removeClass( 'glyphicons-chevron-up' )
					.addClass( 'glyphicons-chevron-down' );
			};

			return false;
		});
	});

	ed.add_filter( 'default_options', function( options )
	{
		return jQuery.extend( options,
		{
			control_label_toggle : 'Toggle'
		});
	});
});

/*
------------------------------------------------------------------------------------------------------------------------
 Component Sorting
------------------------------------------------------------------------------------------------------------------------
*/

function setComponentSorting( ed )
{
	ed._elem.find( '.mmtl-editor .ui-sortable' ).sortable( 'destroy' );

	var defaults =
	{
		connectWith : '',
		placeholder : 'mmtl-placeholder',

		start : function( event, ui )
		{
			jQuery( ui.placeholder )
				.append( '<div class="mmtl-placeholder-inner"><div class="mmtl-placeholder-content"></div></div>' );

			ed._elem.addClass( 'mmtl-is-sorting' );
		},

		update : function( event, ui )
		{
			ed.updateSource();
		},

		sort : function( event, ui )
		{
			var $component = jQuery( ui.item );

			// treats placeholder like a component
			jQuery( ui.placeholder )
				.removeClass()
				.addClass( 'mmtl-placeholder' )
				.addClass( $component.attr('class') )
					.removeClass( 'ui-sortable-handle' )
					.attr( 'data-type', $component.attr( 'data-type' ) )
					.find( '.mmtl-placeholder-inner' )
						.height( $component.innerHeight() )
		},

		stop : function( event, ui )
		{
			jQuery( ui.placeholder ).empty();

			var $component = jQuery( ui.item );

			ed._elem.removeClass( 'mmtl-is-sorting' );
		}
	};

	// TODO : not OOP

	var $rows = jQuery( '.mmtl-content' );

	$rows.sortable( defaults ).disableSelection();

	var $cols = ed._elem.find( '.mmtl-component[data-type="mmtl-row"] > .mmtl-component-inner > .mmtl-component-content' );

	$cols.sortable( jQuery.extend( defaults, { connectWith : $cols } ) ).disableSelection();
}

MMTL_Editor.eventManager.add( 'setup', function( event, ed )
{
	ed.on( 'init', function( event )
	{
		setComponentSorting( ed );
	});

	ed.on( 'update', function( event )
	{
		setComponentSorting( ed );
	});

	ed.on( 'component_added', function( event )
	{
		setComponentSorting( ed );
	});
});

/*
------------------------------------------------------------------------------------------------------------------------
 Media
------------------------------------------------------------------------------------------------------------------------
*/

MMTL_Editor.eventManager.add( 'setup', function( event, ed )
{
	ed.on( 'screen', function( event, $elem, screens )
	{
		$elem.find( '.mmtl-media' ).each(function()
		{
			var $wrap = jQuery( this );

			var $addButton = $wrap.find( '.mmtl-media-add' );
			var $removeButton = $wrap.find( '.mmtl-media-remove' );
			var $field = $wrap.find( '.mmtl-media-field' );
			var $image = $wrap.find( '.mmtl-media-image' );

			$addButton.on( 'click', function( event )
			{
				// called when user has selected media item
				wp.media.editor.send.attachment = function( properties, attachment )
		        {
		        	// TODO : check mime type

		        	// sets url
		        
		        	var size = attachment.sizes[ properties.size ];

		        	$field.val( size.url );

		        	// sets preview

		        	var thumbnail = attachment.sizes.thumbnail;

					$wrap.addClass( 'mmtl-has-image' );
								
					$image
						.removeClass( 'mmtl-image-h mmtl-image-v' )
						.addClass( thumbnail.width > thumbnail.height ? 'mmtl-image-h' : 'mmtl-image-v' )
						.attr( 'src', thumbnail.url )
						.show();
		        };

	        	wp.media.editor.open( 'mmtl_editor_bg_image' );

				return false;
			});

			$removeButton.on( 'click', function( event )
			{
				$image
					.removeAttr( 'src' )
					.hide();

				$wrap.removeClass( 'mmtl-has-image' );

				$field.val('');

				return false;
			});

			if ( $field.val() )
			{
				var url = $field.val();

				ed.doAjax( 'get_attachment_sizes', { attachment : url }, function( response )
				{
					if ( response.success )
					{
						var thumbnail = response.data.thumbnail;

						$wrap.addClass( 'mmtl-has-image' );
						
						$image
							.removeClass( 'mmtl-image-h mmtl-image-v' )
							.addClass( thumbnail.width > thumbnail.height ? 'mmtl-image-h' : 'mmtl-image-v' )
							.attr( 'src', thumbnail.file )
							.show();
					};
				});
			};
		});
	});
});

/*
------------------------------------------------------------------------------------------------------------------------
 Row Shortcode
------------------------------------------------------------------------------------------------------------------------
*/

function getColumnLayout( row, ed )
{
	var $cols = jQuery( row ).find( '> .mmtl-component-inner > .mmtl-component-content > .mmtl-component[data-type="mmtl-col"]' );

	var layout = [], shortcode;

	jQuery.each( $cols, function( i, col )
	{
		shortcode = ed.components.getShortcode( col );
			
		var width = shortcode.get( 'width' );

		if ( ! width )
		{
			return true;
		};

		layout.push( width );
	});

	return layout.join( ' + ' );
};

function setColumnLayout( row, layout, ed )
{
	if ( layout )
	{
		layout = layout.replace( /\s+/g, '' ).split( '+' );
	}

	else
	{
		layout = [];
	};

	var $cols = jQuery( row ).find( '> .mmtl-component-inner > .mmtl-component-content > .mmtl-component[data-type="mmtl-col"]' );
	var col, shortcode;

	jQuery.each( layout, function( i, width )
	{
		// updates column

		if ( i < $cols.length )
		{
			col = $cols.eq( i );
			
			shortcode = ed.components.getShortcode( col );

			shortcode.set( 'width', width );

			ed.components.setShortcode( col, shortcode );
		}

		// creates column 

		else
		{
			shortcode = new wp.shortcode(
			{
				tag : 'mmtl-col',
				attrs :
				{
					width : width
				},
				type : 'closed'
			});

			col = ed.components.create( shortcode );

			ed.components.add( col, row );
		};
	});

	// deletes columns

	jQuery.each( $cols.filter( ':gt(' + ( layout.length - 1 ) + ')' ), function()
	{
		ed.components.remove( this );
	});
};

MMTL_Editor.eventManager.add( 'setup', function( event, ed )
{
	ed.add_shortcode( 'mmtl-row', function( attrs, content )
	{
		return ed.do_shortcode( content );
	});

	ed.add_filter( 'component_controls', function( controls, shortcode )
	{
		if ( shortcode.tag == 'mmtl-row' )
		{
			controls.push( 'add_before', 'edit', 'copy', 'delete', 'add_after', 'toggle' );
		};

		return controls;
	}, 5);

	ed.add_screen( 'edit_mmtl-row',
	{
		render : function( component )
		{
			var shortcode = ed.components.getShortcode( component );

			var data =
			{
				id : shortcode.get( 'id' ) || '',
				class : shortcode.get( 'class' ) || '',
				bg_image : shortcode.get( 'bg_image' ) || '',
				bg_position : shortcode.get( 'bg_position' ) || '',
				bg_repeat : shortcode.get( 'bg_repeat' ) || '',
				bg_size : shortcode.get( 'bg_size' ) || '',
				layout : getColumnLayout( component, ed )
			};

			return wp.template( 'mmtl-row-settings' )( data );
		},

		load : function( $wrap )
		{
			$wrap.find( '.mmtl-layout a' ).click( function( event )
			{
				var $button = jQuery( this );

				var layout = $button.attr( 'title' );

				$wrap.find( 'input[name="layout"]' ).val( layout );

				$wrap.find( 'a' ).removeClass( 'mmtl-active' );

				$button.addClass( 'mmtl-active' );

				return false;
			});

			var layout = $wrap.find( 'input[name="layout"]' ).val();
			
			$wrap.find( '.mmtl-layout a[title="' + layout + '"]' ).addClass( 'mmtl-active' );
		},

		submit : function( input, component )
		{
			var shortcode = ed.components.getShortcode( component );

			shortcode.set( 'id', input.id );
			shortcode.set( 'class', input.class );
			shortcode.set( 'bg_image', input.bg_image );
			shortcode.set( 'bg_position', input.bg_position );
			shortcode.set( 'bg_repeat', input.bg_repeat );
			shortcode.set( 'bg_size', input.bg_size );

			setColumnLayout( component, input.layout, ed );

			return shortcode;
		}
	});

	ed.add_filter( 'component', function( component )
	{
		// adds component class 

		var shortcode = ed.components.getShortcode( component );

		if ( shortcode.tag == 'mmtl-row' )
		{
			$component = jQuery( component );

			$component
				.find( '> .mmtl-component-inner > .mmtl-component-content' )
					.addClass( 'mmtl-row' )

			component = MMTL_Editor.helpers.toHTML( $component );
		};

		return component;
	});

	ed.add_filter( 'component_meta', function( meta, shortcode )
	{
		if ( shortcode.tag == 'mmtl-row' )
		{
			// backgroung image

			var bg_image = shortcode.get( 'bg_image' );

			if ( bg_image )
			{
				// adds placeholder

				meta.push( { title : ed.option( 'meta_title_bg_image' ), type : 'bg_image', text : '' } );
			};

			// id

			var id = shortcode.get( 'id' );

			if ( id )
			{
				meta.push( { title: ed.option( 'meta_title_id' ), type : 'id', text : id } );
			};

			// class

			var htmlClass = shortcode.get( 'class' );

			if ( htmlClass )
			{
				jQuery.each( htmlClass.split( ' ' ), function( i, text )
				{
					meta.push( { title : ed.option( 'meta_title_class' ), type : 'class', text : text } );
				});
			};
		};

		return meta;
	});

	ed.add_filter( 'default_options', function( options )
	{
		return jQuery.extend( options,
		{
			meta_title_id       : 'ID',
			meta_title_class    : 'Class',
			meta_title_bg_image : 'Background image'
		});
	});

 	// loads background image preview

	ed.on( 'update', function()
	{
		var urls = {}, $component, shortcode;

		// gets urls from rows

		ed._elem.find( '.mmtl-component[data-type="mmtl-row"]' ).each( function()
		{
			$component = jQuery( this );

			shortcode = ed.components.getShortcode( $component );

			bg_image = shortcode.get( 'bg_image' );

			if ( ! bg_image || bg_image.indexOf( 'http://' ) !== 0 )
			{
				return true;
			};

			urls[ $component.data( 'id' ) ] = bg_image;
		});

		if ( jQuery.isEmptyObject( urls ) )
		{
			return;
		};

		// gets thumbail url

		var args = { attachment : urls };

		ed.doAjax( 'get_attachment_sizes', args, function( response )
		{
			if ( ! response.success )
			{
				return;
			};

			var sizes = response.data;

			jQuery.each( sizes, function( component_id, data )
			{
				// checks if thumbail exists
				// breaks loop if not. no need to continue when thumbnail is not found

				if ( ! data.hasOwnProperty( 'thumbnail' ) )
				{
					return false;
				};

				$component = ed.components.get( component_id );

				if ( ! $component )
				{
					return true;
				};

				$component.find( '.mmtl-meta[data-type="bg_image"]' )
					.css( 'background-image',  'url(' + data.thumbnail.file + ')' );
			});
		});
	});
	
	ed.add_filter( 'source_content', function( content )
	{
		content = jQuery.trim( content );

		if ( content && content.indexOf( '[mmtl-row' ) !== 0 )
		{
			content = '[mmtl-row][mmtl-col width="1/1"]' + content + '[/mmtl-col][/mmtl-row]';

			ed.on( 'init', function( event )
			{
				ed.updateSource();
			});
		};

		return content;
		
	}, 5 );
});

/*
------------------------------------------------------------------------------------------------------------------------
 Column Shortcode
------------------------------------------------------------------------------------------------------------------------
*/

function getColumnWidth( span )
{
	switch( span )
	{
		case 1  : return '1/12';
		case 2  : return '1/6';
		case 3  : return '1/4';
		case 4  : return '1/3';
		case 5  : return '5/12';
		case 6  : return '1/2';
		case 7  : return '7/12';
		case 8  : return '2/3';
		case 9  : return '3/4';
		case 10 : return '5/6';
		case 11 : return '11/12';
		default : return '1/1';
	}
};

function getColumnSpan( width )
{
	switch( width )
	{
		case '1/12'  : return 1;
		case '1/6'   : return 2;
		case '1/4'   : return 3;
		case '1/3'   : return 4;
		case '5/12'  : return 5;
		case '1/2'   : return 6;
		case '7/12'  : return 7;
		case '2/3'   : return 8;
		case '3/4'   : return 9;
		case '5/6'   : return 10;
		case '11/12' : return 11;
		default : return 12;
	}
};

MMTL_Editor.eventManager.add( 'setup', function( event, ed )
{
	ed.add_shortcode( 'mmtl-col', function( attrs, content )
	{
		return ed.do_shortcode( content );
	});

	ed.add_control( 'col_decrease_width',
	{
		text : ed.option( 'control_label_col_decrease_width' ),
		title : ed.option( 'control_label_col_decrease_width' ),
		icon : 'chevron-left',
		click : function( component )
		{
			var $col = jQuery( component );

			var shortcode = ed.components.getShortcode( $col );

			var span = getColumnSpan( shortcode.get( 'width' ) );

			if ( span <= 1 )
			{
				return;
			};

			$col.removeClass( 'mmtl-col-sm-' + span );

			span--;

			$col.addClass( 'mmtl-col-sm-' + span );

			var width = getColumnWidth( span );

			shortcode.set( 'width', width );

			$col.find( '.mmtl-control[data-type="col_width"]' )
				.text( width );

			ed.updateSource();
		}
	});

	ed.add_control( 'col_increase_width',
	{
		text : ed.option( 'control_label_col_increase_width' ),
		title : ed.option( 'control_label_col_increase_width' ),
		icon : 'chevron-right',
		click : function( component )
		{
			var $col = jQuery( component );

			var shortcode = ed.components.getShortcode( $col );

			var span = getColumnSpan( shortcode.get( 'width' ) );

			if ( span >= 12 )
			{
				return;
			};

			$col.removeClass( 'mmtl-col-sm-' + span );

			span++;

			$col.addClass( 'mmtl-col-sm-' + span );

			var width = getColumnWidth( span );

			shortcode.set( 'width', width );

			$col.find( '.mmtl-control[data-type="col_width"]' )
				.text( width );

			ed.updateSource();
		}
	});

	ed.add_control( 'col_width',
	{
		text : ed.option( 'control_label_col_width' ),
		title : ed.option( 'control_label_col_width' ),
		icon : '',
		click : function(){}
	});

	ed.add_filter( 'component_controls', function( controls, shortcode )
	{
		if ( shortcode.tag == 'mmtl-col' )
		{
			controls.push( 'add_before', 'col_decrease_width', 'col_increase_width', 'col_width', 'edit', 'copy', 'delete', 'add_after', 'toggle' );
		};

		return controls;

	}, 5 );

	ed.add_filter( 'component', function( component )
	{
		var shortcode = ed.components.getShortcode( component );

		if ( shortcode.tag == 'mmtl-col' )
		{
			var $component = jQuery( component );

			$component
				.addClass( 'mmtl-col' )
				.find( '> .mmtl-component-inner > .mmtl-component-content' )
					.addClass( 'mmtl-editor-style' );

			// offset

			if ( value = shortcode.get( 'offset' ) )
			{
				$component.addClass( 'mmtl-col-sm-offset-' + getColumnSpan( value ) );
			}

			// width

			if ( ! shortcode.get( 'width' ) )
			{
				shortcode.set( 'width', '1/1' );
			};

			var width = shortcode.get( 'width' );

			$component.addClass( 'mmtl-col-sm-' + getColumnSpan( width ) );

			$component.find( '.mmtl-control[data-type="col_width"]' )
				.text( width );

			component = $component.get(0);
		};

		return component;
	});

	ed.add_filter( 'component_meta', function( meta, shortcode )
	{
		if ( shortcode.tag == 'mmtl-col' )
		{
			// id

			var id = shortcode.get( 'id' );

			if ( id )
			{
				meta.push( { title: ed.option( 'meta_title_id' ), type : 'id', text : id } );
			};

			// class

			var htmlClass = shortcode.get( 'class' );

			if ( htmlClass )
			{
				jQuery.each( htmlClass.split( ' ' ), function( i, text )
				{
					meta.push( { title : ed.option( 'meta_title_class' ), type : 'class', text : text } );
				});
			};

			// push

			if ( value = shortcode.get( 'push' ) )
			{
				meta.push( { title : ed.option( 'meta_title_push' ), type : 'push', text : value + ' <span class="glyphicons glyphicons-arrow-right"></span> ' } );
			};

			// pull

			if ( value = shortcode.get( 'pull' ) )
			{
				meta.push( { title : ed.option( 'meta_title_pull' ), type : 'pull', text : '<span class="glyphicons glyphicons-arrow-left"></span> ' + value } );
			};
		};

		return meta;
	});

	ed.add_screen( 'edit_mmtl-col',
	{
		render : function( component )
		{
			var shortcode = ed.components.getShortcode( component );

			var data =
			{
				id : shortcode.get( 'id' ) || '',
				class : shortcode.get( 'class' ) || '',
				content : jQuery( component ).find( '> .mmtl-component-inner > .mmtl-component-content' ).html(),
				offset_xs : shortcode.get( 'offset_xs' ) || '',
				offset_sm : shortcode.get( 'offset' ) || '',
				offset_md : shortcode.get( 'offset_md' ) || '',
				offset_lg : shortcode.get( 'offset_lg' ) || '',
				width_xs : shortcode.get( 'width_xs' ) || '',
				width_sm : shortcode.get( 'width' ) || '',
				width_md : shortcode.get( 'width_md' ) || '',
				width_lg : shortcode.get( 'width_lg' ) || '',
				hide_xs : shortcode.get( 'hide_xs' ) || '',
				hide_sm : shortcode.get( 'hide' ) || '',
				hide_md : shortcode.get( 'hide_md' ) || '',
				hide_lg : shortcode.get( 'hide_lg' ) || '',
				push_xs : shortcode.get( 'push_xs' ) || '',
				push_sm : shortcode.get( 'push' ) || '',
				push_md : shortcode.get( 'push_md' ) || '',
				push_lg : shortcode.get( 'push_lg' ) || '',
				pull_xs : shortcode.get( 'pull_xs' ) || '',
				pull_sm : shortcode.get( 'pull' ) || '',
				pull_md : shortcode.get( 'pull_md' ) || '',
				pull_lg : shortcode.get( 'pull_lg' ) || ''
			};

			return wp.template( 'mmtl-col-settings' )( data );
		},
		
		submit : function( input, component )
		{
			var shortcode = ed.components.getShortcode( component );

			shortcode.set( 'id', input.id || '' );
			shortcode.set( 'class', input.class || '' );
			shortcode.set( 'offset_xs', input.offset_xs || '' );
			shortcode.set( 'offset', input.offset_sm || '' );
			shortcode.set( 'offset_md', input.offset_md || '' );
			shortcode.set( 'offset_lg', input.offset_lg || '' );
			shortcode.set( 'width_xs', input.width_xs || '' );
			shortcode.set( 'width', input.width_sm || '' );
			shortcode.set( 'width_md', input.width_md || '' );
			shortcode.set( 'width_lg', input.width_lg || '' );
			shortcode.set( 'hide_xs', input.hide_xs || '' );
			shortcode.set( 'hide', input.hide_sm || '' );
			shortcode.set( 'hide_md', input.hide_md || '' );
			shortcode.set( 'hide_lg', input.hide_lg || '' );
			shortcode.set( 'push_xs', input.push_xs || '' );
			shortcode.set( 'push', input.push_sm || '' );
			shortcode.set( 'push_md', input.push_md || '' );
			shortcode.set( 'push_lg', input.push_lg || '' );
			shortcode.set( 'pull_xs', input.pull_xs || '' );
			shortcode.set( 'pull', input.pull_sm || '' );
			shortcode.set( 'pull_md', input.pull_md || '' );
			shortcode.set( 'pull_lg', input.pull_lg || '' );

			shortcode.content = input.content || '';
			jQuery( component ).find( '> .mmtl-component-inner > .mmtl-component-content' ).html( input.content );

			ed.components.setShortcode( component, shortcode );
		}
	});

	ed.add_filter( 'default_options', function( options )
	{
		return jQuery.extend( options,
		{
			meta_title_push : 'Push',
			meta_title_pull : 'Pull',
			control_label_col_width  : 'Width',
			control_label_col_increase_width : 'Increase width',
			control_label_col_decrease_width : 'Decrease width'
		});
	});
});

})();
