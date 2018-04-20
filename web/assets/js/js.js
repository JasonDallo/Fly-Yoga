 $(window).on("scroll", function () {
  "use strict"

  // menu scroll
  if ($(this).scrollTop() > 150) {
    $(".navigation-boxed").addClass("navigation-full").find(".navbar").removeClass("navbar-boxed container").addClass("container-fluid");
    $(".nav-top-bar").css("display", "none");
    }
  else {
    $(".navigation-boxed").removeClass("navigation-full").find(".navbar").addClass("navbar-boxed container").removeClass("container-fluid");
    $(".nav-top-bar").css("display", "block");
  };
});

$(document).ready(function() {
  "use strict"

  /*--------------------------------------------
    Navigation
  --------------------------------------------*/
  $("#menu-toggle").on("click", function(){
    $(this).find("i").toggleClass("ion-android-menu ion-android-close");
    $("#main-menu").toggleClass("open");
  });
   $("#search-toggle").on("click", function(){
    $("#search-bar").toggleClass("open");
    $(this).find("i").toggleClass("ion-ios-search-strong ion-android-close");
  });

  // Submenu 
  $(".menu-item").on("mouseenter", function(){
    $(this).find(".submenu").stop(true, true).delay(1).fadeIn(100);
  });
   $(".menu-item").on("mouseleave", function(){
    $(this).find(".submenu").stop(true, true).delay(120).fadeOut(5);
  });

  /*--------------------------------------------
    Range Slider
  --------------------------------------------*/

  if ($(".price-range-slider").length > 0) {
    var minPrice = $("#min-price");
    var maxPrice = $("#max-price");
    var priceRangeSlider = $("#range-slider");
    priceRangeSlider.slider({
      range: true,
      min: 0,
      max: 100,
      values: [20, 70],
      slide: function (event, ui){
        minPrice.val(ui.values[0]);
        maxPrice.val(ui.values[1]);
      }
    });
    minPrice.val(priceRangeSlider.slider("values", 0));
    maxPrice.val(priceRangeSlider.slider("values", 1));
  };

  /*--------------------------------------------
    Incrementing
  --------------------------------------------*/
  $(".qtybutton").on("click", function() {
    var $button = $(this);
    var oldValue = $button.parent().find("input").val();
    if ($button.html() === '<i class="ion-arrow-up-b"></i>') {
      var newVal = parseFloat(oldValue) + 1;
    } else {
       // Don't allow decrementing below zero
      if (oldValue > 0) {
      var newVal = parseFloat(oldValue) - 1;
      } else {
      newVal = 0;
      }
    }
    $button.parent().find("input").val(newVal);
  }); 


  /*--------------------------------------------
    Toggle Switch
  --------------------------------------------*/

  $(".toggle-switch .first-option").on("click", function(){
    var toggleHandler = $(this);
    if (!toggleHandler.hasClass("active") ) {
      toggleHandler.parent().next(".indicator").removeClass('active');
      toggleHandler.parent().prev(".indicator").addClass('active');
    }
  });
  $(".toggle-switch .second-option").on("click", function(){
    var toggleHandler = $(this);
    if (!toggleHandler.hasClass("active") ) {
      toggleHandler.parent().prev(".indicator").removeClass('active');
      toggleHandler.parent().next(".indicator").addClass('active');
    }
  });

  /*--------------------------------------------
    Twitters
  --------------------------------------------*/
  var helixTwitter = {
    "id": '722835766359957504', //Twiiter id
    "domId": 'helix-twitter',
    "dateOnly": true,
    "maxTweets": 2,
    "enableLinks": true,
    "showUser": false,
    "showTime": true,
    "showInteraction": false,
    "dateFunction": momentDateFormatter,
    "customCallback": customTemplate
  };
 
  function customTemplate(tweets){
    var x = tweets.length;
    // var tweetObject = ;
    var n = 0;
    var element = document.getElementById('helix-twitter');
    var html = '<ul>';
    while(n < x) {
      html += '<li class="clearfix">'+ '<i class="ion-social-twitter"></i>' + '<div>' + tweets[n] + '<div>' + '</li>';
      n++;
    }
    html += '</ul>';
    element.innerHTML = html;
  };

  twitterFetcher.fetch(helixTwitter);

  function momentDateFormatter(date, dateString) {
    return moment(dateString).fromNow();
  };

  /*--------------------------------------------
    NivoLight Box
  --------------------------------------------*/
  // var nivoActivator = $("#nivo-activator");
  if ($("#nivo-activator").css("display") == "block"){
    $('.nivo-trigger').nivoLightbox({
      theme: 'default'
    });
  };

  /*--------------------------------------------
    Instagram
  --------------------------------------------*/
  if ($("#helix-instafeed").length>0) {
    var feedFooter = new Instafeed({
      get: 'user',
      userId: '3013976721', // put your used id here
      accessToken: '3013976721.14e148f.c89002a243444fb1b7a1839aa0125047', //put your acces token here
      sortBy: 'most-liked',
      template: '<li><a href="{{link}}" target="_blank"><img class="img-responsive" src="{{image}}" /></a></li>',
      target: 'helix-instafeed',
      limit: 6,
      resolution: 'low_resolution',
    });
    feedFooter.run();
  }
  
  /*--------------------------------------------
    Slider Revolution
  --------------------------------------------*/
  if($("#helix-main-slider").length>0){
    jQuery("#helix-main-slider").revolution({
      sliderType:"standard",
      sliderLayout:"auto",
      delay:9000,
      gridwidth:1230,
      gridheight:800,
      navigation: {
        keyboard_direction: "horizontal",
        mouseScrollNavigation: "off",
        onHoverStop: "off",
        arrows: {
          style: "erinyen",
          enable: true,
          hide_under: 768,
          hide_onleave: false,
          tmp: '<div class="tp-title-wrap"><div class="tp-arr-imgholder"></div><div class="tp-arr-img-over"></div></div>',
          left: {
            h_align: "left",
            v_align: "center",
            h_offset: 55,
            v_offset: 2
          },
          right: {
            h_align: "right",
            v_align: "center",
            h_offset: 55,
            v_offset: 2
          }
        },
        bullets: {
          enable: true,
          hide_onmobile: false,
          style: "simplebullets",
          hide_onleave: false,
          direction: "horizontal",
          h_align: "center",
          v_align: "bottom",
          h_offset: 20,
          v_offset: 50,
          space: 10,
        }
      },
    });
  };
  /*--------------------------------------------
    Image Zoom
  --------------------------------------------*/
  if ($(".zoom-wrapper").length > 0) {
    $("#big-product-image").elevateZoom({
      gallery:'zoom-gallery', 
      galleryActiveClass: 'active', 
      imageCrossfade: true,  
      zoomType : "inner",
      cursor: "crosshair"
    });
  };

  /*--------------------------------------------
    Carousels
  --------------------------------------------*/
  
  /* variable "carousels" holds all carousels that have the same configuration like
   like "#helix-classes-carousel". Don't add other carousels that didn't share the configuration*/
  var carousels = $("#helix-classes-carousel, #helix-blog-carousel"); 

  // Defines Custom Navigation for OwlCarousel When it's need it
  $('.customNextBtn').on("click", function(){
    carousels.trigger('next.owl.carousel');
    return false;
  });
  $('.customPrevBtn').on("click", function(){
    carousels.trigger('prev.owl.carousel', [300]);
    return false;
  });

  // Owl Carousel Classes and Blog (Home Page)
  if(carousels.length>0){
    carousels.owlCarousel({
      responsive:{
        1200:{
          items: 4,
        },
        991:{
          items: 3,
        },
        768:{
          items:2,
        },
        650:{
          items:2,
        },
        100:{
          items:1,
        }
      },
      margin: 30,
      autoHeight: false,
      navigation : false,
      navigationText:['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
      autoPlay: true,
      autoplaySpeed: 500
    });
  };

  // Owl Carousel testimonials (Home Page)
  if($("#helix-testimonials-carousel").length>0){
    $("#helix-testimonials-carousel").owlCarousel({
      responsive:{
        1200:{
          items: 2,
          dots: true   
        },
        991:{
          items: 2,
          dots: true 
        },
        768:{
          items:2,
          dots: true
        },
        100:{
          items:1,
          dots: true
        }
      },
      responsiveRefreshRate:100,
      margin: 30,
      nav: false,
      dots: true
    });
  };

  // Oel Carousel article (Blog Page)
  if($("#helix-article-slider").length>0){
    $("#helix-article-slider").owlCarousel({
      items: 1,
      responsiveRefreshRate:100,
      nav: true,
      navText:['<i class="ion-ios-arrow-left"></i>','<i class="ion-ios-arrow-right"></i>'],
      dots: false
    });
  };

  /*--------------------------------------------
    FitVids
  --------------------------------------------*/
  if ($('.embed-video').length>0) {
    $('.embed-video').fitVids();
  }

  /*--------------------------------------------
    Google Maps
  --------------------------------------------*/
  if ($("#helix-map").length > 0) {
    $(function () {

    var position = {lat: -37.799185, lng: 144.987057};

    var contentString = '<div class="map-adress">' + '<p>168th Wellington St <br> Collingwood, Victoria 3066 <br> +064 342 28 38</p>' + '</div>';

    $('#helix-map')
      .gmap3({
        zoom: 15,
        center: position,
        mapTypeControl: false,
        streetViewControl: false,
        scrollwheel: false
      })
      .marker({
        position: position,
        icon: 'assets/img/map-marker.png'
      })
      .infowindow({
        content: contentString,
      }) 
      .then(function (infowindow) {
        var map = this.get(0);
        var marker = this.get(1);
          infowindow.open(map, marker);
      });
    });
  };


  /*--------------------------------------------
    Filterable Content
  --------------------------------------------*/
  if ($(".helix-filterable").length > 0) {
    var $container = $('.helix-filterable');
     
    $container.isotope({
      itemSelector: '.item',
      filter: '.item-shown'
    });
            
    var PageLoadFilter = '.item-shown';
    $container.isotope({ filter: PageLoadFilter});

    $("#helix-filter").on("click", "li", function(){
      var $this = $(this);
      $(".selected").removeClass("selected");
      $this.addClass("selected");
      var selector = $this.attr('data-filter');
      $container.isotope({ filter: selector });
      return false;
    });
  };

});