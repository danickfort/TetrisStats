function KeyboardController(keys, repeat, timeoutDelay) {

    var timers= {};
    var timeouts= {};
    
    // When key is pressed and we don't already think it's pressed, call the
    // key action callback and set a timer to generate another one after a delay
    //
    document.onkeydown= function(event) {
        var key= (event || window.event).keyCode;
        if (!(key in keys))
            return true;
        if (!(key in timers)) {
            timers[key]= null;
            keys[key]();
            if (repeat[key]!==0) {
               if (!timeouts[key]) {
					timeouts[key] = setTimeout(function() {
					timers[key] = setInterval(keys[key], repeat[key])
					},timeoutDelay[key]);	
				}
			}
        }
        return false;
    };
    
    // Cancel timeout and mark key as released on keyup
    //
    document.onkeyup= function(event) {
        var key= (event || window.event).keyCode;
		clearTimeout(timeouts[key]);
		delete timeouts[key];
        if (key in timers) {
            if (timers[key]!==null)
                clearInterval(timers[key]);
            delete timers[key];
        }
    };
    
    // When window is unfocused we may not get key events. To prevent this
    // causing a key to 'get stuck down', cancel all held keys
    //
    window.onblur= function() {
        for (key in timers)
		{
		clearTimeout(timeouts[key]);
            if (timers[key]!==null)
                clearInterval(timers[key]);
        }
		timers= {};
		timeouts= {};
    };
};

				   
KeyboardController({
		    37: function() { keyPressed('left'); },
		    38: function() { keyPressed('rotate'); },
		    39: function() { keyPressed('right'); },
		    40: function() { keyPressed('down'); },
		    32: function() { keyPressed('drop'); },
		    67: function() { keyPressed('hold')},
		    13: function() { keyPressed('reset')}
		    },
		    {
		    37: 30,
		    38: 5000,
		    39: 30,
		    40: 90,
		    32: 5000,
		    67: 5000,
		    13: 10000
		    },
		    {
		    37: 150,
		    38: 150,
		    39: 150,
		    40: 10,
		    32: 5000,
		    67: 5000,
		    13: 10000
		    });
				   