jQuery(document).ready(function(){
$ = jQuery.noConflict();
 $x = jQuery('h1[class="post-title"]').text();
var divTriangle = $("<i/>").addClass("arrow");
 if ($x=='Home') {jQuery('.post-container').css("background-color", "transparent");
 jQuery('.content').css("background-color", "transparent")};
 jQuery('div[class="mobile-navigation"]').find('li').find('ul').hide();
jQuery('a[href="#pll_switcher"]').click(function(){$(this).next().hide().slideDown();});
jQuery('a[href="https://www.visorsourcing.com/competition/"]').append(divTriangle);
jQuery('a[href="https://www.visorsourcing.com/competition/"]').attr('href', '#competition_placeholder');
jQuery('a[href="https://www.visorsourcing.com/about-us/"]').attr('href', '#about_us_placeholder');
jQuery('a[href="https://www.visorsourcing.com/zh/%e9%97%9c%e6%96%bc%e6%88%91%e5%80%91/"]').attr('href','#about_us_placeholder');
jQuery('a[href="https://www.visorsourcing.com/zh/%e6%af%94%e8%b3%bd/"]').attr('href','#competition_placeholder');
jQuery('a[href="#competition_placeholder"]').click(function(){$(this).next().slideToggle();});
jQuery('a[href="#about_us_placeholder"]').click(function(){$(this).next().slideToggle();});
});