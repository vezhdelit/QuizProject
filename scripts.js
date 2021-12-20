$(function(){

    $('.test-data').find('div:first').show();

    $('.pagination a').on('click', function(){
        if( $(this).attr('class') == 'nav-active' ) return false;

        var link = $(this).attr('href'); // посилання на текст вкладки для відображення
        var prevActive = $('.pagination > a.nav-active').attr('href'); // посилання на текст поки що активної вкладки

        $('.pagination > a.nav-active').removeClass('nav-active'); // удаляем клас активності у активної вкладки
        $(this).addClass('nav-active'); // добавляємо клас активності для даної вкладки

        // прячимо/показуємо питання
        $(prevActive).fadeOut(100, function(){
            $(link).fadeIn(100);
        });

        return false;
    });

    $('#btn').click(function(){
        var test = +$('#test-id').text();
        var res = {'test':test};

        $('.question').each(function(){
            var id = $(this).data('id');
            res[id] = $('input[name=question-' + id + ']:checked').val();
        });

        $.ajax({
            url: 'main-page.php',
            type: 'POST',
            data: res,
            success: function(html){
                $('.content').html(html);
            },
            error: function(){
                alert('Error!');
            }
        });
    });

});