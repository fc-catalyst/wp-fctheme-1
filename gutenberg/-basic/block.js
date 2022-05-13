( function( blocks, element, blockEditor ) {
	var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType( 'fct1-gutenberg/basic', {
		title: 'FCT1 Basic',
        icon: 'block-default',
		category: 'widgets',
		edit: function( props ) {
			return el(
				'p',
				{ className: props.className },
                'Test basic block works'
			);
		},
		save: function( props ) {
			return el(
				'p',
				{ className: props.className },
				'Test basic block can be printed'
			);
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );
