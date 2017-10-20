    <script>
        $('body').on('click', '#save-listing-button', function() {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/listings/{{ $listing->id }}/save',
                type: 'POST',
                data: {_token: CSRF_TOKEN},
                dataType: 'JSON',
                success: function (response) {
                    if (response.status == 200) {
                        $('#save-listing-button').html('Unsave<i class="fa fa-heart fa-pad-5l"></i>');
                        $('#save-listing-button').attr('id', 'unsave-listing-button');
                        console.log(response.message);
                    } else if (response.status == 402) {
                        window.location = '{{ route('login') }}';
                    } else {
                        console.error('An error occurred while saving this listing.');
                        console.error('Error ' + response.status);
                        console.error(response.responseText);
                    }
                },
                error: function(e) {
                    console.error('An error occurred while saving this listing.');
                    console.log('Refreshing page...');
                    setTimeout(function() { location.reload(true); }, 1000);
                }
            });
            return false;
        });

        $('body').on('click', '#unsave-listing-button', function() {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/listings/{{ $listing->id }}/unsave',
                type: 'POST',
                data: {_token: CSRF_TOKEN},
                dataType: 'JSON',
                success: function (response) {
                    if (response.status == 200) {
                        $('#unsave-listing-button').html('Save<i class="fa fa-heart fa-pad-5l"></i>');
                        $('#unsave-listing-button').attr('id', 'save-listing-button');
                        console.log(response.message);
                    } else if (response.status == 402) {
                        window.location = '{{ route('login') }}';
                    } else {
                        console.error('An error occurred while unsaving this listing.');
                        console.error('Error ' + response.status);
                        console.error(response.responseText);
                    }
                },
                error: function(e) {
                    console.error('An error occurred while unsaving this listing.');
                    console.log('Refreshing page...');
                    setTimeout(function() { location.reload(true); }, 1000);
                }
            });
            return false;
        });
    </script>