jQuery(document).ready(function($) {
    $('#isermons_update_button').on('click', function(e) {
        e.preventDefault();

        var progressBar = $('#isermons_progress_bar');
        var messageBox = $('#isermons_message_box');
        progressBar.val(0);
        progressBar.show();
        messageBox.hide();

        var batchSize = 30;
        var totalPosts;
        var processed = 0;

        function updateProgress() {
            $.ajax({
                type: 'POST',
                url: isermons_ajax_object.ajax_url,
                data: {
                    action: 'isermons_update_date_format',
                    nonce: isermons_ajax_object.nonce,
                    batch_size: batchSize,
                    offset: processed
                },
                success: function(response) {
                    if (response.success) {
                        processed += response.data.processed;
                        totalPosts = response.data.total;

                        var progress = (processed / totalPosts) * 100;
                        progressBar.val(progress);

                        if (processed < totalPosts) {
                            updateProgress();
                        } else {
                            messageBox.html('<p style="color: green;">Date format updated successfully.</p>');
                            messageBox.show();
							progressBar.hide();
                        }
                    } else {
                        messageBox.html('<p style="color: red;">Error: ' + response.data.message + '</p>');
                        messageBox.show();
                    }
                }
            });
        }

        updateProgress();
    });
});
