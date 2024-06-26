$(document).ready(function() {
    var totalImages = 4;
    var titles = ['Simple', 'Rapide', 'Sur', 'Efficace'];

    $('.item').each(function(index) {
        $(this).css('background-image', 'url(img/' + (index + 1) + '.jpg)');
        $(this).find('.title').text(titles[index]);
        $(this).data('currentIndex', index);
    });

    $('#arrow-right').click(function() {
        $(this).siblings('.item').each(function() {
            var currentIndex = $(this).data('currentIndex');
            currentIndex++;
            if (currentIndex >= totalImages) {
                currentIndex = 0;
            }
            $(this).css('background-image', 'url(img/' + (currentIndex + 1) + '.jpg)');
            $(this).find('.title').text(titles[currentIndex]);
            $(this).data('currentIndex', currentIndex);
        });
    });

    $('#arrow-left').click(function() {
        $(this).siblings('.item').each(function() {
            var currentIndex = $(this).data('currentIndex');
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = totalImages - 1;
            }
            $(this).css('background-image', 'url(img/' + (currentIndex + 1) + '.jpg)');
            $(this).find('.title').text(titles[currentIndex]);
            $(this).data('currentIndex', currentIndex);
        });
    });
});
