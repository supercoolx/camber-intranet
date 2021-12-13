//TODO why global functions? use Class instead, important to find all inits in code!!!
//TODO prefetch ckeditor on button mouseover

window.initedRichTextEditors = [];
window.openedJstreeNode = false;
function initPhone() {

    var telInput = $("input[js-role='phone']").intlTelInput({
	initialCountry: 'us'
		//utilsScript: "/scripts/intl-tel-input/js/utils.js"
    });
    $("input[js-role='phone']").each(function () {
	if ($(this).val() === '+') {
	    $(this).val('+1'); 
	}
    }
    )


    $('form').submit(function (e) {
	var notValid = false;
	$("input[js-role='phone']").each(function () {
	    var prevBlock = $(this).prev();
	    var countryCode = $('ul li.active', prevBlock).attr('data-country-code').toUpperCase();
	    var dataCountry = $(this).attr('data-country');

	    var target = $('#' + dataCountry);

	    //Validation
	    var errorMessagePositionSelector = 'div.intl-tel-input';
	    if ($('input[name="section"]').val() === 'EmergencyContact') {
		errorMessagePositionSelector = 'div.input-group';
	    }
	    $(this).closest(errorMessagePositionSelector).after('<div class="error-msg_' + dataCountry + ' hide">Invalid number</div>');
	    var errorMsg = $('.error-msg_' + dataCountry);
	    var reset = function () {
		errorMsg.removeClass("alert alert-error");
		errorMsg.addClass("hide");
		if (errorMsg.length > 1)
		    errorMsg.first().remove();
	    };

	    reset();

	    if ($.trim($(this).val()) && $(this).val() != '+1') {
		if (!$(this).intlTelInput("isValidNumber")) {
		    errorMsg.addClass("alert alert-error");
		    errorMsg.removeClass("hide");
		    notValid = true;
		}
	    }
	    target.val(countryCode);
	});
	if (notValid) {
	    e.preventDefault();
	    return false;
	}

    });
}



function initWysiwyg(res) {


    var containers = document.querySelectorAll('[js-role="ckeditor"]');
    var options;
    var defaultOptions = getCkeditorDefaultOptions();

    CKEDITOR.plugins.addExternal('videodetector', '/scripts/vendor/aoturoa/videodetector/');


    for (i = 0; i < containers.length; i++) {

	var editor = CKEDITOR.instances[containers[i].id];
	if (editor) {
	    editor.destroy(true);
	}

	var customOptions = $(containers[i]).attr("js-options");
	if (typeof customOptions != 'undefined') {
	    customOptions = customOptions.replace(/'/g, '"');
	    options = $.extend({}, defaultOptions, jQuery.parseJSON(customOptions));
	} else {
	    options = defaultOptions;
	}

	CKEDITOR.replace(containers[i], options);
    }
}

function initCkeditorMulti() {

    console.log('init ckeditor-multi');
    var containers = document.querySelectorAll('[js-role="ckeditor-multi"]');
    var options;
    var defaultOptions = getCkeditorDefaultOptions();

    CKEDITOR.plugins.addExternal('videodetector', '/scripts/vendor/aoturoa/videodetector/');
    $(document).on('focus', '.hidden-but-focus-allowed', function () {
	var varname = $(this).attr("name");
	var editorContainer = document.getElementById(varname);
	var placeHolder = document.getElementById(varname + 'fake');
	var customOptions = $(placeHolder).attr("js-options");
	if (typeof customOptions != 'undefined') {
	    customOptions = customOptions.replace(/'/g, '"');
	    options = $.extend({}, defaultOptions, jQuery.parseJSON(customOptions));
	} else {
	    options = defaultOptions;
	}
	console.log('tab checking:' + varname);
	if (typeof window.initedRichTextEditors[varname] == 'undefined')
	    window.initedRichTextEditors[varname] = 1;
	else {
	    console.log('setting focus for existing:' + varname);
	    window.initedRichTextEditors[varname].focus();
	    return false;
	}
	console.log('tab initing:' + varname);
	console.log('setting focus:' + varname);
	editor = CKEDITOR.replace(editorContainer, options);
	window.initedRichTextEditors[varname] = editor;
	editor.on('instanceReady', function (e) {
	    $(placeHolder).remove();
	    $("#preload-editor-css").attr("disabled", "disabled");
	    $(this).focus();
	});
	editor.on('change', function (e) {
	    console.log('fillinng by name:' + e.editor.element.getId());
	    updateHidden($(this), e);

	});
	editor.on('blur', function (e) {
	    updateHidden($(this), e);
	});

    });

    for (i = 0; i < containers.length; i++) {

	var editor = CKEDITOR.instances[containers[i].id];
	if (editor) {
	    editor.destroy(true);
	}

	var customOptions = $(containers[i]).attr("js-options");
	if (typeof customOptions != 'undefined') {
	    customOptions = customOptions.replace(/'/g, '"');
	    options = $.extend({}, defaultOptions, jQuery.parseJSON(customOptions));
	} else {
	    options = defaultOptions;
	}

	new Waypoint.Inview({
	    element: $(containers[i]),
	    enter: function (direction) {
		var id = $(this)[0].element[0].id;
		console.log('view checking:' + id);
		if (typeof window.initedRichTextEditors[id.replace('fake', '')] == 'undefined')
		    window.initedRichTextEditors[id.replace('fake', '')] = 1;
		else
		    return false;
		var that = document.getElementById(id);
		var realContainer = document.getElementById(id.replace('fake', ''));

		console.log('real ckeditorer init:' + id);
		console.log(options);
		console.log('view initing:' + id);
		editor = CKEDITOR.replace(realContainer, options);
		window.initedRichTextEditors[id.replace('fake', '')] = editor;
		editor.on('instanceReady', function (e) {
		    $(that).remove();
		    removejscssfile('editor.css?t=H8DA1', 'css');
		});
		editor.on('change', function (e) {
		    updateHidden($(this), e);

		});
		editor.on('blur', function (e) {
		    updateHidden($(this), e);
		});
	    }
	});
    }

    for (var i in CKEDITOR.instances) {

	CKEDITOR.instances[i].on('change', function () {

	    var name = $(this).attr('name');
	    //TODO investigate further
	    //new ckeditor4 doesnt fire chnage event for original textarea
	    if ($('#previewReport').length) {
		var value = CKEDITOR.instances[name].getData();
		updatePreviewSlot(name, value);
	    }
	    CKEDITOR.instances[name].updateElement();
	});
    }

    //TODO check if we need this code with new ckeditor version
    //without this code inputs in ckeditor are disabled
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
	modal_this = this;
	$(document).on('focusin.modal', function (e) {
	    if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
		    // add whatever conditions you need here:
		    && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
		    && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
		modal_this.$element.focus();
	    }
	});
    }

    function removejscssfile(filename, filetype) {
	var targetelement = (filetype == "js") ? "script" : (filetype == "css") ? "link" : "none" //determine element type to create nodelist from
	var targetattr = (filetype == "js") ? "src" : (filetype == "css") ? "href" : "none" //determine corresponding attribute to test for
	var allsuspects = document.getElementsByTagName(targetelement)
	for (var i = allsuspects.length; i >= 0; i--) { //search backwards within nodelist for matching elements to remove
	    if (allsuspects[i] && allsuspects[i].getAttribute(targetattr) != null && allsuspects[i].getAttribute(targetattr).indexOf(filename) != -1)
		allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()
	}
    }

    updateHidden = function (that, e) {
	var name = that.attr('name');

	var textarea = $('textarea[name="' + e.editor.element.getId() + '"]');
	textarea.html(e.editor.getData());

	//TODO investigate further
	//new ckeditor4 doesnt fire chnage event for original textarea
	if ($('#previewReport').length) {
	    var value = CKEDITOR.instances[name].getData();
	    updatePreviewSlot(name, value);
	}
	//TODO what for ?
	CKEDITOR.instances[name].updateElement();
    }
}

function getCkeditorDefaultOptions() {
    var defaultOptions = {
	startupFocus: false,
	scayt_autoStartup: true,
	toolbarCanCollapse: true,
	toolbarStartupExpanded: false,
	extraPlugins: 'videodetector',
	toolbar: [
	    {name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
	    {name: 'editing', items: ['Scayt']},
	    {name: 'insert', items: ['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'Checkbox']},
	    {name: 'links', items: ['Link', 'Unlink', 'VideoDetector']},
	    '/',
	    {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
	    {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']},
	    {name: 'align', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight']},
	    '/',
	    {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
	    {name: 'colors', items: ['TextColor', 'BGColor']},
	    {name: 'tools', items: ['Maximize']}
	]
    };
    return defaultOptions;
}

function initGroupEditor(res) {

    attachGroupEditorHooks();
    attachEditProductionHooks();
    onLoadStartScripts();
    groupEditorHandler();

    if (typeof disableGroupEditorFlag !== 'undefined' && disableGroupEditorFlag === true)
	disableGroupEditor();
    $("input.active_tab").on('change', function () {
	restoreActiveTab();
	if (typeof window.isSelectedProductionApplied == 'undefined') {
	    $('#idChooseCompany').trigger("change");
	    window.isSelectedProductionApplied = true;
	}

    });
}

function groupEditorHandler() {
    var a = document.createElement('a');
    a.href = $('#parent-groups-editor-container').attr('data-load-url');
    var aParams = a.search.split('&');

    if (aParams[aParams.length - 1].indexOf('active_tab=') + 1) {
	var activeTab = aParams[aParams.length - 1].split('=');
	if (activeTab[1] !== 'null') {
	    $('input.active_tab').val(activeTab[1]);
	    restoreActiveTab();
	    console.log('groupEditorHandler: ' + $('input.active_tab').val());
	}
    }
}

function initSpinner() {
    console.log("init spinner");
    
    $("[js-role='spinner']").each(function () {

	var $element = $(this);
	var maxValue = parseInt($(this).attr("js-max-value"));
	var elementId = $element.id;
	var defaultValue = parseInt($('.spinbox-input', $element).val());
	var min = 1;
	if (!(defaultValue > 0))
	{
	    defaultValue = 1;
	}
	if (maxValue == 0)
	{
	    defaultValue = 0;
	    min = 0;
	}
	//TODO what for?
	$('#' + elementId).on('blur', function () {
	    var spinner = $("#MySpinner_" + elementId).spinner();
	    var num = spinner.spinner("value");
	    if (num < 1)
	    {
		spinner.spinner("value", 1);
	    }
	    if (num > maxValue)
	    {
		spinner.spinner("value", maxValue);
	    }
	});
	//if(typeof window.instances!=='undefined' && typeof window.instances[$(this).attr("name")] !== 'undefined'){

	//}
	//window.instances[$(this).attr("name")]  =
	var $parent = $(this).parent();
	var markup = '';
	if(typeof $(this).spinbox == 'function')
	    markup = $(this).spinbox('destroy');
	$parent.append(markup);
	
	var $clonedSpinner = $parent.find("[js-role='spinner']");
	
	
	//if initSpinner() called before library loaded nothing happens
	//for example other developer called initSpinner in his ajax callback, it will do nothing if spinner not loaded
	//and if loaded it will redefine...
	//TODO to make it faster to prevent duplication we need Js parse js roles function!!!
	if(typeof $clonedSpinner.spinbox== 'function'){
	    $clonedSpinner.spinbox({
		value: defaultValue,
		min: min,
		max: maxValue,
		step: 1
	    });
	}else{
	    console.log('spinner libary is not found. that can be ok');
	}
    })
}

function initZoomable() {
    var i = false;

    $('.control-item-dialog').off('shown.bs.modal.zoom').on('shown.bs.modal.zoom', function (e) {
	_init();
    });

    $('.control-item-dialog').off('hide.bs.modal.zoom').on('hide.bs.modal.zoom', function (e) {
	_destroy();
    });

    if (!i) {
	_init()
    }

    function _init() {
	$("img[js-role='zoomable']").elevateZoom({
	    constrainType: "height",
	    constrainSize: 274,
	    zoomType: "lens",
	    containLensZoom: true,
	    gallery: 'gallery_01',
	    cursor: 'pointer',
	    galleryActiveClass: "active"
	});
	i = true;
    }

    function _destroy() {
	$('#zoomed img').removeData('elevateZoom');
	$('img.zoomed').removeData('elevateZoom');//remove zoom instance from image
	$('.zoomWrapper img.zoomed').unwrap();
	$('.zoomContainer').remove();
    }
}

function initTags()
{
    if ($('#descriptionTags').length > 0)
    {
	$(document).ready(function () {
	    var descriptionTagsData = $('#descriptionTagsData').text().replace("['", '').replace("']", '').replace(/\"/g, "'").split("', '");

	    $('#descriptionTags').tagit({
		availableTags: descriptionTagsData,
		singleField: true,
		allowSpaces: true,
		autocomplete: {delay: 0, minLength: 0},
		fieldName: "descriptionTags"
	    });

	    if ($('#descriptionTagsData').attr('for') === 'view') {
		loadInventoryViewFunctions();
	    }
	    if ($('#descriptionTagsData').attr('for') === 'add') {
		loadInventoryAddFunctions();
	    }
	    $(document).on('click', '.ui-autocomplete-input', function () {
		$(this).data("autocomplete").search('');
	    });
	});
    }
}
//http://www.malot.fr/bootstrap-datetimepicker
function initDate()
{

    $('input[js-role="date"]').parent().datetimepicker({
	language: "de",
	todayBtn: 1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0,
        format: 'dd.mm.yyyy'
    });
    console.log('AAA');
    $(document).on('hover', 'input[js-role="date"]', function () {
	if (!isMobile())
	    $(this).attr('readonly', false);
    });
    $('input[js-role="date"]').each(function () {

	var linkedField = $(this).parent().attr('data-link-field');
	if (linkedField != '') {
	    var date = $('#' + linkedField).val();
	    if (typeof date !== 'undefined') {

		var dateArr = date.split('.');
		var dateObj = new Date(dateArr[0], dateArr[1] - 1, dateArr[2]);
		$(this).parent().data('datetimepicker').setDate(new Date(dateObj));
	    }
	}
    });

}

function initTime()
{
    $('input[js-role="time"]').timepicker({
	showInputs: true,
	defaultTime: 'value',
	showMeridian: timepickerMeridian,
	minuteStep: 15
    });
}

function initColorPicker() {
    if ($('input[js-role="colorpicker"]').length < 1)
	return true;
    //$(".color").colorpicker();

    var miniColorsConfig = {
	control: $(this).attr('data-control') || 'hue',
	defaultValue: $(this).attr('data-defaultValue') || '',
	inline: $(this).attr('data-inline') === 'true',
	letterCase: $(this).attr('data-letterCase') || 'lowercase',
	opacity: $(this).attr('data-opacity'),
	position: $(this).attr('data-position') || 'bottom left',
	change: function (hex, opacity) {
	    if (!hex)
		return;
	    if (opacity)
		hex += ', ' + opacity;
	    try {
		console.log(hex);
	    } catch (e) {
	    }
	},
	theme: 'bootstrap'
    };

    $('input[js-role="colorpicker"]').each(function () {
	$(this).minicolors(miniColorsConfig);

    });
}
function initPairDate(external_config)
{

    var modalPairDate = '';
    var submitButton = '';
    var element = $('[js-role="dates-pair"]');

    var default_config = {
	validation: true
    };
    var config = default_config;
    if (typeof config !== 'undefined')
	config = $.extend(true, default_config, external_config)
    if ($('div.modal').length > 0) {
	modalPairDate = 'div.modal ';
	var datePickersInModalWindow = $('div.modal input[js-role="date"]').length;
	var allDatePickers = $('input[js-role="date"]').length;
	if (allDatePickers > datePickersInModalWindow) {

	    initDate();
	}
	var timePickersInModalWindow = $('div.modal input[js-role="time"]').length;
	var allTimePickers = $('input[js-role="time"]').length;
	if (allTimePickers > timePickersInModalWindow) {

	    initTime();
	}

	submitButton = $('div.modal input.control-submit-button, div.modal button.control-submit-button');
    }

    if ($(modalPairDate + 'div[dates-pair-submit-buttons]').length > 0) {
	var submitButtonsIds = $(modalPairDate + 'div[dates-pair-submit-buttons]').attr('dates-pair-submit-buttons');
	submitButton = $(submitButtonsIds);
    }

    var startDate = '#' + $(modalPairDate + 'div[js-role="dates-pair"] input[js-role="date"]:first').attr('id');
    var endDate = '#' + $(modalPairDate + 'div[js-role="dates-pair"] input[js-role="date"]:last').attr('id');

    if (submitButton === '') {
	submitButton = $('input:submit, button:submit');
    }

    $(document).on('hover', 'input[js-role="date"]', function () {
	if (!isMobile())
	    $(this).attr('readonly', false);
    });
    if (config.validation) {
	Form.addValidation(element, function (e) {

	    if ($('div[js-role="dates-pair"] div.alert-error').length > 0) {
		$('div[js-role="dates-pair"] div.alert-error').remove();
	    }

	    if (isEndingDateEarlier($(startDate).attr('id'), $(endDate).attr('id'))) {
		$(modalPairDate + 'div[js-role="dates-pair"]').append('<div class="alert alert-error">Your event\'s end time occurs before its start time.</div>');
		$('html, body').animate({
		    scrollTop: $("div.alert-error:first").offset().top
		}, 1000);
		return false;
	    }
	    return true;
	});
    }
}
function initSubmitButton()
{
    $('.controls').on('click', '[js-role="submit-button"]', function (e) {
	Form.validate($('[js-role="submit-button"]'));
    });
}

function initScheduler() {
    $('#myScheduler').closest('body').addClass('fuelux');

    $('#myScheduler').scheduler({
	startDateOptions: {
	    allowPastDates: true
	},
	endDateOptions: {
	    momentConfig: {
		format: 'DD MMM YYYY'
	    }
	}
    });

    _loadData();
    $(document).on('change', '#schedulerData', function () {
	_loadData();
    });
    $('#s').change(function () {
	$('#myStartDate').val($(this).val()).trigger('change').trigger('blur');
    });
    $('#myScheduler').on('changed.fu.scheduler', function () {
	var schedulerData = $('#myScheduler').scheduler('value');
	$('#schedulerData').val(schedulerData.recurrencePattern);
    });
    $('#myEndDate').change(function () {
	var schedulerData = $('#myScheduler').scheduler('value');
	$('#schedulerData').val(schedulerData.recurrencePattern);
    });
    function _loadData() {
	//console.log('laoding data');
	if ($('#schedulerData').length > 0) {
	    $('#myScheduler').scheduler('value', {
		startDateTime: $('#s').val(),
		timeZone: {
		    offset: '+00:00'
		},
		recurrencePattern: $('#schedulerData').val()
	    });
	}
    }
}

function initTimesTable() {
    var timerId = setInterval(function () {
	if ($('.table.table-first-column-number').innerHeight() > 0) {
	    $('.scroll-pane').jScrollPane({autoReinitialise: true});
	    $(".jspContainer .table-responsive").css('overflow-x', 'visible');
	    clearInterval(timerId);
	}
    }, 100);
}


function initJsTree() {
    var $element = $('[js-role="jstree"]');
    var settings = $element.attr('js-options');
    var options = {};
    options.files = false;
    if (typeof settings !== 'undefined' && settings.indexOf('files') > -1) {
	options.files = true;
	options.plugins = ["sort"];
    } else {
	options.plugins = ["wholerow", "checkbox", "search", "sort"];
    }

    $element.bind('open_node.jstree', function (e, data) {
	//we are saving opened node
	window.openedJstreeNode = data.node.id;
	console.log('OPENED:' + window.openedJstreeNode);
    }).
	    bind('select_node.jstree', function (e, data) {
		if (options.files === false) {
		    //debugger;
		    var node_id = data.node.id;
		    var itemId = node_id.replace('li_node_', '');

		    if ($('input[name="position"]').length == 0) {
			alert("init.js Error hidden input field is missing");
		    }
		    $('input[name="position"]').val(itemId);
		}
	    }).
	    bind('click.jstree', function (e, data) {
		//we want to prevent select when we click edit element
		if (e.target.className == 'icon-cog' || e.target.className == 'control-item-edit') {
		    var els = e.originalEvent.path;
		    id = '';
		    for (i = 0; i < els.length; i++) {
			if ($(els[i]).hasClass('jstree-node')) {
			    id = $(els[i]).prop('id');
			    break;
			}
		    }
		    $('[js-role="jstree"]').jstree(true).deselect_node(id);
		}
	    }).
	    bind('ready.jstree', function (e, data) {

		if (window.openedJstreeNode != false) {
		    // data.instance._open_to(window.openedJstreeNode);
		    var current = window.openedJstreeNode;
		    console.log('OPEN NODE' + window.openedJstreeNode);

		    var parent = [];

		    $('[js-role="jstree"]').jstree(true)._open_to(current);
		    $('[js-role="jstree"]').jstree(true).open_node(current);
		    window.openedJstreeNode = current;

		}
	    }).jstree(
	    {
		"core": {
		    "themes": {
			"variant": "large"
		    },
		    'multiple': false,
		},
		"plugins": options.plugins,
		"checkbox": {"three_state": false}
	    }
    );
    if (options.files) {
	$element.on("click", "ul li a.jstree-anchor", function () {
	    $(this).trigger("scroll");
	    var thisObj = this;
	    $.ajax({
		url: "/fileshare/ajax/?action=displayFolderFiles&folder_id=" + $(this).attr("data-id"),
		type: "GET",
		dataType: 'json',
		success: function (data) {
		    $('.fileshare-files-list').empty();
		    $('.fileshare-files-list').append(data['fileExplorerPage']);
		    $('.pagination-bar').empty();
		    $('.pagination-bar').append(data['pagination']);

		    runImgLazyLoad();
		    forbidMultiSelect(thisObj);
		    $(thisObj).trigger("scroll");

		},
		error: function (thrownError) {
		    console.log(thrownError);
		}
	    });
	});
    }
}
function initSaveState() {
//    TODO change all .saveclick-tabs classes with js-rols='save-state'
    var hash = window.location.hash;
    hash && $('[js-role="save-state"] a[href="' + hash + '"]').tab('show');

    $('[js-role="save-state"] a').on('click', function (e) {
	window.location.hash = this.hash;
    });
}

function initValidateForm(config) {

    $("[js-role^='validate_before_form_submit']").each(function () {

	//todo unattach validation ????
	//todo for hidden fields we should hide error (revalidate on entering values!!!!)
	var final_config = {
	    errorClass: 'error',
	    //hidden elements are validated now
	    ignore: [],
	    //this scrolls up to error position
	    focusInvalid: false,
	    invalidHandler: function (form, validator) {

		if (!validator.numberOfInvalids())
		    return;
		$("[js-role='date']").on('change', function () {
		    $('form label.error').hide();
		});
		//todo unattach
		$(validator.errorList[0].element).on('change',function(){
		    if($(validator.errorList[0].element).hasClass('select2-hidden-accessible')){
			var $custom_selector = $(validator.errorList[0].element).parent().find('span.select2');
			$custom_selector.removeClass("error");
			
			var $error_message = $(validator.errorList[0].element).parent().find('label.error');
			$error_message.remove();
		    }
		})
		debugger;
		//TODO what happens if many errors?
		if($(validator.errorList[0].element).hasClass('select2-hidden-accessible')){
		    var $custom_selector = $(validator.errorList[0].element).parent().find('span.select2');
		    $custom_selector.addClass("error");
		}
		if ($(validator.errorList[0].element).attr('js-role') == 'error-placeholder') {
		    $(validator.errorList[0].element).show();
		    $(validator.errorList[0].element).attr('type', 'text');
		}

		$('.modal-body').animate(
			{scrollTop: $(validator.errorList[0].element).focus().top - 100},
			1000,
			'swing',
			function () {
			    $(validator.errorList[0].element).blur();
			}
		);
		if ($(validator.errorList[0].element).attr('js-role') == 'error-placeholder')
		    $(validator.errorList[0].element).hide();
	    }
	};
	final_config = $.extend(true, final_config, config);

	$(this).on('click', function () {

	    console.log('We catched validation request');
	    console.log(final_config);
	    var validator = $(".modal-body .form-horizontal").validate(final_config);
	    var result = validator.form();

	    if (result === false) {
		console.log('initValidateForm says: validation not passed');
		SiteControl.validationFailed = true;
		return;
	    } else {
		// $('.next-button').off('click'); i don't know why we off handler if failed
		console.log('initValidateForm says: validation passed');
		SiteControl.validationFailed = false;
	    }
	});
    });



}

function initCheckAll(){
    $("[js-role='check-all']").each(function () {
	$(this).on('change', function () {
	    if(this.checked){
		$("table input[type='checkbox']").each(function () {
		    if($(this).attr('js-role')!='check-all' && $(this).prop('checked')===false )
			$(this).trigger('click');
		});
	    }else{
		$("table input[type='checkbox']").each(function () {
		    if($(this).attr('js-role')!='check-all' && $(this).prop('checked')===true)
			$(this).trigger('click');
		});
	    }
	});
    });
}

