$(document).ready(function (){
    var selectedItems;
    
    $('#delete_selected_items_btn').click(function (){
        selectedItems = $('.grid-view').yiiGridView('getSelectedRows');
        
        if ( ! selectedItems.length)
        {
            alert('Please select at least one item to be deleted');
            return false;
        }

        if ( ! confirm('Are you sure to delete ' + selectedItems.length + ' items?')) return false;

        var multipledeleteUrl = $(this).attr('data-url');
        $.ajax({
            type: "POST",
            url: multipledeleteUrl,
            data: {selectedItems : selectedItems},
            success: (function (e){
                $.pjax.reload({container : '#w0'});
                selectedItems = [];
                
            }),
            error: (function (e) {
                alert("Can not delete selected items");
            })
        });
    });
});