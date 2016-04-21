var str = "";

function setCanvasHeight()
{
	// width = $( window ).width();
	//
	// if ( width > 767 )
	// {
	// 	//$('.right .img').detach().appendTo('.right');
	// }
	// else
	// {
	// 	$('.right .img').detach().prependTo('.right');
	// }
}


$(document).ready(function(){

	$.validate();

	$('a.more').removeAttr('title');

	$("#mobile-menu ul").first().prepend( '<li class="mmclosebt"><a href="#page">Close</a></li>' );

	$("#mobile-menu").mmenu({
        extensions 		: [ "pageshadow", "theme-dark" ],
		offCanvas: {
			position  : "right",
			zposition : "front"
		}
      }
      ,
      {
         // configuration
         offCanvas: {
            pageSelector: ".wrapper"
         }
      });

			setCanvasHeight();

			$(window).resize( function(){

				setCanvasHeight();

			});



	/* ----------------------------------------------------------------- */
	/* -- target="_blank" fix, geef externe links een rel="external"  -- */
	$("a").each( function() {

		if ( $(this).attr('rel') && $(this).attr('rel').indexOf("external") >= 0 )
		{
			$(this).attr('target', '_blank');
		}

	});
	/* ----------------------------------------------------------------- */


});
