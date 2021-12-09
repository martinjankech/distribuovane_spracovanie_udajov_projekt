// Ajax -> čo znamená, že dokáže komunikovať so serverom, vymieňať si údaje a aktualizovať stránku bez toho, aby ste ju museli obnovovať.
function getMovies() { 
  $.ajax({
      type: 'POST',
      url: 'movieAction.php',
      data: 'action_type=view',
      success: function(html) {
          $('#movieData').html(html);
      }
  });
}

/* Posle data na stranu servera bez toho aby sme museli refreshovat stranku */
function movieAction( type, id ) {
  id = (typeof id == "undefined") ? '' : id;
  var movieData = '',
      frmElement = '';
  if (type == 'add') {
      frmElement = $( "#modalUserAddEdit" );
      movieData = frmElement.find( 'form' ).serialize() + '&action_type=' + type + '&Id=' + id; // serialize()-> zakoduje mnozinu prvkov formulara ako retazec na odoslanie 
      console.log(movieData)
  } else if (type == 'edit') {
      frmElement = $( "#modalUserAddEdit" );
      movieData = frmElement.find( 'form' ).serialize() + '&action_type=' + type;
      console.log( movieData )
  } else {
      frmElement = $(".row");
      movieData = 'action_type=' + type + '&Id=' + id;
      console.log(movieData)
  }
  frmElement.find('.statusMsg').html('');

  $.ajax({
      type: 'POST',
      url: 'movieAction.php',
      dataType: 'JSON',
      data: movieData,
      beforeSend: function() {
          frmElement.find( 'form' ).css( "opacity", "0.5" );
      },
      success: function( resp ) {
          frmElement.find( '.statusMsg' ).html( resp.msg );
        
          console.log( resp.msg )
          if ( resp.status == 1 ) {
              console.log( resp.status )
              if (type == 'add') {
                  frmElement.find( 'form' )[0].reset();
              }
              getMovies();
          }
          frmElement.find( 'form' ).css( "opacity", "" );
      }
  });
}

// Vyplnenie udajov pouzovatela do editacneho formulara 
function editMovie( id )  {
  $.ajax({
      type: 'POST',
      url: 'movieAction.php',
      dataType: 'JSON',
      data: 'action_type=data&Id=' + id,
      success: function(data) {
          $( '#Id' ).val( data.Id );
          $( '#Name_Movie' ).val( data.Name_Movie );
          $( '#Name_Director' ).val( data.Name_Director );
          $( '#Main_Actor' ).val( data.Main_Actor );
          $( '#Rating_Imdb' ).val( data.Rating_Imdb );
          $( '#Added_By_User' ).val( data.Added_By_User );
      }
  });
}

// Akcie na modálnej show a skrytých udalostiach - zisti aku akciu vykonavame v modalnom okne a zavola funckiu movie action podla toho 
$(function() {
  $( '#modalUserAddEdit' ).on( 'show.bs.modal', function(e) {
      var type = $(e.relatedTarget).attr('data-type');
      console.log( type )
      var userFunc = "movieAction( 'add' );";
      if ( type == 'edit' ) {
          userFunc = "movieAction( 'edit' );";
          var rowId = $( e.relatedTarget ).attr( 'rowID' );
          console.log( rowId )
          editMovie( rowId );
      }
      $( '#movieSubmit' ).attr( "onclick", userFunc );
  });

  $( '#modalUserAddEdit' ).on( 'hidden.bs.modal', function() {
      $( '#movieSubmit' ).attr( "onclick", "" );
      $( this ).find( 'form' )[0].reset();
      $( this ).find( '.statusMsg' ).html( '' );
  });
});

$("#register").submit(function(event){    
     event.preventDefault(); //prevent default action 
    
})