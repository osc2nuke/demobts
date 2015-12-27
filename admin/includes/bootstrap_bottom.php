<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>

</div>



<div id="footer" class="footer">
      <div class="container">
        <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
      </div>
    </div>
	
<script>
$(function () {
		$().dndPageScroll();
    //##drag&drop events start##

    $('#dndPlaceHolder').on('dragstart', '.dragMe', function (event) {
        event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
    });

    // on the dragover event on the board sections
    $('#dndPlaceHolder').on('dragover', '#header-container, #header-storage', function (event) {
        event.preventDefault();
        //$(event.target).css({"background-color":"#AA0000"});
    });

    // on the drop event on the board sections
    $('#dndPlaceHolder').on('drop', '#header-container, #header-storage', function (event) {
        event.preventDefault();

         $move_action = 'keep_grouped'
         status_val = '1';
         dragElement = event.originalEvent.dataTransfer.getData("text/plain");
        event.target.appendChild(document.getElementById(dragElement));

        // Turn off the default behaviour
        // without this, FF will try and go to a URL with your id's name
         $id = $(event.target).attr("data-element-id");
         container = $(event.target).closest('#header-container, #header-storage').attr('id');
        if (container == 'header-storage') status_val = '3';
        if (container == 'header-storage') $id = '0';
        if (container == 'header-storage') $move_action = '';
        $.post("ajax_update_parent.php", {
            action: "move_header_confirm",
            move_action: $move_action,
            drag_id: dragElement,
            drop_id: $id,
            status: status_val
        }, function (data) {
            $(' #header-result ').html(data);

        }, "json");

    });
    //##drag&drop events end##

    //##popover content start##

    $('#dndPlaceHolder').on("mouseleave", 'span.label, i.pover', function (e) {
        $('span.label, i.pover').popover('destroy');
    });

    $('#dndPlaceHolder').on("mouseenter", 'span.label, i.pover', function (e) {

	e.preventDefault();
	
         $id = $(e.target).parent().attr("data-element-id");
         $type = $(e.target).parent().attr("data-type");
         $_name = $(e.target).parent().attr("data-headers-name");
         $_class = $(e.target).parent().attr("data-class");
         $_css_id = $(e.target).parent().attr("data-css-id");
         $_url = $(e.target).parent().attr("data-url");
         $_icon = $(e.target).parent().attr("data-fa-icon");
         $_sort_order = $(e.target).parent().attr("data-sort-order");
		
         popOverContent = '<div class="thumbnail">' +
            '<div class="row"><div class="col-md-12">'+
			'<p class="text-left">' + $type + ' <span class="pull-right badge">' + $id + '</span></p>'+
			'<p class="text-left">Name : <span class="pull-right badge">' + $_name + '</span></p>'+
			'<p class="text-left">css #Id : <span class="pull-right badge">' + $_css_id + '</span></p>'+
			'<p class="text-left">css Class : <span class="pull-right badge">' + $_class + '</span></p>'+
			'<p class="text-left">Url : <span class="pull-right badge">' + $_url + '</span></p>'+
			'<p class="text-left">Icon : <span class="pull-right badge">' + $_icon + '</span></p>'+
			'<p class="text-left">sort order : <span class="pull-right badge">' + $_sort_order + '</span></p>'+
            '</div></div>' +
            '</div>';
        options = {
            html: true,
            placement: 'top',
            content: popOverContent,
            trigger: 'manual'
        }
        $(e.target).popover(options).popover('show');

    });
    //##popover content end##

    //##drag boxes icon actions start##
    $('#dndPlaceHolder').on('click', '.action', function (e) {
        $('#return-form-result').empty();
		
	    $id = $(e.target).parent().attr("id");
        
		formAction = $(this).attr("data-action");

        if (formAction == 'create_header') {
            formType = $(this).attr("data-type");
        }
        if ((formAction == 'edit_header') || (formAction == 'delete_header_confirm') || (formAction == 'status_disable')|| (formAction == 'status_enable')) {
		formType = $(e.target).parent().attr("id");
        }

        $.post("ajax_update_parent.php", {
            action: formAction,
            form_type: formType
        }, function (data) {
            if ((formAction == 'create_header') || (formAction == 'edit_header')) {
                $('#return-form-result').append(data); //form results
                    $('#myModal').modal('show')

			}
            if ((formAction == 'delete_header_confirm') || (formAction == 'status_disable')|| (formAction == 'status_enable')) {
                $(' #header-result ').html(data);
            }
        }, "json");

    });
    //##drag boxes icon actions end##
	

    //##Form new/edit events start##
    $('#dndPlaceHolder').submit('form[name="cart_quantity"]', function (e) {
        e.preventDefault();
		alert('huh');
        formID = $('form[name="cart_quantity"]').attr('id');
        $id = $(this).find(':input').serializeArray();

        $id.push({
            name: "action",
            value: formID
        });

        $.post("ajax_update_parent.php", $id, "json").done(function () {
            $('#myModal').modal('hide');

            $('#myModal').on('hidden.bs.modal', function (e) {
                $('#return-form-result').empty();
                $.post("ajax_update_parent.php", {
                    action: 'reload'
                }, function (data) {
                    console.log(data)
                    $(' #header-result ').html(data);

                }, "json");

            })
        });

        return false;

    });


    $('#dndPlaceHolder').on('click', '.cancel', function (e) {

         setTimeout(function () {
            $('.container.thumbnail').addClass('hidden');
            $('#return-form-result').empty();
        }, 800);
 
    });
    //##Form new/edit events ends##

});

//css editor start
    var max_fields = 5; //maximum input boxes allowed

    //new fields when create new selector	
    var wrapper = $(".input_fields_wrap"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID

    //new fields when edit
    var editNew = $(".input_new_edit_fields_wrap"); //Fields wrapper
    var add_new_edit_button = $(".add_new_edit_field_button"); //Add button ID

    var x = 1; //initlal text box count

    $(add_new_edit_button).click(function (e) { //on add input button click
        e.preventDefault();
        var selector_id = $(this).attr('id');
		
 $.post("ajax_new_css_field.php", {
            action: "new_field",
            sel_id: selector_id
        }, function (data) {
              $("form[id="+selector_id+"] .input_new_edit_fields_wrap").append(data); //add input box
        }, "json");

    });
	
    $(editNew).on("click", ".remove_new_edit_field", function (e) { //user click on remove text
        e.preventDefault();
        var field_id = $(this).parent().parent().attr('id');
        $.post("ajax_new_css_field.php", {
            action: "delete_field",
            field_id: field_id
        });
        $(this).parent().parent().remove();

    });


    $(add_button).click(function (e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="form-group"><div class="row"><div class="col-md-5"><input type="text" name="property_element[]" class="form-control" placeholder="property element"/></div><div class="col-md-5"><input type="text" name="property_value[]" class="form-control" placeholder="property value" /></div><div class="col-md-2"><a href="#" class="remove_field">Remove</a></div></div></div>'); //add input box

        }
    });

    $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        $(this).parent().parent().remove();
        x--;
    });
//css editor end
</script>
</body>
</html>