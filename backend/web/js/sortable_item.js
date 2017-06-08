$(document).ready(function () {
    
    // cache the original position
    var sortable_cache = $('#sortable-table tbody').html();

    // POST featured order data
    $('#position_order_btn').click(function () {
        $(this).attr({'disabled' : 'disabled'}).text('Saving...');
        var sorted_ids = $('#sortable-table tbody').sortable('serialize');

        $.ajax({
            data: sorted_ids,
            type: 'POST',
            url: $(this).attr('data-url'),
            success: (function (e){
                $('#position_order_btn')
                    .switchClass('btn-primary', 'btn-default', 200)
                    .text('Saved!')
                    .removeAttr('disabled')
                    .delay(500)
                    .queue(function (d){
                        $(this).switchClass('btn-default', 'btn-primary', 200).text('Save');
                        $(this).dequeue();
                    });

                // Change the cache's content
                sortable_cache = $('#sortable-table tbody').html();
            }),
            error: (function (e) {
                alert('Error while saving the position : ' + e.responseText);
                $('#position_order_btn').removeAttr('disabled').text('Save');
            })

        });
    });

    // Reset the order to the previous position
    $('#reset_btn').click(function () {
        $("#sortable-table tbody").html(sortable_cache).sortable('refresh');
    });

    // Sort the items by Label, ASC
    $('#sort_label_asc_btn').click(function (){
        sortByLabel(true);
    });

    // Sort the items by Label, DESC
    $('#sort_label_desc_btn').click(function (){
        sortByLabel(false);
    });

    function sortByLabel(asc)
    {
        var sortableLists = $('#sortable-table tbody');
        $(sortableLists).each(function(index, element) {

            var listitems = $('tr', element);

            listitems.sort(function(a, b) {
                var tA = $(a).children('td:nth-child(2)').text().toUpperCase(); // get the label
                var tB = $(b).children('td:nth-child(2)').text().toUpperCase(); // get next label
                
                if (asc)
                    return tA.localeCompare(tB);
                else 
                    return tA.localeCompare(tB) * -1;
            });

            $(element).append(listitems);
        });
    }

})