/* Examples
js-visibility-depends-on:
<div class='col-xs-6' js-visibility-depends-on="[name='call_support'];isRadio==normal">
									<?php echo $form->drawTime($data->startTime, 's') ?>
								</div>


<a class='btn month ' href="#"     js-click="SiteCalls.openMonthView(this)">Month</a>
*/
$(document).on('click', '[js-click]', function (e) {
	if(e.target.type=='checkbox' || e.target.type=='radio'){
	    //we want checkbox clickable
	}else{
	    e.preventDefault(); 
	}
	
	var code = $(this).attr('js-click');
	code = code.nfcReplaceAll('(this)','($(this))',code);
	eval(code);

})

window.debug_loader = false;
function isCheckboxChecked(element) {
    return $(element).prop('checked');
}
function isRadio(element, value) {
    var passed = false;
    $(element).each(function () {
	
	if ($(this).is(':checked') && $(this).val() == value){
	  
	    passed = true;
	}
    });
    if(passed)
	return true;
    else
	return false;
}
//TODO chewck if used when parsing js-roles
function getStyleOnce(f) {
    var a = getStyleOnce;
    if (typeof a[f] == 'undefined') {
	$('head').append('<link rel="stylesheet" href="' + f + '" type="text/css" />');
	a[f] = true;
    }
}
//TODO use https://addyosmani.com/basket.js/ instead

function _loadScriptOnceNext(urls, callback) {
    //console.log('loadScriptONceNext:');
    urls.shift();
    if ($.isFunction(callback) == false) {
	var fixedCallback = window[callback];
    } else {
	var fixedCallback = callback;
    }

    if (typeof urls[0] === 'undefined') {
	//console.log('Bundle Inited, run callback:');
	fixedCallback();

	return function () {};
    } else {
	loadScriptOnce(urls[0], _loadScriptOnceNext(urls, callback));
    }
}

function loadBundleOnce(urls, callback) {

    loadScriptOnce(urls[0], _loadScriptOnceNext(urls, callback));
}

function loadScriptOnce(path, callback) {
 
  
    if ($.isFunction(callback) == false) {
	var fixedCallback = window[callback];
    } else {
	var fixedCallback = callback;
    }
    
    if (typeof fixedCallback === 'undefined') {
	return false;
    }

    if (typeof window['once_loaded'] === 'undefined') {
	window['once_loaded'] = {};
    }
    if (typeof window['once_inited'] === 'undefined') {
	window['once_inited'] = {};
    }

    if (typeof window['once_loaded'][path] === 'undefined') { //mark as script in loading queue
	window['once_loaded'][path] = 1;

    } else { //script already loaded
	if(typeof arguments!=='undefined' &&  typeof arguments[2]!=='undefined'){
	    fixedCallback(arguments[2]);
	}else{
	    fixedCallback();
	}

	return true;
    }
//	asyncResourceLoad(path,function (script, textStatus) {
//		
//		console.log('Loaded, Inited, run callback:');
//		window['once_inited'][path] = 1;
//		if($.isFunction(fixedCallback)) fixedCallback();
//	});
//	return true;

    $.cachedScript(path).done(function (script, textStatus) {
	if(window.debug_loader)
	    console.log('LOADED:'+path);
	window['once_inited'][path] = 1;

	if ($.isFunction(fixedCallback))
	    fixedCallback();
    });

    return true;

}

function parseContainersDependencies() {
    //TODO make sure it is called after values restored
    $('[js-visibility-depends-on]').each(function (index, element) {


	var data = $(this).attr('js-visibility-depends-on');
	var dataParsed = data.split(';');
	var sourceElement = dataParsed[0];
	var verificationFunction = dataParsed[1];
	var token = $(this).getPath();

	var $destination = $(this);
	//checking on load
	//console.log(verificationFunction);

	if ($.isFunction(window[verificationFunction]) === false && verificationFunction.indexOf('isRadio') == -1)
	    return false;
	if (verificationFunction.indexOf('isRadio') >= 0) {
	    temp = verificationFunction.split('==');
	    realVerificationFunction = temp[0];
	    value = temp[1];
	    if (window[realVerificationFunction](sourceElement, value)) {
		$(this).show();
	    } else {
		$(this).hide();
	    }
	} else if (verificationFunction == 'isCheckboxChecked') {
	    if (window[verificationFunction](sourceElement)) {
		$(this).show();
	    } else {
		$(this).hide();
	    }
	} else {
	    if (window[verificationFunction]()) {
		$(this).show();
	    } else {
		$(this).hide();
	    }
	}
	//checking on change;
	$(sourceElement).off('change.' + token).on('change.' + token, function () {
	   
	    if (verificationFunction.indexOf('isRadio') >= 0) {
		temp = verificationFunction.split('==');
		realVerificationFunction = temp[0];
		value = temp[1];
		
		if (window[realVerificationFunction](sourceElement, value)) {
		
		    $destination.show();
		} else {
		    $destination.hide();
		}
	    } else if (verificationFunction == 'isCheckboxChecked') {
		if (window[verificationFunction](sourceElement)) {
		    $destination.show();
		} else {
		    $destination.hide();
		}
	    } else {
		if (window[verificationFunction]()) {
		    $destination.show();
		} else {
		    $destination.hide();
		}
	    }

	});

    });
}
String.prototype.nfcReplaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

   

function asyncResourceLoad(u, c) {
    var d = document, t = 'script',
	    o = d.createElement(t),
	    s = d.getElementsByTagName(t)[0];
    o.src = '//' + u;
    if (c) {
	o.addEventListener('load', function (e) {
	    c(null, e);
	}, false);
    }
    s.parentNode.insertBefore(o, s);
}
jQuery.cachedScript = function (url, options) {

    // Allow user to set any option except for dataType, cache, and url
    options = $.extend(options || {}, {
	dataType: "script",
	cache: true,
	url: url
    });

    // Use $.ajax() since it is more flexible than $.getScript
    // Return the jqXHR object so we can chain callbacks
    return jQuery.ajax(options);
};



jQuery.fn.getPath = function () {
    if (this.length != 1)
	return 'na';
    var path, node = this;
    if (node[0].id)
	return "#" + node[0].id;
    while (node.length) {
	var realNode = node[0],
		name = realNode.localName;
	if (!name)
	    break;
	name = name.toLowerCase();
	var parent = node.parent();
	var siblings = parent.children(name);
	if (siblings.length > 1) {
	    name += ':eq(' + siblings.index(realNode) + ')';
	}
	path = name + (path ? '>' + path : '');
	node = parent;
    }
    return path;
};

