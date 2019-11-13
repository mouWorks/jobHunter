// LOCATION_SELECT
function location_select(){
  $(".search_location").click(function(){
      if( $(".search_location").hasClass("on") ){
         $(this).removeClass("on");
      }else{
        $(this).addClass("on");
      }
      $(".search_location_block").slideToggle();
  });

}


// NAV
function nav(){
    $("#menu").click(function(){
        if( $("#menu").hasClass("on") ){
            $(this).removeClass("on");
        }else{
             $(this).addClass("on");
        }
        $("#nav").slideToggle();
    });
}


// BTN_TOP
function btn_top(){
    $("#btn_top").click(function() {
        $("html,body").animate({scrollTop:0},1000);
    });
    $(window).scroll(function(){
        var SCROLL = $(window).scrollTop(); //抓目前網頁捲軸的座標
        if( SCROLL>300 ){
          $("#btn_top").stop(true,false).animate({ bottom : 80}, 500, "easeOutBack");
        }else{
          $("#btn_top").stop(true,false).animate({ bottom : -1000}, 500, "easeOutBack");
        }
    });
}



$(document).ready(function(){
  location_select();
  nav();
  btn_top();
});
