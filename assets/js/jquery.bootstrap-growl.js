(function() {
    var $;
  
    $ = jQuery;
  
    $.bootstrapGrowl = function(message, options) {
      var $alert, css, aw_css,  offsetAmount;
      options = $.extend({}, $.bootstrapGrowl.default_options, options);
      $alert = $("<div>");
      $alert.attr("class", "bootstrap-growl alert");
      if (options.type) {
        $alert.addClass("alert-" + options.type);
        switch(options.type){
          case "primary": $alert.append(" <svg class=\"bi flex-shrink-0 me-2\" width=\"24\" height=\"24\" role=\"img\" aria-label=\"Info:\"><use xlink:href=\"#info-fill\"/></svg>"); break;
          case "danger": $alert.append(" <svg class=\"bi flex-shrink-0 me-2\" width=\"24\" height=\"24\" role=\"img\" aria-label=\"Info:\"><use xlink:href=\"#exclamation-triangle-fill\"/></svg>"); break;
        }
        
      }
      if (options.allow_dismiss) {
        $alert.addClass("alert-dismissible");
        $alert.append("<button  class=\"btn-close\" data-dismiss=\"alert\" type=\"button\"><span aria-hidden=\"true\"></span><span class=\"sr-only\">Close</span></button>");
      }
      $alert.append(message);
      if (options.top_offset) {
        options.offset = {
          from: "top",
          amount: options.top_offset
        };
      }
      offsetAmount = options.offset.amount;
      $(".bootstrap-growl").each(function() {
        //return offsetAmount = Math.max(offsetAmount, parseInt($(this).css(options.offset.from)) + $(this).outerHeight() + options.stackup_spacing);
      });
      css = {
        //"position": (options.ele === "body" ? "fixed" : "absolute"),
        "position": "",
        "margin": 0,
        "z-index": "9999",
        "margin-top": "10px",
        "display": "none"
      };
      css[options.offset.from] = offsetAmount + "px";
      $alert.css(css);
      if (options.width !== "auto") {
        $alert.css("width", options.width + "px");
      }

      aw_css = {
        "position": "fixed",
        "top": 0,
        "margin": 0,
        "z-index": "9999"
      };
      
      if ( $("#alert-wapper").length === 0 ){
            $awrapper = $("<div>");
            $awrapper.attr('id','alert-wapper');
      } else $awrapper = $("#alert-wapper");

      $awrapper.css(aw_css);
      
      switch (options.align) {
        case "center":
            $awrapper.css({
            "left": "50%",
            "margin-left": "-" + ($alert.outerWidth() / 2) + "px"
          });
          break;
        case "left":
            $awrapper.css("left", "20px");
          break;
        default:
            $awrapper.css("right", "20px");
      }

      $awrapper.append($alert);
      $(options.ele).append($awrapper);
      $alert.fadeIn();
      if (options.delay > 0) {
        $alert.delay(options.delay).fadeOut(function() {
          return $(this).alert("close");
        });
      }
      return $alert;
    };
  
    $.bootstrapGrowl.default_options = {
      ele: "body",
      type: "info",
      offset: {
        from: "top",
        amount: 20
      },
      align: "right",
      width: 250,
      delay: 4000,
      allow_dismiss: true,
      stackup_spacing: 10
    };
  
  }).call(this);