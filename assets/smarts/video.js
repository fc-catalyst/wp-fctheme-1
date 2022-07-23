/*
 * Add Video to a div by attributes
 */

function fcAddVideo(self) {

    if ( !self ) { return }

    const $ = jQuery,
          $self = $( self ),
          source = $self.attr( 'data-source' ),
          src = $self.attr( 'data-src' );
    let content = ``;
            
    if ( !source || !src ) { return }
    
    switch(source) {
        case 'direct' :
            content = `
                <video width="600" controls>
                    <source src="${ src }" type="${ $self.attr( 'data-type' ) }">
                    ${ $self.attr( 'data-error' ) }
                </video>
            `;
        break;
        case 'youtube' :
            content = `
                <iframe src="${ src }" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" width="600" height="312" class="youtube"></iframe>
            `;
        break;
    }
    
    $self.html( content );
}