$(document).ready(function () {

    // --------------- FIX NAV-TABS ---------------
    $('.nav-tabs > .operator-menu').on('click', function() {
        $('.operator-menu.active').removeClass('active');
        $(this).toggleClass('active');
    });

    // --------- INCREASE/DECREASE QUANTITY ----------
    $('.cart').on('click', '.increase-quantity', function () {
        increaseQuantity($(this).prev());
    });
    $('.cart').on('click', '.decrease-quantity', function () {
        decreaseQuantity($(this).next());
    });

    // ------------ RECALCULATE SUBTOTAL WHEN PRODUCT QUANTITY IS CHANGED --------------
    $('.cart').on('change keyup paste propertychange', 'input.product-quantity', function () {
        calculateSubTotal($(this).parent().parent().parent());
    });

    // --------- RECALCULATE SUBTOTAL WHEN DISCOUNT IS CHANGED -----------
    $('.cart').on('change keyup paste propertychange', '.cell-discount-input', function () {
        calculateSubTotal($(this).parent().parent());
    });


    // --------- ADD SELECTED PRODUCT TO CART -----------
    $('.product').on('click', function () {
        addProductToCart($(this));
    });

    // ------------  EDIT PRODUCT ON CART --------------
    $('.cart').on('click', '.cart-item .cell-description', function () {

        var cart_item = $(this).parent();

        if (cart_item.attr('data-product-attribute-id') > 0)
        {
            var attributes = JSON.parse(cart_item.attr('data-product-attributes'));
            var list = '';
            // showing all product attributes from JSON data
            for (var i = 0; i < attributes.length; i ++)
            {
                list += '<option value=' + attributes[i].id + ' data-price=' + attributes[i].price +'>' + attributes[i].label + '</option>';
            }

            $('#edit_item_modal_attributes').html(list);
            // set selected value based on previous product-attribute-id
            $('#edit_item_modal_attributes').val(cart_item.attr('data-product-attribute-id'));
            $('.edit_item_model_attribute').show();
        }
        else {
            $('.edit_item_model_attribute').hide();
        }
        
        $('#edit_item_modal_note').val(cart_item.attr('data-note'));
        $('#form_edit_item_options .cart_item_index').val(cart_item.index());

        $('#edit_item_options_modal').modal();
    });

    // ------------- SAVE THE NOTE AND ATTRIBUTE-ID TO THE .CART-ITEM -----------------
    $('#form_edit_item_options').submit(function () {

        // saving the note
        var item_index = $('#form_edit_item_options .cart_item_index').val();
        var cart_item = $('.cart tbody').find('.cart-item').eq(item_index);
        cart_item.attr('data-note', $('#edit_item_modal_note').val());
        
        // saving the attribute-id
        if (cart_item.attr('data-product-attribute-id') > 0)
        {
            cart_item.attr('data-product-attribute-id', $('#edit_item_modal_attributes').val());
            cart_item.attr('data-product-attribute-price', $('#edit_item_modal_attributes option:selected').attr('data-price'));
            cart_item.find('small').text($('#edit_item_modal_attributes option:selected').text());
        }

        calculateSubTotal(cart_item);

        $('#edit_item_options_modal').modal('hide');
        return false;
    });

    function addProductToCart(product)
    {
        // checking if there is selected product in cart
        var existed_product = $('.cart tr[data-product-id=' + product.data('id') + ']');
        var product_label = product.data('label');
        var product_attribute_id;
        var product_attributes = [];

        var product_attribute_price = 0;

        if (product.has('.attributes').length)
        {
            var first_attribute = product.find('.attributes .attribute:first');
            product_attribute_id = first_attribute.data('id');
            product_label = product_label + ' <small>' + first_attribute.data('label') + '</small>';
            product_attribute_price = first_attribute.data('price');

            existed_product = $('.cart tr[data-product-id=' + product.data('id') + '][data-product-attribute-id=' + product_attribute_id + ']');

            // include all attributes data, so the operator can select it later
            product.find('.attributes .attribute').each(function (index, element) {
                product_attributes[index] = {};
                product_attributes[index].id = $(element).data('id');
                product_attributes[index].label = $(element).data('label');
                product_attributes[index].price = $(element).data('price');
            });
        }

        if (existed_product.length)
        {
            increaseQuantity(existed_product.find('.product-quantity'));
            return;
        }

        var row = $('.product_template_for_cart tr');
        row.attr('data-product-id', product.data('id'));
        row.attr('data-product-price', product.data('price'));

        if (product_attributes.length)
        {
            row.attr('data-product-attributes', JSON.stringify(product_attributes));
            row.attr('data-product-attribute-id', product_attribute_id);
            row.attr('data-product-attribute-price', product_attribute_price);
        }
        else {
            // clear previous data
            row.attr('data-product-attributes', '');
            row.attr('data-product-attribute-id', '');
            row.attr('data-product-attribute-price', '');
        }

        row.find('.cell-description').html(product_label);
        row.find('.cell-unit-price').text(formatCurrency(1*product.data('price') + 1*product_attribute_price));
        row.find('.cell-subtotal').text(formatCurrency(product.data('price')));
        
        row.clone(true).insertBefore('.row-add_product').hide().fadeIn(350);
    }

    function increaseQuantity(input_quantity)
    {
        var oldVal = input_quantity.val();
        input_quantity.val(parseInt(oldVal) + 1);

        calculateSubTotal(input_quantity.parent().parent().parent());
    }

    function decreaseQuantity(input_quantity)
    {
        var oldVal = input_quantity.val();
        if (oldVal >= 2)
            input_quantity.val(parseInt(oldVal) - 1);
        else if (oldVal > 1)
            input_quantity.val(1);
        else 
            input_quantity.closest('tr').remove();

        calculateSubTotal(input_quantity.parent().parent().parent());
    }

    // calculate subtotal for each product
    function calculateSubTotal(cart_item)
    {
        var unit_price = 1*cart_item.data('product-price');
        if (cart_item.data('product-attribute-id') > 0)
        {
            unit_price+= 1*cart_item.attr('data-product-attribute-price');
        }

        cart_item.find('.cell-unit-price').text(formatCurrency(unit_price));

        var discount = +Math.abs(cart_item.find('.cell-discount-input').val()) || 0;
        var quantity = Math.abs(cart_item.find('.cell-quantity input').val()) || 0;

        console.log(discount);

        var subtotal = unit_price * quantity - discount;
        cart_item.find('.cell-subtotal').text(formatCurrency(subtotal));

        calculateTotal();
    }

    function calculateTotal()
    {
        console.log('Calculate Total...');
    }
});

function startTime(){
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    h = h < 10 ? '0' + h : h;
    m = m < 10 ? '0' + m : m;
    document.getElementById('current_time').innerHTML = h + ':' + m;

    t = setTimeout(function () {startTime();}, 1000);
}

startTime();

function formatCurrency(number)
{
    return Number(parseFloat(number).toFixed(2)).toLocaleString('en-US');
}
