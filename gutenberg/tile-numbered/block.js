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
			},
            color1: {
                type: 'string'
            },
		},

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
                el( // sidebar
                    wp.element.Fragment,
                    {},
                    el( wp.blockEditor.InspectorControls, {},
                        el( wp.components.PanelBody, {},
                            el( TextControl, {
                                label: 'Link URL',
                                value: props.attributes.url ? props.attributes.url : '',
                                onChange: function( value ) {
                                    props.setAttributes( { url: value } );
                                }
                            }),
                            el( wp.components.ColorPalette, {
                                label: 'Color for the Number',
                                colors: [ // ++find a way to use the default pallet ++use classes from slug!! ++toggle
                                    {
                                        'name'  : 'Dark 1',
                                        'slug'  : 'fct1-dark-1',
                                        'color' : '#23667b'
                                    },
                                    {
                                        'name'  : 'Dark 2',
                                        'slug'  : 'fct1-dark-2',
                                        'color' : '#277888'
                                    },
                                    {
                                        'name'  : 'Dark 3',
                                        'slug'  : 'fct1-dark-3',
                                        'color' : '#58acbc'
                                    },
                                    {
                                        'name'  : 'Dark 4',
                                        'slug'  : 'fct1-dark-4',
                                        'color' : '#0087a0'
                                    },
                                    {
                                        'name'  : 'Dark 5',
                                        'slug'  : 'fct1-dark-5',
                                        'color' : '#dfc082'
                                    },
                                    {
                                        'name'  : 'Light 1',
                                        'slug'  : 'fct1-light-1',
                                        'color' : '#87c8d3'
                                    },
                                    {
                                        'name'  : 'Warning 1',
                                        'slug'  : 'fct1-warning-1',
                                        'color' : '#fda7a7'
                                    },
                                    {
                                        'name'  : 'White',
                                        'slug'  : 'white',
                                        'color' : '#fff'
                                    },
                                    {
                                        'name'  : 'Black',
                                        'slug'  : 'black',
                                        'color' : '#000'
                                    },
                                    {
                                        'name'  : 'Grey 1',
                                        'slug'  : 'fct1-grey-1',
                                        'color' : '#22262c'
                                    },
                                    {
                                        'name'  : 'Grey 2',
                                        'slug'  : 'fct1-grey-2',
                                        'color' : '#2f3339'
                                    },
                                    {
                                        'name'  : 'Grey 3',
                                        'slug'  : 'fct1-grey-3',
                                        'color' : '#2c3538'
                                    },
                                    {
                                        'name'  : 'Grey 4',
                                        'slug'  : 'fct1-grey-4',
                                        'color' : '#d3d7da'
                                    }
                                ],
                                value: props.attributes.color1,
                                onChange: function( value ) {
                                    props.setAttributes( { color1: value } );
                                }
                            })

/*                            el( wp.components.ColorPicker, {
                                label: 'Color for the Number',
                                color: props.attributes.color1,
                                defaultValue: '#333',
                                onChange: function( value ) {
                                    props.setAttributes( { color1: value } );
                                },
                                copyFormat: 'hex'
                            }),
//*/
                        )
                    )
                )
			);
		},
		save: function( props ) {
            return null;
		}
	} );
})();