$(document).ready(function () {
    // delete product attribute from the product
    $('.product_attributes').on('click', '.product-attribute-delete', function () {
        var deleteUrl = $(this).attr('delete-url');
        
        var pjaxContainer = $(this).attr('pjax-container');

        $.ajax({
            url : deleteUrl,
            type : "POST",
            error : function (xhr){
                alert('Error : ' + xhr.responseText);
            },

        }).done(function (data){
            $.pjax.reload({container: '#' + pjaxContainer});
        });

    });

    // override timeout value of pjax
    $.pjax.defaults.timeout = false;

    // add new attributes to the product
    $('#add_new_attribute_form').submit(function() {
        
        // checking attribute
        if ( ! $('#attribute_dropdownlist').val())
        {
            alert('Please select one attribute');
            return false;
        }

        $.ajax({
            url : $(this).attr('data-url'),
            type : "POST",
            data : $(this).serialize(),
            error : function (xhr){
                alert('Error : ' + xhr.responseText);
            }
        }).done(function (data){
            $.pjax.reload({container: '#product-attribute-list'});
            $('#add_new_attributes_modal').modal('hide');
        });

        return false;
    });

    var product_attribute_id;
    // edit button clicked
    $('.product_attributes').on('click', '.product-attribute-update', function () {
        // getting the clicked row values
        var row = $(this).parents('tr');
        product_attribute_id = row.attr('data-key');
        var product_attribute_price = row.find('td:nth-child(3)').text();

        // set the value based on clicked row
        $('#edit_product_attribute_form_id').val(product_attribute_id);
        $('#edit_product_attribute_form_price').val(parseInt(product_attribute_price));
    });

    // edit product's attributes 
    var edit_product_attribute_url = $('#edit_product_attribute_form').attr('data-url');
    $('#edit_product_attribute_form').submit(function() {
        $.ajax({
            url : edit_product_attribute_url + product_attribute_id,
            type : "POST",
            data : $(this).serialize(),
            error : function (xhr){
                alert('Error : ' + xhr.responseText);
            }
        }).done(function (data){
            $.pjax.reload({container: '#product-attribute-list'});
            $('#edit_product_attributes_modal').modal('hide');
        });

        return false;
    });
});