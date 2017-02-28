$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(res) {
                var json = $.parseJSON(res);
                $('#submissionResponse').removeAttr('class');
                if (parseInt(json.code) === 1) {
                    $('#submissionResponse').addClass('success');
                    $('input').val('');
                } else {
                    $('#submissionResponse').addClass('error');
                }
                $('#submissionResponse').text(json.message);
                $('#submissionResponse').show().delay(3000).fadeOut();
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
});
