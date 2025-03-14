$(function () {
  $("body").append('<img id="goTopButton" style="display:none; z-index:5; cursor:pointer;" title="回到頂端"/>');
  var img = "./images/bntop01.png",  //將圖片以變數引用      
    location = 0.9,         //位置
    right = 50,             //與右邊界距離
    opacity = 0.6,          //透明度
    speed = 800,            //捲動速度
    $button = $("#goTopButton"),  //宣告按鈕
    $body = $(document),          //宣告body
    $win = $(window);             //瀏覽器
  $button.attr("src", img);
  

  //建立當網站捲動時呼叫自訂函數
  window.goTopMove = function () {
    var scrollH = $body.scrollTop(),
      winH = $win.height(),
      css = { "top": winH * location + "px", "position": "fixed", "right": right, "opacity": opacity };
    
    if (scrollH > 20) {
      $button.css(css);
      $button.fadeIn("slow");
    } else {
      $button.fadeOut("slow");
    }
  };
  //設定瀏覽器監聽兩個段作。分別為scroll與resize
  $win.on({
    scroll: function () { goTopMove(); },
    resize: function () { goTopMove(); }
  });
  //設定瀏覽器監聽圖片的三個動作，分別為滑鼠<1. 滑入>,<2. 滑出>,<3. 點擊>
  $button.on({
    mouseover: function () { $button.css("opacity", 1); },
    mouseout: function () { $button.css("opacity", opacity); },
    click: function () {
      $("html,body").animate({ scrollTop: 0 }, speed);
    }
  });
});