jQuery(document).ready(function($) {
    $('#custom-button').on('click', function() {
        // Obtém o nonce do objeto localizado
        var nonce = custom_ajax_object.nonce;

        // Adiciona um registro de data e hora no banco de dados
        $.ajax({
            url: custom_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_button_add_to_database',
                date_time: '',
                nonce: nonce
            },
            success: function(response) {
                console.log(response);
            }
        });
    });
});
