( function() {

	var el = wp.element.createElement;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var TextControl = wp.components.TextControl;

	wp.blocks.registerBlockType( 'fct1-gutenberg/tile-numbered', {
		title: 'FCT1 Tile Numbered',
        icon: 'columns',
		category: 'widgets',

		attributes: {
			mediaID: {
				type: 'number'
			},
			mediaURL: {
				type: 'string'
			},
			excerpt: {
				type: 'string'
			},
			url: {
				type: 'string'
			}
		}, // ++add number color and text color, maybe background color and shadow color too?

		edit: function( props ) {

			var onSelectImage = function( media ) {
				return props.setAttributes( {
					mediaURL: media.sizes && media.sizes.thumbnail ? media.sizes.thumbnail.url : media.url,
					mediaID: media.id,
				} );
			};
            
			return el( 'div',
				{},
                el( MediaUpload, {
                    onSelect: onSelectImage,
                    allowedTypes: 'image',
                    value: props.attributes.mediaID,
                    render: function( obj ) {
                        return el( wp.components.Button,
                            {
                                onClick: obj.open,
                            },
                            ! props.attributes.mediaID
                                ? 'Upload Image'
                                : el( 'img', { src: props.attributes.mediaURL } )
                        );
                    }
                }),
                el( TextControl, {
                    placeholder: 'Excerpt',
                    value: props.attributes.excerpt ? props.attributes.excerpt : '',
                    onChange: function( value ) {
						props.setAttributes( { excerpt: value } );
					}
                }),
                el( TextControl, {
                    placeholder: 'Link URL',
                    value: props.attributes.url ? props.attributes.url : '',
                    onChange: function( value ) {
						props.setAttributes( { url: value } );
					}
                })
			);
		},
		save: function( props ) {
            return null;
		}
	} );
})();