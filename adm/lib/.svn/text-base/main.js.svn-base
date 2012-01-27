$(document).ready(function(){
    $("#meny li").hover(
        function(){ $("ul", this).fadeIn("slow"); }, 
        function() { } 
    );
    if (document.all) {
        $("#meny li").hoverClass("hover");
    }

	$("p.error").click(function () {
		$(this).hide("blind", { direction: "vertical" }, 1000);
	});

	$("p.info").click(function () {
		$(this).hide("blind", { direction: "vertical" }, 1000);
	});
	
	$("p.warning").click(function () {
		$(this).hide("blind", { direction: "vertical" }, 1000);
	});
	
	$("p.success").click(function () {
		$(this).hide("blind", { direction: "vertical" }, 1000);
	});
	
	$('#date').datepicker();

	$("#tid").clockpick({
		starthour : 0,
		endhour : 23,
		military : true
	});
});

$.fn.hoverClass = function(c) {
    return this.each(function(){
        $(this).hover( 
            function() {
				$(this).addClass(c);
			},
            function() {
				$(this).removeClass(c);
			}
        );
    });
};


