var drawer = function() {
    var canvas = null;
    var context = null;
    var properties = null;
    var lastX;
    var lastY;
    var isMousePressed = false;

    this.init = function(strNameElement,intWidth,intHeight) {
        intWidth = !intWidth ? 300 : intWidth;
        intHeight = !intHeight ? 300 : intHeight;
        //window.onload = function() {
            // Get the canvas reference
            canvas = document.getElementById(strNameElement);
            // Get the 2D context reference from the canvas
            context = canvas.getContext("2d");
            // Set the default properties for the canvas
            properties = {
                fill: "#000000",
                stroke: "#000000",
                clear: "#FFFFFF",
                size: 5,
                cap: "round",
                join: "round",
                width: intWidth,
                height: intHeight
            };
            // Set the canvas size (important step or drawing will fail)
            canvas.width = properties.width;
            canvas.height = properties.height;
            canvas.style.width = properties.width + 'px';
            canvas.style.height = properties.height + 'px';
            // Set the web page's handler for the touch/move event
            document.ontouchmove = function(event) {
                //event.preventDefault();
            }
            // Set the canvas' mouse click behaviour
            canvas.onmousedown = function(event) {
                isMousePressed = true;
                handleMouse(event, function(lastX, lastY, curX, curY)
                {
                    point(curX, curY);
                });
            };
            // Set the canvas' mouse release click behaviour
            canvas.onmouseup = function(event) {
                isMousePressed = false;
            };
            // Set the canvas' mouse drag behaviour
            canvas.onmousemove = function(event) {
                if (!isMousePressed)
                    return;
                handleMouse(event, function(lastX, lastY, curX, curY)
                {
                    line(lastX, lastY, curX, curY);
                });
            };
            // Set the canvas' touch/start behaviour
            $("#"+strNameElement).on("touchstart",function(event){
                handleTouch(event.originalEvent, function(lastX, lastY, curX, curY)
                {
                    point(curX, curY);
                });
            });
            // Set the canvas' touch/move behaviour
            $("#"+strNameElement).on("touchmove",function(event){
                handleTouch(event.originalEvent, function(lastX, lastY, curX, curY)
                {
                    line(lastX, lastY, curX, curY);
                });
            });

            // Hide the Android browser's URL bar
            if (!window.pageYOffset) {
                hideAddressBar();
            }
            window.addEventListener("orientationchange", hideAddressBar);
            clearAll();
        //}
    };
    this.getElementCanvas = function(){
        return canvas;
    };
    this.getDataUrl = function(){
        var dataURL = canvas.toDataURL();
        return dataURL;
    }
    this.changeColorBlue = function() {
        properties.stroke = "#fdfa00";
        properties.fill = "#fdfa00";
    };
    this.changeColorYellow = function() {
        properties.stroke = "#fdfa00";
        properties.fill = "#fdfa00";
    };
    this.changeColorRed = function() {
        properties.stroke = "#fdfa00";
        properties.fill = "#fdfa00";
    };
    this.changeColorBlack = function() {
        properties.stroke = "#000000";
        properties.fill = "#000000";
    };
    this.clear = function(){
        clearAll();
    }
    
    function clearAll(){
        context.fillStyle = properties.clear;
        context.rect(0, 0, properties.width, properties.height);
        context.fill();
    };
    
    function doWithStyle(what){
        context.beginPath();
        context.strokeStyle = properties.stroke;
        context.fillStyle = properties.fill;
        context.lineCap = properties.cap;
        context.lineJoin = properties.join;
        context.lineWidth = properties.size;
        what();
        context.fill();
        context.stroke();
        context.closePath();
    }

    function point(x, y){
        doWithStyle(
            function(){
                context.arc(x, y, 1, 0, Math.PI * 2, true);
            }
        );
    }

    function line(x1, y1, x2, y2){
        doWithStyle(
            function(){
                context.moveTo(x1, y1);
                context.lineTo(x2, y2);
            }
        );
    }

    function handleMouse(event, action){
        event.preventDefault();
        
        var curX = event.layerX - canvas.offsetLeft;
        var curY = event.layerY - canvas.offsetTop;
        if( curX < 0 || curY < 0 ){
            var curX = event.layerX;
            var curY = event.layerY;
        }
        action(lastX, lastY, curX, curY);
        lastX = curX;
        lastY = curY;
    }
    
    function handleTouch(event, action){
        event.preventDefault();
        var curX = event.touches[0].pageX - canvas.offsetLeft;
        var curY = event.touches[0].pageY - (canvas.offsetTop);
        action(lastX, lastY, curX, curY);
        lastX = curX;
        lastY = curY;
    }

    function hideAddressBar(){
        if (!window.location.hash){
            if (document.height < window.outerHeight){
                document.body.style.height = (window.outerHeight + 50) + 'px';
            }
            setTimeout(function(){
                window.scrollTo(0, 1);
            }, 50);
        }
    }
}  