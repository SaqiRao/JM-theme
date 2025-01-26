jQuery(document).ready(function ($) {
    let selectedRating = 0;

    // Handle star click
    $('.star').on('click', function () {
        selectedRating = $(this).data('value');
        $('.star').each(function () {
            $(this).css('color', $(this).data('value') <= selectedRating ? 'gold' : 'gray');
        });
    });

    // Submit rating
    $('#submit-rating').on('click', function () {
        if (selectedRating === 0) {
            alert('Please select a rating!');
            return;
        }
        const postId = $(this).data('post-id'); 
        $.ajax({
            url: ajaxurl.ajax_url, 
            type: 'POST',
            data: {
                action: 'save_post_rating',
                post_id: postId,
                rating: selectedRating,
            },
            success: function (response) {
                $('#rating-result').html(response.data.message); 
            },
            error: function () {
                alert('Something went wrong. Please try again.');
            },
        });
    });

   
    $('#load-more').on('click', function () {
        let button = $(this);
        let page = button.data('page');
        let ajaxUrl = button.data('url');

        $.ajax({
            url: ajaxurl.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_posts',
                page: page
            },
            beforeSend: function () {
                button.text('Loading...');
            },
            success: function (data) {
                if (data) {
                    $('#post-container').append(data);
                    button.text('Load More');
                    button.data('page', page + 1);
                } else {
                    button.text('No More Posts');
                    button.prop('disabled', true);
                }
            }
        });
    });
      
});
