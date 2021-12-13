Info = '';

(function () {

    var Info = {
	company: false,
	usersTotal: false,
	initedTab: false,
	getBetween: function (str, delimiter1, delimiter2) {

	    result = false;
	  
	    const regex = /<div class=["']?alert alert-success["']?>(.*)<\/div>/m;
	    test = regex.exec(str);
	
	    if (test !== null) {
		test.forEach((match, groupIndex) => {

		    result = match;
		});
	    }

	    if (result === false) {
		console.log('INCIDENT Message was not detected');

		console.log(str);
		return result;
	    }
	   
	    return result;
	},
	getNotyType: function (str) {
	    if (str != '') {
		if (str.indexOf('alert-success'))
		    return 'success';
		if (str.indexOf('alert-error'))
		    return 'error';
		if (str.indexOf('alert-warning'))
		    return 'warning';
	    }
	},
	notyMessage: function (message) {
	    console.log('Notymessage');
	    console.trace();
	    if (typeof message === 'undefined')
		return false;
	    if (message === false)
		return false;
	 
	    var notyType = i.getNotyType(message);
	    var n = noty({
		type: notyType,
		//layout: 'topCenter',
		layout: 'bottomLeft',
		text: message,
		theme: 'relax',
		timeout: 8000,
		closable: true,
		closeWith: ['click', 'button'],
		animation: {
		    open: {height: 'toggle'}, // jQuery animate function property object
		    close: {height: 'toggle'}, // jQuery animate function property object
		    easing: 'swing', // easing
		    speed: 500 // opening & closing animation speed
		}
	    });
	}

    }

    var i = Info;
    window.Info = Info;
})();
