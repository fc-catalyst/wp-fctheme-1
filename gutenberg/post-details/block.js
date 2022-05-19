( function( blocks, element, blockEditor ) {
	var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType( 'fct1-gutenberg/post-details', {
		title: 'FCT1 Post Details',
        icon: 'block-default',
		category: 'widgets',
		edit: function( props ) {
			return el(
				'p',
				{ className: props.className },
                'The Post Details'
			);
		},
		save: function( props ) {
            return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );
