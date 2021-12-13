Admin = '';

(function () {

	var Admin = {
                updateRequest: function(that) {
                    //debugger;
                    const publicNotes = $(that).parent().find("textarea[name^='public_notes']").val();
                    const privateNotes = $(that).parent().find("textarea[name^='private_notes']").val();
                    const date = $(that).parent().find("input[name^='date']").val();
                    let requestId = $(that).parent().parent().attr('id');
                    requestId = requestId.replace('extended-','');
                              console.log(requestId);
                       $.ajax({
                                    beforeSend: function(request) {
                                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                                    },
				    url: '/orders/updateRequest',
				    dataType: "html",
				    type: 'POST',
				    data: {
                        public_notes: publicNotes,
                        private_notes: privateNotes,
                        date: date,
                        request_id: requestId
                    },
				    error: function () {
					    alert("ajax request failed");
				    },
				    success: function (data) {
                        console.log(data);
				    }
			    });
                },

                updateRequestStatus: function(status, requestId) {
                    $.ajax({
                        beforeSend: function(request) {
                            request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                        },
    				    url: '/orders/updateRequestStatus',
    				    dataType: "html",
    				    type: 'POST',
    				    data: {
                            request_id: requestId,
                            status: status
                        },
    				    error: function () {
    					    alert("Request failed");
    				    },
    				    success: function (data) {
                            var requestSection = $('#request-' + requestId);
                            var theStatusColumn = requestSection.find('.status-column');
                            var theStatusText = requestSection.find('.status-column-text');
                            console.log(status);
                            theStatusText.text(status);
                            if (status === 'Completed') {
                                theStatusColumn.css({'background-color' : '#cef9ce', 'text-align' : 'center'});
                            } else {
                                theStatusColumn.css({'background-color' : 'transparent', 'text-align' : 'center'});
                            }
    				    }
  			        });
                },

                updateSubsectionStatus: function(requestId, key) {
                    $.ajax({
                        beforeSend: function(request) {
                            request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                        },
    				    url: '/orders/updateRequestStatus',
    				    dataType: "html",
    				    type: 'POST',
    				    data: {
                            request_id: requestId,
                            subsection: $('#request-' + requestId + ' #subsection-' + key).attr('data-sub')
                        },
    				    error: function () {
    					    alert("Request failed");
    				    },
    				    success: function () {
                            var subsection = $('#request-' + requestId + ' #subsection-' + key);
                            var newStatus = subsection.attr('data-sub');
                            var theStatusColumn = subsection.closest('tr').find('.status-column');
                            var theStatusText = subsection.closest('tr').find('.status-column-text');
                            theStatusText.text(newStatus);
                            if (newStatus === 'Completed') {
                                theStatusColumn.css({'background-color' : '#cef9ce', 'text-align' : 'center'});
                            } else {
                                theStatusColumn.css({'background-color' : 'transparent', 'text-align' : 'center'});
                            }
    				    }
  			        });
                },

                createRequest: function(that) {
                    //debugger;
                    var $formContainer = $(that).parent().parent().parent();
                    var publicNotes = $formContainer.find("textarea[name^='public_notes']").val();
                    var privateNotes = $formContainer.find("textarea[name^='private_notes']").val();
                    var date = $formContainer.find("input[name='date']").val();
                    var name = $formContainer.find("input[name='request']").val();
                    var agent_id = $formContainer.find("select[name='agent_id']").val();

                    $.ajax({
                        beforeSend: function(request) {
                            request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                        },
    				    url: '/orders/storeRequest',
    				    dataType: "html",
    				    type: 'POST',
    				    data: {
                          request : name,
                          agent_id: agent_id,
                          date: date,
                          public_notes: publicNotes,
                          private_notes: privateNotes,
                        },
    				    error: function () {
    					    alert("ajax request failed");
    				    },
    				    success: function (data) {
    					    $('#ModalAddRequest').modal('toggle');
    				    }
    			    });
                }


	};
	var c = Admin;
	window.Admin = Admin;
})();




$(document).ready( function() {
    $(document).on('change', '.custom-file-input:file', function() {
        var input = $(this),
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
    });

    $('.custom-file-input:file').on('fileselect', function(event, label) {

        var input = $('.custom-file-label').text(''),
            log = label;
        if( input.length ) {
            input.text(log);
        } else {
            if( log ) alert(log);
        }

    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".custom-file-input").change(function(){
        readURL(this);
    });

    jQuery(function($){
        $('.table:not("#transaction_table")').footable();
    });

    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        console.log(uri);
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        else {
            return uri + separator + key + "=" + value;
        }
    }

    $(".filter-to-show input[type=radio]").change(function() {
        var url = window.location.href,
            value = $(this).val();

        if (history.pushState) {
            url = updateQueryStringParameter(url, 'status', value);
            url = updateQueryStringParameter(url, 'page', 1);
            window.history.pushState({path:url}, '', url);
            window.location.href = url;
        }
    });

    $(".filter-agent select").change(function() {
        var url = window.location.href,
            value = $(this).val();

        if (history.pushState) {
            url = updateQueryStringParameter(url, 'agent', value);
            url = updateQueryStringParameter(url, 'page', 1);
            window.history.pushState({path:url}, '', url);
            window.location.href = url;
        }
    });

    $("#ModalAddRequest").on('change', 'input[name=request_type]', function(e) {
        console.log($(this).val());
        if ($(this).val() === 'ad_hoc_form') {
            $('#fieldCustomName input').prop('required', true);
            $('#fieldCustomName').show();
            $('#fieldDate').show();
            $('#fieldPublicNotes').show();
            $('#fieldPrivateNotes').show();
            $('#fieldAddress span.if-applicable').show();
            $('#fieldAddress input').prop('required', false);
        }
        else
        if ($(this).val() === 'with_address_form') {
            $('#fieldCustomName input').prop('required', false);
            $('#fieldCustomName').hide();
            $('#fieldDate').hide();
            $('#fieldPublicNotes').hide();
            $('#fieldPrivateNotes').hide();
            $('#fieldAddress span.if-applicable').hide();
            $('#fieldAddress input').prop('required', true);
        }
    });

    // $('#ModalAddRequest form').on('submit', function (e) {
    //     e.preventDefault();
    //     console.log(e);
    // });


});



//window.onload = function () {
//    console.log('INIT VUE');
//    const app = new Vue({
//        el: '#app2'
//    });
//
//};