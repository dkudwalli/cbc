jQuery(function ($) {
  "use strict";
	$('.eventer-ticket-details-wrap .eventer-btn').css('pointer-events','none');
    if (!$(".eventer-time-slot option:selected").length){
          $('.eventer-ticket-details-wrap .eventer-btn').css('pointer-events','all');
    }
});
