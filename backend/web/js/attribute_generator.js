$(document).ready(function () {
    // show input of each attribute selected to be generated
    var attributes;

    $('.generate_button').hide();
    $('.info').show();

    $('.btn_add_attribute').click(function () {
        attributes = $('#product_attribute_dropdown').val();

        if ( ! attributes )
        {
            alert('Please select one or more attributes by Ctrl-click / Cmd-click on the labels');
            return false;
        }

        $('.generate_button').show();
        $('.info').hide();

        // remove all attributes first
        $('.form-group.attribute').remove();
        
        // Add form-group to the form, for each attribute
        for (var i = 0; i < attributes.length; i++)
        {
            var label = $('#product_attribute_dropdown > option[value=' + attributes[i] + ']').text();
            
            $("<div class='form-group attribute'><label class='control-label'>" + label + " <\/label><input type='number' step='0.01' name='attributes[" +
                attributes[i] +
                "]' value='0.00' class='form-control' placeholder='Impact on Price'\/><\/div>").insertBefore('.generate_button');

        }
    });
    
});