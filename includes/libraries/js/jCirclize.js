(function($)
{
	$.fn.circlize = function(options)
  {
		var defaults =
    {
			radius: 100, // The radius of the circle
			percentage: 50, // The percentage covered by the foreground
      text: "",
      typeUse: "usePercentage",
			useAnimations: true, // If you turn it to false it will not use the animations
			useGradient: true, // If you turn it to false it will use the solid color of the foreground
			background: "rgba(20,20,20,0.5)",
			foreground: "#1a1a1a",
			stroke: 20, // The width of the stroke
			duration: 1000, // The duration of the animation in milliseconds
			min: 50, // The minimum value
			max: 100, // The maximum value
			gradientColors: ["#f0f0f0", "red", "#f0f0f0"] // [Max 3 colors] Here you can set the colors of the gradients
		};

		var opts = $.extend(true, {}, defaults, options);

		return this.each(function()
    {
			var perc, box, x, y, html, context, cnv, ctn, fore, gradient;

			box = (Math.PI*(opts.radius));
			x   = box / 2;
			y   = box / 2;

      switch (opts.typeUse)
      {
        case "useHundred":
          perc = opts.min;
          break;

        case "useText":
        case "usePercentage":
        default:
          perc = opts.percentage;
          break;
      }

      if (opts.typeUse == "useText" && opts.text != "")
      {
        html = "<canvas class=\"circle\" width=" + box + " height=" + box + "></canvas>" +
               "<canvas class=\"circle\" width=" + box + " height=" + box + "></canvas>" +
               "<div class=\"percentage\" id=\"percentage_text\">" + perc + "</div>";
      }
      else
      {
        html = "<canvas class=\"circle\" width=" + box + " height=" + box + "></canvas>" +
               "<canvas class=\"circle\" width=" + box + " height=" + box + "></canvas>" +
               "<div class=\"percentage\">" + perc + "</div>";
      }


			$(this).append(html);
			$(this).addClass("canvasized");

			cnv = $(this).children(".circle");

			context = $(cnv)[0].getContext("2d");
			context.translate(0, box);
      context.rotate(-Math.PI / 2);

			gradient = context.createLinearGradient(0,0,opts.radius*Math.PI,0);
			gradient.addColorStop(0, opts.gradientColors[0]);
			gradient.addColorStop(0.5, opts.gradientColors[1]);
			gradient.addColorStop(1, opts.gradientColors[2]);

			fore = opts.useGradient ? gradient : opts.foreground;

			if (opts.useAnimations)
      {
				jQuery({counter: 0}).animate({counter: $(".percentage").text()},
        {
					duration: opts.duration,
					easing: "swing",
					step: function()
          {
            /*console.log ('counter : ' + this.counter);
            console.log ('x : ' + x);
            console.log ('y : ' + y);
            console.log ('opts.radius : ' + opts.radius);
            console.log ('start angle : ' + (2-(Math.ceil(this.counter)/opts.max)*2)*Math.PI);
            console.log ('end angle : ' + 2*Math.PI);
            console.log ('------------');*/

            switch (opts.typeUse)
            {
              case "useHundred":
                $(".percentage").text(Math.ceil(this.counter * 10)/10 + "/" + Math.ceil(opts.max * 10)/10);
  							context.beginPath();
  							context.arc(x, y, opts.radius, (1-(Math.ceil(this.counter)/opts.max)*2)*Math.PI, Math.PI);
  							context.fillStyle = "transparent";
  							context.fill();
  							context.strokeStyle = fore;
  							context.lineWidth   = opts.stroke;
  							context.stroke();
                break;

              case "useText":
                $("#percentage_text").text(opts.text);
                context.beginPath();
                context.arc(x, y, opts.radius, (1-(Math.ceil(this.counter)/100)*2)*Math.PI, Math.PI);
  							context.fillStyle = "transparent";
  							context.fill();
  							context.strokeStyle = fore;
  							context.lineWidth   = opts.stroke;
  							context.stroke();
                break;

              case "usePercentage":
              default:
                $(".percentage").text(Math.ceil(this.counter) + "%");
  							context.beginPath();
  							context.arc(x, y, opts.radius, (1-(Math.ceil(this.counter)/100)*2)*Math.PI, Math.PI);
  							context.fillStyle = "transparent";
  							context.fill();
  							context.strokeStyle = fore;
  							context.lineWidth   = opts.stroke;
  							context.stroke();
                break;
            }
					}
				});
			}
      else
      {
        switch (opts.typeUse)
        {
          case "useHundred":
            $(".percentage").text(Math.ceil(opts.min * 10)/10 + "/" + Math.ceil(opts.max * 10)/10);
  					context.beginPath();
  					context.arc(x, y, opts.radius, (1-(Math.ceil(opts.min)/opts.max)*2)*Math.PI, Math.PI);
  					context.fillStyle = "transparent";
  					context.fill();
  					context.strokeStyle = fore;
  					context.lineWidth   = opts.stroke;
  					context.stroke();
            break;

          case "useText":
            $(".percentage").text(opts.text);
            context.beginPath();
            context.arc(x, y, opts.radius, (1-(Math.ceil(opts.percentage)/100)*2)*Math.PI, Math.PI);
            context.fillStyle = "transparent";
            context.fill();
            context.strokeStyle = fore;
            context.lineWidth   = opts.stroke;
            context.stroke();
            break;

          case "usePercentage":
          default:
            $(".percentage").text(opts.percentage + "%");
  					context.beginPath();
  					context.arc(x, y, opts.radius, (1-(Math.ceil(opts.percentage)/100)*2)*Math.PI, Math.PI);
  					context.fillStyle = "transparent";
  					context.fill();
  					context.strokeStyle = fore;
  					context.lineWidth   = opts.stroke;
  					context.stroke();
            break;
        }
			}

			ctn = $(cnv)[1].getContext("2d");
			ctn.beginPath();
			ctn.arc(x, y, opts.radius, 0*Math.PI, 2*Math.PI);
			ctn.fillStyle = "transparent";
			ctn.fill();
			ctn.strokeStyle = opts.background;
			ctn.lineWidth   = opts.stroke;
			ctn.stroke();
		});
	};
})(jQuery);
