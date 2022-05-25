$(function() {

    $('#side-menu').metisMenu().removeClass("hide");

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 90;
        //topOffset = 0;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 134; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        //console.log(height);
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height-2) + "px");
            //esto pone el menu hasta abajo
            //$("div.sidebar").css("height", (height) + "px");
            /*esto pone el menu hasta abajo
            $("div.navbar-collapse").css("height", (height-4) + "px");*/
            //$("div.navbar-collapse").css("max-height", (height-4) + "px");
            /*esto pone el menu hasta abajo
            $("div.navbar-collapse").css("max-height", "none");
            $("#side-menu").css("height", (height-8) + "px");*/
            //$("div.navbar-collapse").css("background-color", "red");
        }
    })
})