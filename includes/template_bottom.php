<?php
    /*
        $Id$
        
        osCommerce, Open Source E-Commerce Solutions
        http://www.oscommerce.com
        
        Copyright (c) 2010 osCommerce
        
        Released under the GNU General Public License
    */
?>

</div> <!-- bodyContent //-->

<?php
    if ($oscTemplate->hasBlocks('boxes_column_left') && basename( $PHP_SELF ) !== 'bts_header_builder.php'  && basename( $PHP_SELF ) !== 'bts_css_editor.php') {
    ?>
    
    <div id="columnLeft" class="col-md-<?php echo $oscTemplate->getGridColumnWidth(); ?>  col-md-pull-<?php echo $oscTemplate->getGridContentWidth(); ?>">
        <?php echo $oscTemplate->getBlocks('boxes_column_left'); ?>
    </div>
    
    <?php
    }
    
    if ($oscTemplate->hasBlocks('boxes_column_right') && basename( $PHP_SELF ) !== 'bts_header_builder.php'  && basename( $PHP_SELF ) !== 'bts_css_editor.php') {
    ?>
    
    <div id="columnRight" class="col-md-<?php echo $oscTemplate->getGridColumnWidth(); ?>">
        <?php echo $oscTemplate->getBlocks('boxes_column_right'); ?>
    </div>
    
    <?php
    }
?>

</div> <!-- row -->

</div> <!-- bodyWrapper //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<script src="ext/bootstrap/js/bootstrap.min.js"></script>
<script src="ext/bootstrap/js/jquery.dnd_page_scroll.js"></script>
<script src="ext/bootstrap-iconpicker/js/bootstrap-iconpicker.js"></script>

<script>
    $(function () {
        $().dndPageScroll();
        //##drag&drop events start##
        
        $('#dndPlaceHolder').on('dragstart', '.dragMe', function (event) {
            event.originalEvent.dataTransfer.setData("Text", event.target.getAttribute('id'));
        });
        
        // on the dragover event on the placeholder sections
        $('#dndPlaceHolder').on('dragover', '#header-container, #header-storage, #css-container', function (event) {
            event.preventDefault();
            //$(event.target).css({"background-color":"#AA0000"});
        });
        
        // on the drop event on the placeholder sections
        $('#dndPlaceHolder').on('drop', '#header-container, #header-storage', function (event) {
            event.preventDefault();
            
            $move_action = 'keep_grouped'
            status_val = '1';
            dragElement = event.originalEvent.dataTransfer.getData("Text");
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
                
                }, "json").done(function () {
                resetHeader();
            });
            
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
            '<div class="row"><div class="col-md-12">' +
            '<p class="text-left">' + $type + ' <span class="pull-right badge">' + $id + '</span></p>' +
            '<p class="text-left">Name : <span class="pull-right badge">' + $_name + '</span></p>' +
            '<p class="text-left">css #Id : <span class="pull-right badge">' + $_css_id + '</span></p>' +
            '<p class="text-left">css Class : <span class="pull-right badge">' + $_class + '</span></p>' +
            '<p class="text-left">Url : <span class="pull-right badge">' + $_url + '</span></p>' +
            '<p class="text-left">Icon : <span class="pull-right badge">' + $_icon + '</span></p>' +
            '<p class="text-left">sort order : <span class="pull-right badge">' + $_sort_order + '</span></p>' +
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
            e.preventDefault();
            
            $('#return-form-result').empty();
            
            $id = $(e.target).parent().attr("id");
            
            formAction = $(this).attr("data-action");
            
            if (formAction == 'create_header') {
                formType = $(this).attr("data-type");
            }
            if ((formAction == 'edit_header') || (formAction == 'delete_header_confirm') || (formAction == 'status_disable') || (formAction == 'status_enable')) {
                formType = $(e.target).parent().attr("id");
            }
            
            $.post("ajax_update_parent.php", {
                action: formAction,
                form_type: formType
                }, function (data) {
                if ((formAction == 'create_header') || (formAction == 'edit_header')) {
                    $('#return-form-result').append(data); //form results
                    $('#myModal').modal('show')
                    $('#convert').iconpicker({
                        iconset: 'fontawesome'
                    });
                }
                if ((formAction == 'delete_header_confirm') || (formAction == 'status_disable') || (formAction == 'status_enable')) {
                    $(' #header-result ').html(data);
                }
                }, "json").done(function () {
                resetHeader();
            });
        });
        //##drag boxes icon actions end##
        
        
        //##Form new/edit events start##
        $('#dndPlaceHolder').submit('form[name="cart_quantity"]', function (e) {
            e.preventDefault();
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
                    resetHeader();
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
    $(document).on("dblclick", ".edit", function () {
        makeEditable(this);
    });
    $(document).on("blur", "#editbox", function () {
        removeEditable(this)
    });
    $(document).on("click", ".css-action", function (e) {
        e.preventDefault();
        CssAction(this)
    });
    
    function CssAction(element) {
        $id = $(element).parent().attr("data-element-id");
        type = $(element).attr('data-css-action-type');
        css_action = $(element).attr('data-css-action');
        
        $.post('ajax_css_editor.php', {
            action: css_action,
            css_action_type: type,
            selectors_id: $id
            }, function (data) {
            console.log(data)
            $(' #css-container ').html(data);
            
        }, "json");
    }
    
    function removeEditable(element) {
        
        //$('#indicator').show();
        $(element).parent().parent().attr('draggable', 'true');
        
        id = $('.current').attr('edit_id');
        field = $('.current').attr('field');
        //.replace(/(['"])/g, "\\$1")
        newvalue = $(element).val();
        
        if ($(element).val() == '') {
            newvalue = '<i class="fa fa-angellist"></i>must not be empty';
        }
        
        $.post('ajax_css_editor.php', {
            action: 'update_css',
            selectors_id: id,
            selectors_field: field,
            selectors_data: newvalue
            }, function (data) {
            //console.log(data)
            $(' #css-container ').html(data);
            resetHeader();
            
        }, "json");
    }
    
    function makeEditable(element) {
        $(element).parent().attr('draggable', 'false');
        
        $(element).removeClass('edit');
        elField = $(element).attr('field');
        if (elField === 'selectors_name') {
            $(element).html('<input class="form-control" id="editbox" size="' + $(element).text().length + '" type="text" value="' + $(element).text() + '">');
        }
        if (elField === 'selectors_properties') {
            $(element).html('<textarea class="form-control" id="editbox">' + $(element).html().replace("<textarea>", " ") + '</textarea>');
        }
        $('#editbox').focus();
        $(element).addClass('current');
    }
    
    
    // on the drop event on the placeholder sections
    $('#dndPlaceHolder').on('drop', '#css-container', function (event) {
        event.preventDefault();
        
        
        dragElement = event.originalEvent.dataTransfer.getData("Text");
        event.target.appendChild(document.getElementById(dragElement));
        
        $id = $(event.target).attr("data-element-id");
        container = $(event.target).closest('#css-container, #css-storage').attr('id');
        //alert($id+'--------'+container);
        
        $.post("ajax_css_editor.php", {
            action: "move_header_confirm",
            selectors_id: dragElement,
            drop_id: $id
            }, function (data) {
            $(' #css-result ').html(data);
            
            }, "json").done(function () {
            resetHeader();
        });
        
    });
    //css editor End
	
    function resetHeader(){
        $.post("ajax_reset.php", {
            action: "reset_header",
            }, function (data) {
            divs = data.split('|');
            $("#css-area").html(divs[0]); //add input box
            $("#header-area").html(divs[1]); //add input box
        }, "json");
    }
    
    function showiconpicker(){
        
        $(document).off( "click", "#convert");
        $('#convert').iconpicker({
            iconset: 'fontawesome',
            rows: 5,
            cols: 5,
            placement: 'right'
        });                    
    }
</script>
<script>
    
    $(document).ready(function() {
        
        var docHeight = $(window).height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;
        
        if (footerTop < docHeight) {
            $('#footer').css('margin-top', 0+ (docHeight - footerTop) + 'px');
        }
    });
</script>
<?php echo $oscTemplate->getBlocks('footer_scripts'); ?>

</body>
</html>
