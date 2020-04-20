
//lähteet: https://jqueryui.com/datepicker/#date-range
$.datepicker.setDefaults({
  firstDay: 1,
  autoSize: true,
  dayNamesMin: [ "Su", "Ma", "Ti", "Ke", "To", "Pe", "La" ],
  monthNames: [ "Tammi", "Helmi", "Maalis", "Huhti", "Touko", "Kesä", "Heinä", "Elo", "Syys", "Loka", "Marras", "Joulu" ]
});
$( function() {
  $('#from').datepicker({
    dateFormat: "yy-mm-dd",
    });
  $('#to').datepicker({
    dateFormat: "yy-mm-dd"});
  
  var dateFormat = "yy-mm-dd",
    from = $( "#from" )
      .datepicker({
        changeMonth: true,
        numberOfMonths: 1
      })
      .on( "change", function() {
        to.datepicker( "option", "minDate", getDate( this ) );
      }),
    to = $( "#to" ).datepicker({
      changeMonth: true,
      defaultDate: "+1d",
      numberOfMonths: 1
    })
    .on( "change", function() {
      from.datepicker( "option", "maxDate", getDate( this ));
    });

  function getDate( element ) {
    var date;
    try {
      date = $.datepicker.parseDate( dateFormat, element.value );
    } catch( error ) {
      date = null;
    }

    return date;
  }
} );