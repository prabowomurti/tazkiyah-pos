$(document).ready(function () {
    // delete product from the order
    $('.order_items').on('click', '.order-items-delete', function () {
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
            $('#total_price').val(parseInt(data));
        });

    });

    // override timeout value of pjax
    $.pjax.defaults.timeout = false;

    // add new item/product to the order
    $('#add_new_product_form').submit(function() {
        
        // checking product
        if ( ! $('#product_dropdownlist').val())
        {
            alert('Please select a product');
            return false;
        }

        if ( $('#product_quantity').val() < 1)
        {
            alert('Minimum quantity is 1');
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
            $.pjax.reload({container: '#order-items-list'});
            $('#add_new_product_modal').modal('hide');

            $('#total_price').val(data);
        });

        return false;
    });

    var order_item_id;
    // edit button clicked
    $('.order_items').on('click', '.order-items-update', function () {
        // getting the clicked row values
        var row = $(this).parents('tr');
        order_item_id = row.attr('data-key');
        var order_item_quantity = row.find('td:nth-child(4)').text();

        // set the value based on clicked row
        $('#edit_order_item_form_id').val(order_item_id);
        $('#edit_order_item_form_quantity').val(parseInt(order_item_quantity));
    });

    // edit order items
    var edit_order_item_url = $('#edit_order_item_form').attr('data-url');
    $('#edit_order_item_form').submit(function() {
        $.ajax({
            url : edit_order_item_url + order_item_id,
            type : "POST",
            data : $(this).serialize(),
            error : function (xhr){
                alert('Error : ' + xhr.responseText);
            }
        }).done(function (data){
            $.pjax.reload({container: '#order-items-list'});
            $('#edit_order_items_modal').modal('hide');
            $('#total_price').val(data);
        });

        return false;
    });
});