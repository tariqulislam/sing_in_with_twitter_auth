/*
 * jQuery cutoms Effect 
 	Tanim Ahmed 6/3/2013
*/

 $(document).ready(function(){
	
	// start footer animation footermenutop
	
	$('.show_hide').click(function(){
    $(".footerContainer").slideToggle(function(){
		
 if($(this).css('display')=='none'){
      $(".show_hide").removeClass("icon-minus-sign").addClass("icon-plus-sign");
	  
	  $(".footerBar").removeClass("bgcolor12").addClass("bgcolor11");
	  $(".footermenutop").removeClass("footermenutopB").addClass("footermenutopA");
 
 }
 else{
	  $(".show_hide").removeClass("icon-plus-sign").addClass("icon-minus-sign");
	  $(".footerBar").removeClass("bgcolor11").addClass("bgcolor12");
	  $(".footermenutop").removeClass("footermenutopA").addClass("footermenutopB");
 }
});
    });
	
	// cart  animation start here
	
$(".cart").click(function () {
		$(".cartDropDown").slideToggle(function(){
			
			 if($(this).css('display')=='none'){
		  $(".cartContentInner").removeClass("cartbgreds inHide").addClass("cartbg ");
		  
		  $(".cart").removeClass("cartnoanime").addClass("cartanime ");
		  
		  $(".cartContentMouseover").removeClass("cartContentMouseoverHeight").addClass("cartContentMouseovernormal ");

	 }
 
	 else{
		  $(".cartContentInner").removeClass("cartbg").addClass("cartbgred inHide");
		  $(".cartContentMouseover").removeClass(" cartContentMouseovernormal").addClass("cartContentMouseoverHeight ");
		  $(".cart").removeClass("cartanime").addClass("cartnoanime");

	 }
	});
	
		
 $(".cartContentMouseoverHeightx").mouseout(function(){
   $(".cartDropDown").slideUp("slow");
  });
  
 $(".cartContentMouseoverx ").mouseout(function(){
   $(".cartDropDown").slideUp("slow"); 
  });

				 });

});

// windows scroll function

(function($){
			$(window).load(function(){
				$(".innerScroll").mCustomScrollbar(
				{
				//theme:"dark".
				theme:"dark"	,
				mouseWheelPixels: "400"
				}
				);
				$(".innerScrollfaq").mCustomScrollbar(
				{
				//theme:"dark".
				theme:"dark"	,
				mouseWheelPixels: "382"
				}
				);
				
			});
		})(jQuery);