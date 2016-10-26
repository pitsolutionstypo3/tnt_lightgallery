var contentId ='';
$(document).ready(function() {
    $('.dynamic').click(function(e) {
        contentId = $(this).attr("id");
        contentId = contentId.split('-');
        $(this).lightGallery({
            dynamic: true,
            html: true,
            mobileSrc: false,
            dynamicEl: contentArray[contentId[1]] 
        });
    })
});
