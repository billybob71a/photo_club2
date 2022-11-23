jQuery(document).ready(function(){
$ = jQuery.noConflict();
jQuery('div[class="mobile-navigation"]').find('li').find('ul').hide();
jQuery('a[href="#pll_switcher"]').click(function(){$(this).next().hide().slideDown();});
jQuery('li.item_welcome').css('color','green');
$(".post-container").on('click','#delete_button', function(){
    var isGood = confirm('Are you sure you want delete this photo?');
    var post_number = $(this).attr('name');
    if (isGood) {
        $.ajax({ url: 'http://63.142.254.125/wp-content/themes/hitchcock-child/delete_post.php',
            data: {delete_var: post_number},
            type: 'post',
            success: function(output) {
                location.reload();
            }});
    }
    else {
        location.reload();
    }
	}
);
}
);
