jQuery(document).ready(function () {

    // $(".delete").on("submit", function(){
    //     return confirm("Are you sure?");
    // });

    $('button[data-toggle="modal"]').click(function () {
      $($(this).data('target')).show();
    })
    $('button.close').click(function(){
        $('.modal').hide();
    });

    $('input[type="checkbox"]').each(function(){
        var parent = $(this).parent().parent().parent();
        if($(this).prop("checked")){
            $(parent).next('.form-group.form-check').show();
        }
        else{
            $(parent).next('.form-group.form-check').hide();
        }
    });
    $('input[type="checkbox"]').change(function(){
        var parent = $(this).parent().parent().parent();
        if($(this).prop("checked")){
            $(parent).next('.form-group.form-check').show();
        }
        else{
            $(parent).next('.form-group.form-check').hide();
        }
    });


    $('.scrollup').fadeOut();
    $(window).scroll(function(){
	if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
	} else {
            $('.scrollup').fadeOut();
	}
    });

    $('.scrollup').click(function(){
	$("html, body").animate({ scrollTop: 0 }, 600);
    	return false;
    });

     $( function() {
        $.widget( "custom.combobox", {
          _create: function() {
            this.wrapper = $( "<span>" )
              .addClass( "custom-combobox" )
              .insertAfter( this.element );

            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
          },

          _createAutocomplete: function() {
            var selected = this.element.children( ":selected" ),
              value = selected.val() ? selected.text() : "";

            this.input = $( "<input>" )
              .appendTo( this.wrapper )
              .val( value )
              .attr( "title", "" )
              .attr( "name", "address" )
              .attr( "placeholder", "Enter New Address" )
              .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
              .autocomplete({
                delay: 0,
                minLength: 0,
                source: $.proxy( this, "_source" )
              })
              .tooltip({
                classes: {
                  "ui-tooltip": "ui-state-highlight"
                }
              });

            this._on( this.input, {
              autocompleteselect: function( event, ui ) {
                ui.item.option.selected = true;
                this._trigger( "select", event, {
                  item: ui.item.option
                });
              },
            });
          },

          _createShowAllButton: function() {
            var input = this.input,
              wasOpen = false;

            $( "<a>" )
              .attr( "tabIndex", -1 )
              .attr( "title", "Show All" )
              .tooltip()
              .appendTo( this.wrapper )
              .button({
                icons: {
                  primary: "ui-icon-triangle-1-s"
                },
                text: false
              })
              .removeClass( "ui-corner-all" )
              .addClass( "custom-combobox-toggle ui-corner-right" )
              .on( "mousedown", function() {
                wasOpen = input.autocomplete( "widget" ).is( ":visible" );
              })
              .on( "click", function() {
                input.trigger( "focus" );

                // Close if already visible
                if ( wasOpen ) {
                  return;
                }

                // Pass empty string as value to search for, displaying all results
                input.autocomplete( "search", "" );
              });
          },

          _source: function( request, response ) {
            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
            response( this.element.children( "option" ).map(function() {
              var text = $( this ).text();
              if ( this.value && ( !request.term || matcher.test(text) ) )
                return {
                  label: text,
                  value: text,
                  option: this
                };
            }) );
          },


          _destroy: function() {
            this.wrapper.remove();
            this.element.show();
          }
        });

        //$( "#order-combobox" ).combobox();
        $( "#order-combobox" ).combobox();
      } );
});



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

    /*$('.email-update *[type="checkbox"]').on('change', function () {
        if ($(this).is(':checked')) {
            $(this).val('checked');
        } else {
            $(this).val('');
        }

        $.ajax({
          beforeSend: function(request) {
            request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
          },
          url: '/orders/isChecked',
          dataType: "html",
          type: 'POST',
          data: {
            checked:$(this).val(),
            assistant:$('select[name=\'assistant\']').val()
          },
          error: function () {
           alert("ajax request failed");
          },
          success: function (data) {

          }
       });

    });*/
});



