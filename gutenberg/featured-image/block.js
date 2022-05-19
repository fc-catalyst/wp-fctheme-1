( function( blocks, element, blockEditor ) {
	var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType( 'fct1-gutenberg/featured-image', {
		title: 'FCT1 Featured Image',
        icon: 'block-default',
		category: 'widgets',
		edit: function( props ) {
			return el(
				'p',
				{ className: props.className },
                'The Featured Image'
			);
		},
		save: function( props ) {
            return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );
