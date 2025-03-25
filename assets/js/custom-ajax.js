jQuery(document).ready(function($) {
    $('form').on('submit', function(e) {
        e.preventDefault();
        var email = $('#tnp-1').val();
        if (validateEmail(email)) {
            var data = {
                action: 'create_newsletter_post',
                nonce: ajax_object.nonce,
                email: email
            };
            
            $.post(ajax_object.ajax_url, data, function(response) {
                showPopup(response.data.message, response.success ? 'success' : 'error');
                showresponsePopup();
                if(response.success) {
                    $('#tnp-1').val('');
                }
            });
        } else {
            showPopup('Please enter a valid email address.', 'error');
        }
    });

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\.,;:\s@"]+(.[^<>()\[\]\.,;:\s@"]+)*)|(".+"))@(([^<>()[\]\.,;:\s@"]+\.)+[^<>()[\]\.,;:\s@"]{2,})$/i;
        return re.test(String(email).toLowerCase());
    }

    function showPopup(message, type) {
        var popupContent = $('#popup-content');
        popupContent.text(message);
        if (type === 'success') {
            popupContent.css({'color': 'green', 'font-weight': 'bold'});
        } else {
            popupContent.css({'color': 'red', 'font-weight': 'bold'});
        }
        $('#popup-overlay, #popup-message').fadeIn();

        setTimeout(function() {
            $('#popup-overlay, #popup-message').fadeOut();
        }, 2000);
    }

    function showresponsePopup() {
        setTimeout(function() {
            $('.close-btn').click();
        }, 4000);
    }


});
