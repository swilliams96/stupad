            <script>
                const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $('body').on('click', '#send-message-btn', function() {
                    sendMessage();
                    return false;
                });

                function sendMessage() {
                    if ($('#message-input-text').val() != "") {
                        $.ajax({
                            url: '/dashboard/messages/{{ $other->id }}',
                            type: 'POST',
                            data: {
                                _token: CSRF_TOKEN,
                                message: $('#message-input-text').val()
                            },
                            dataType: 'JSON',
                            success: function (response) {
                                if (response.status == 201) {
                                    // TODO: Insert newly sent chat message into the HTML
                                    $('#message-input-text').val('');
                                    console.log('Message sent successfully:\n' + response.message);
                                    FB.AppEvents.logEvent('SENT_MESSAGE');
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

                $(document).ready(function() {
                    $('#message-container').scrollTop($('#message-container')[0].scrollHeight - $('#message-container')[0].clientHeight);
                });
            </script>