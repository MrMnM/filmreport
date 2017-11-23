export function activateSideMenu(){
     $('#side-menu').metisMenu();
     $(window).bind("load resize", function() {
         var topOffset = 50;
         var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
         if (width < 768) {
             $('div.navbar-collapse').addClass('collapse');
             topOffset = 100; // 2-row-menu
         } else {
             $('div.navbar-collapse').removeClass('collapse');
         }
         var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
         height = height - topOffset;
         if (height < 1) height = 1;
         if (height > topOffset) {
             $("#page-wrapper").css("min-height", (height) + "px");
         }
     });
     var url = window.location;
      var element = $('ul.nav a').filter(function() {
          return this.href == url;
      }).addClass('active').parent().parent().addClass('in').parent();
     var element = $('ul.nav a').filter(function() {
         return this.href == url;
     }).addClass('active').parent();

     while (true) {
         if (element.is('li')) {
             element = element.parent().addClass('in').parent();
         } else {
             break;
         }
     }

     let freelancer = true
     if (sessionStorage.getItem('freelancer') === null) {
    let freelancer = sessionStorage.setItem('freelancer', true);
    }
    switchTypes(freelancer)
}

export function switchTypes(freelancer){
    if (freelancer) {
        sessionStorage.setItem('freelancer', true);
        $(".freelance").show()
        $(".producer").hide()
    } else {
        sessionStorage.setItem('freelancer', false);
        $(".freelance").hide()
        $(".producer").show()
    }
}
