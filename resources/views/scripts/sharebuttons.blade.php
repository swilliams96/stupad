            <script>
                $('#share-button').on('click', function() {
                    return false;
                });

                $(document.body).on('click', '#share-button-messenger', function() {
                    FB.ui({
                        method: 'send',
                        link: 'https://www.stupad.co.uk/listings/{{ $listing->id }}',
                    });
                    return false;
                });

                $(document.body).on('click', '#share-button-facebook', function() {
                    FB.ui({
                        method: 'share',
                        mobile_iframe: true,
                        quote: 'Check out this student house I found on StuPad!',
                        href: 'https://www.stupad.co.uk/listings/{{ $listing->id }}',
                    });
                    return false;
                });
            </script>