$(document).ready(function () {

    // --------------- FIX NAV-TABS ---------------
    $('.nav-tabs > .operator-menu').on('click', function() {
        $('.operator-menu.active').removeClass('active');
        $(this).toggleClass('active');
    });

    // --------------- SHOW PRODUCT SEARCH MODAL ---------------
    $('.row-add_product').click(function () {
        $('#add_product_modal').modal();
    });
    // --------------- INPUT PRODUCT TO THE CART FROM SEARCH MODAL ---------------
    $('#form_add_product').submit(function () {
        var product_id = $('select[name=product_name]').val();
        var cart_item = $('.all_products .cart-item[data-product-id=' + product_id + ']');

        addProductToCart(cart_item);
        
        $('#add_product_modal').modal('hide');
        return false;
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
        var cart_item = $(this).closest('.cart-item');
        setItemDataQuantity(cart_item, parseFloat($(this).val(), 10).toFixed(2));

        calculateSubTotal(cart_item);
    });

    // --------- RECALCULATE SUBTOTAL WHEN DISCOUNT IS CHANGED -----------
    $('.cart').on('change keyup paste propertychange', '.cell-discount-input', function () {
        calculateSubTotal($(this).parent().parent());
    });

    // --------- REMOVE ITEM FROM CART -----------
    $('.remove_from_cart_btn').on('click', function () {
        var item_index = $('#form_edit_item_options .cart_item_index').val();
        var cart_item = $('.cart tbody').find('.cart-item').eq(item_index);

        $('#edit_item_options_modal').modal('hide');

        deleteItemFromCart(cart_item);
    });

    // --------- ADD SELECTED PRODUCT TO CART -----------
    $('.product').on('click', function () {
        var product_id = $(this).attr('data-id');
        var cart_item = $('.all_products .cart-item[data-product-id=' + product_id + ']');
        addProductToCart(cart_item);
    });

    // --------- BEAUTIFY DISCOUNT VALUE -----------
    $('.cell-discount-input').on('change', function () {
        var value = $(this).val();

        value = parseFloat(value);
        $(this).val(value);

    });

    // --------- SHOW EDIT DISCOUNT MODAL -----------
    $('.summary .discount').on('click', function () {
        $('#edit_discount_modal').modal();
    });

    // ----------- DETERMINE DISCOUNT TYPE ------------
    $('.discount_type_btn_group button').click(function () {
        $(this).addClass('active').siblings().removeClass('active');

        if ($(this).is('.discount_by_value'))
        {
            $('#edit_discount_input').removeAttr('max').attr('step', 0.01);
            $('.discount_percentage_symbol').addClass('hide');
            $('#discount').attr('data-type', 'by_value');
        }
        else {
            $('#edit_discount_input').attr({'max' : 99, 'step' : 1}).val(10);
            $('.discount_percentage_symbol').removeClass('hide');
            $('#discount').attr('data-type', 'by_percentage');
        }
    });

    // --------- CALCULATE DISCOUNT -----------
    $('#form_edit_discount').submit(function () {
        if ($('.discount_by_value').is('.active'))
        {
            $('#discount').attr('data-type', 'by_value');
        }
        else {
            $('#discount').attr('data-type', 'by_percentage');
        }

        calculateTotal();
        
        $('#edit_discount_modal').modal('hide');
        
        return false;
    });
    $('.no_discount_btn').click(function () {
        var current_discount = parseFloat($('#discount').attr('data-value'));

        if (current_discount !== 0) {
            $('#edit_discount_input').val(0);
            $('#discount').attr('data-value', 0).text(formatCurrency(0));
            calculateTotal();
        }

        $('#edit_discount_modal').modal('hide');
    });

    // --------- SHOW EDIT TAX MODAL -----------
    $('.summary .tax').on('click', function () {
        $('#edit_tax_modal').modal();
    });

    // ----------- DETERMINE TAX TYPE ------------
    $('.tax_type_btn_group button').click(function () {
        $(this).addClass('active').siblings().removeClass('active');

        if ($(this).is('.tax_by_value'))
        {
            $('.tax_percentage_symbol').addClass('hide');
            $('#tax').attr('data-type', 'by_value');
        }
        else {
            $('#edit_tax_input').val(10);
            $('.tax_percentage_symbol').removeClass('hide');
            $('#tax').attr('data-type', 'by_percentage');
        }
    });

    // --------- CALCULATE TAX -----------
    $('#form_edit_tax').submit(function () {
        if ($('.tax_by_value').is('.active'))
        {
            $('#tax').attr('data-type', 'by_value');
        }
        else {
            $('#tax').attr('data-type', 'by_percentage');
        }

        calculateTotal();
        
        $('#edit_tax_modal').modal('hide');
        
        return false;
    });
    $('.no_tax_btn').click(function () {
        var current_tax = parseFloat($('#tax').attr('data-value'));

        if (current_tax !== 0) {
            $('#edit_tax_input').val(0);
            $('#tax').attr('data-value', 0).text(formatCurrency(0));
            calculateTotal();
        }

        $('#edit_tax_modal').modal('hide');
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

        // TODO ........
        // check if the combination of attributes is already in the cart. Merge the quantity

        calculateSubTotal(cart_item);

        $('#edit_item_options_modal').modal('hide');
        return false;
    });

    function addProductToCart(cart_item)
    {
        
        var product_id = cart_item.attr('data-product-id');
        var existed_product = $('.cart tr[data-product-id=' + product_id + ']');
        var product_attribute_id;

        // check if the product has attributes
        if (cart_item.attr('data-product-attribute-id') > 0)
        {
            var attributes = JSON.parse(cart_item.attr('data-product-attributes'));
            var first_attribute = attributes[0];

            existed_product = $('.cart tr[data-product-id=' + product_id + '][data-product-attribute-id=' + first_attribute['id'] + ']');
        }

        if (existed_product.length)
        {
            increaseQuantity(existed_product.find('.product-quantity'));
            return;
        }

        var unit_price = cart_item.attr('data-product-price');

        cart_item.find('.cell-unit-price').text(formatCurrency(unit_price));
        cart_item.find('.cell-subtotal').text(formatCurrency(unit_price));

        cart_item.clone(true).insertBefore('.row-add_product').hide().fadeIn(350);
        calculateTotal();
    }

    function increaseQuantity(input_quantity)
    {
        var new_quantity = parseInt(input_quantity.val(), 10) + 1;
        input_quantity.val(new_quantity);
        var cart_item = input_quantity.closest('.cart-item');
        setItemDataQuantity(cart_item, new_quantity);

        calculateSubTotal(cart_item);
    }

    function decreaseQuantity(input_quantity)
    {
        var oldVal = input_quantity.val();
        var cart_item = input_quantity.closest('.cart-item');
        var new_quantity;
        if (oldVal >= 2)
            new_quantity = parseInt(oldVal, 10) - 1;
        else if (oldVal > 1)
            new_quantity = 1;
        else {
            deleteItemFromCart(cart_item);
        }
        
        input_quantity.val(new_quantity);
        setItemDataQuantity(cart_item, new_quantity);

        calculateSubTotal(cart_item);
    }

    function setItemDataQuantity(cart_item, quantity)
    {
        cart_item.attr('data-quantity', quantity);
    }

    function deleteItemFromCart(cart_item)
    {
        cart_item.fadeOut('fast', function () {$(this).remove(); calculateTotal();});
    }

    function calculateDiscount()
    {
        var discount = 0.00;

        if ($('#discount').attr('data-type') == 'by_value')
        {
            discount = parseFloat($('#edit_discount_input').val());
        }
        else {
            discount = calculateDiscountByPercentage();
        }

        var subtotal = parseFloat($('#subtotal').attr('data-value'));
        if (discount > subtotal)
        {
            discount = subtotal;
        }

        $('#discount').attr('data-value', discount).text(formatCurrency(discount));
    }

    function calculateDiscountByPercentage()
    {
        var discount = 0.00;

        discount = parseFloat($('#edit_discount_input').val()) / 100 * $('#subtotal').attr('data-value');
        discount.toFixed(2);

        return discount;
    }

    function calculateTax()
    {
        var tax = 0.00;

        if ($('#tax').attr('data-type') == 'by_value')
        {
            tax = parseFloat($('#edit_tax_input').val());
        }
        else {
            tax = calculateTaxByPercentage();
        }

        $('#tax').attr('data-value', tax).text(formatCurrency(tax));
    }

    function calculateTaxByPercentage()
    {
        var tax = 0.00;

        tax = parseFloat($('#edit_tax_input').val()) / 100 * ($('#subtotal').attr('data-value') - $('#discount').attr('data-value'));
        tax.toFixed(2);

        return tax;
    }

    // calculate subtotal for each product
    function calculateSubTotal(cart_item)
    {
        var unit_price = 1*cart_item.data('product-price');
        if (cart_item.data('product-attribute-id') > 0)
        {
            unit_price+= 1*cart_item.attr('data-product-attribute-price');
        }

        cart_item.attr('data-product-price', unit_price).find('.cell-unit-price').text(formatCurrency(unit_price));

        var discount = +Math.abs(cart_item.find('.cell-discount-input').val()) || 0;
        var quantity = Math.abs(cart_item.find('.cell-quantity input').val()) || 0;

        var subtotal = unit_price * quantity - discount;
        subtotal = subtotal < 0 ? 0 : subtotal;
        cart_item.find('.cell-subtotal').attr('data-value', subtotal).text(formatCurrency(subtotal));

        calculateTotal();
    }

    // calculate total from all subtotal, tax, and discount
    function calculateTotal()
    {
        var subtotal = 0;
        var discount;
        var tax;
        var total;

        $('.cart .cart-item').each(function (index, element) {
            subtotal += parseFloat($(this).find('.cell-subtotal').attr('data-value'));
        });

        $('#subtotal').attr('data-value', subtotal).text(formatCurrency(subtotal));

        calculateDiscount();
        discount = $('#discount').attr('data-value');

        calculateTax();
        tax = $('#tax').attr('data-value');

        total = parseFloat(subtotal) + parseFloat(tax) - discount;
        $('#total').attr('data-value', total).text(formatCurrency(total));

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
