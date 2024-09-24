$(document).ready(function()
{
    $('a.admin-ajax').click(function(event)
    {
        event.preventDefault();

        $a = $(this);

        if(confirm($a.data('confirm')))
        {
            $.ajax($a.attr('href'), {
                'success': function()
                {
                    alert($a.data('success'));
                },
                'complete': function(data)
                {
                    if(data.status != 200)
                    {
                        alert($a.data('fail'));
                    }
                }
            })
        }
    });
});
