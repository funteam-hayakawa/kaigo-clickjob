$(function(){
        $(".questions dt").on("click", function() {
                $(this).next().slideToggle();
                var selector = $(this);
                if (!selector.hasClass('on')) {
                        selector.addClass('on');
                } else {
                        selector.removeClass('on');
                }
        });
});

