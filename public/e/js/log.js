//https://github.com/hgoebl/mobile-detect.js/
var md = new MobileDetect(window.navigator.userAgent);

var mobileDevice = false;
if(md.mobile()){
	mobileDevice = true;
}
var userAgent = ' os: ' + md.os() + '. Build: ' + md.versionStr('Build') + '. Webkit: ' + md.version('Webkit');
var browserName = getBrowserByDucktyping();
function getAndroidVersion(ua) {
    ua = (ua || navigator.userAgent).toLowerCase(); 
    var match = ua.match(/android\s([0-9\.]*)/);
    return match ? match[1] : false;
};

if (mobileDevice && md.os() == 'AndroidOS') {
	var androidVersion = getAndroidVersion();
	var num = androidVersion.split('.');
	androidVersion = num[0] + '.' + num[1];
	if(androidVersion < 4.4) {
		window.onerror = function(msg, file, lineNum) {
			$.ajax({
				type: 'POST',
				//url: '/scripts/js-error-logs-master/server/php-flatfile/logError.php',
				
				url: '/e/index.php?setJsError=true&url='+encodeURI(window.location.href),
				data: {
					setJsError: true,
					filename: file,
					line: lineNum,								
					error: msg,
					mobileDevice: mobileDevice,
					mobile: md.mobile(),
					userAgent: userAgent,
					browserName: browserName,
					stackTrace: 'Android version < 4.4. Cannot get stacktrace! ',
				},
				success: function(data) {
				},
				error: function(xhr, str) {

				}
			});
			return true;
		};
	} else {
		
		var logJsError = function(stackframes) {
			var stringifiedStack = stackframes.map(function(sf) {
				return sf.toString();
			}).join('\n');
			//console.log(stackframes);
			$.ajax({
				type: 'POST',
				//url: '/scripts/js-error-logs-master/server/php-flatfile/logError.php',
				url: '/e/index.php?setJsError=true&url='+encodeURI(window.location.href),
				data: {
					setJsError: true,
					filename: window.errorFilename,
					line: window.errorLine,
					error: window.errorMsg,
					stackTrace: stringifiedStack,
					mobileDevice: mobileDevice,
					mobile: md.mobile(),
					userAgent: userAgent,
					browserName: browserName,
				},
				success: function(data) {
				},
				error: function(xhr, str) {
				}
			});					
		};		
		
		window.onerror = function(msg, file, lineNum, col, error) {
			//StackTrace.fromError(error).then(callback).catch(errback);
			window.errorMsg = msg;
			window.errorLine = lineNum;
			window.errorFilename = file;
			StackTrace.fromError(error).then(logJsError);
			return true;
		};

		
	}

} else {
	
	Logerr.init({
	  remoteLogging: true,
	  detailedErrors: false,
	  remoteSettings: {
		//url: '/scripts/js-error-logs-master/server/php-flatfile/logError.php',
		url: '/e/index.php?setJsError=true&url='+encodeURI(window.location.href),
		additionalParams: {
			setJsError: true,
			mobileDevice: mobileDevice,
			mobile: md.mobile(),
			browserName: browserName,
//			userAgent: userAgent,  //default
		},
		successCallback: function () {
		},
		errorCallback: function () {
		}
	  }
	});
}

//Detecting browsers by ducktyping:
function getBrowserByDucktyping(){
	// Opera 8.0+
	var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
	if(isOpera) {
		return 'Opera';
	}
	// Firefox 1.0+
	var isFirefox = typeof InstallTrigger !== 'undefined';
	if(isFirefox) {
		return 'Firefox';
	}
	// Safari 3.0+ "[object HTMLElementConstructor]" 
	var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);
	if(isSafari) {
		return 'Safari';
	}
	// Internet Explorer 6-11
	var isIE = /*@cc_on!@*/false || !!document.documentMode;
	if(isIE) {
		return 'IE';
	}
	// Edge 20+
	var isEdge = !isIE && !!window.StyleMedia;
	if(isEdge) {
		return 'Edge';
	}
	// Chrome 1+
	var isChrome = !!window.chrome && !!window.chrome.webstore;
	if(isChrome) {
		return 'Chrome';
	}
	// Blink engine detection
	var isBlink = (isChrome || isOpera) && !!window.CSS;
	if(isBlink) {
		return 'Blink';
	}
	return 'cannot define browser by ducktyping';
}

