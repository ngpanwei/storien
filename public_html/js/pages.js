$(function() {
  $( "[data-role='navbar']" ).navbar();
  $( "[data-role='header'], [data-role='footer']" ).toolbar();
});
// Update the contents of the toolbars
$( document ).on( "pagecontainershow", function() {
  var current = $( ".ui-page-active" ).jqmData( "title" );
  $( "[data-role='header'] h1" ).text( current );
  // Remove active class from nav buttons
  // $( "[data-role='navbar'] a.ui-btn-active" ).removeClass( "ui-btn-active" );
  // Add active class to current nav button
  $( "[data-role='navbar'] a" ).each(function() {
    if ( $( this ).text() === current ) {
      $( this ).addClass( "ui-btn-active" );
    } else {
        $( this ).removeClass( "ui-btn-active" );
    }
  });
});