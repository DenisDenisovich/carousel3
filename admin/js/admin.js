jQuery(function($){

    $('#carousel3-sortable').sortable({
        items: '> tr',
        handle: '.drag-handle',
        axis: 'y',
        placeholder: 'sortable-placeholder',
        update: function(){

            let order = [];

            $('#carousel3-sortable tr').each(function(index){
                order.push({
                    id: $(this).data('id'),
                    menu_order: index
                });
            });

            $.post(carousel3TableSort.ajaxUrl, {
                action: 'carousel3_update_order',
                nonce: carousel3TableSort.nonce,
                order: order
            });

        }
    });

});

console.log('Admin JS loaded');