            <script>
                $('body').on('click', '#send-message-btn', function() {
                    sendMessage();
                    return false;
                });

                const MESSAGES_URL = location.protocol + "//" + location.host + '/dashboard/messages/{{ !empty($other) ? $other->id : (!empty($listing) ? $listing->owner->id : '') }}';

                function sendMessage() {
                    console.log('Sending message...');

                    if ($('#message-input-text').val() != "") {
                        $.ajax({
                            url: MESSAGES_URL,
                            type: 'POST',
                            data: {
                                _token: CSRF_TOKEN,
                                message: $('#message-input-text').val()
                            },
                            dataType: 'JSON',
                            success: function (response) {
                                if (response.status == 201) {
                                    if (window.location.pathname.split('/')[1] == 'dashboard') {
                                        $('#message-container').append(
                                            '<div class="message message-sent">\n' +
                                            '    <h3>You:</h3>\n' +
                                            '    <p>' + response.message + '</p>\n' +
                                            '</div>\n'
                                        )
                                    }
                                    $('#message-input-text').val('');
                                    console.log('Message sent successfully:\n' + response.message);
                                    FB.AppEvents.logEvent('SENT_MESSAGE');
                                    if (window.location.pathname.split('/')[1] == 'listings')
                                        window.location = MESSAGES_URL;
                                } else if (response.status == 402) {
                                    window.location = '{{ route('login') }}';
                                } else {
                                    console.error('An error occurred while sending the message.');
                                    console.error('Error ' + response.status);
                                    console.error(response.responseText);
                                }
                            },
                            error: function (e) {
                                console.error('An error occurred while sending the message.');
                                console.log('Refreshing page...');
                                setTimeout(function () {
                                    // location.reload(true);
                                }, 1000);
                            }
                        });
                    }
                }
            </script>