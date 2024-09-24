$(document).ready(function()
{
    window.promptTriggered = 0;

    $('.featureLinksScroll.juice').toggleClass('active');
    setInterval(
        function(){
            $('.featureLinksScroll.juice').toggleClass('active');
        }, 25000);

    $('input.nsMasked').each(function(i, el)
    {
        $.extend($.mask.definitions, $(el).data('definitions'));

        $(el).mask($(el).data('mask'), {placeholder:$(el).data('placeholder')});
        $(el).parents('div.form-group').children('label').append(' <small class="text-info">'+$(el).data('mask')+'</small>');
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    var calculateDelivery = function($el)
    {
        if($el.val() == 0)
        {
            $('.deliveryInfo').hide();
        }
        else
        {
            $('.deliveryInfo').show();
        }
    };

    $('#delivery').change(function(el)
    {
        calculateDelivery($(this));
    });

    calculateDelivery($('#delivery'));

    $('#newsletter_resubscribe, #modal_newsletter_resubscribe, #checkout_newsletter_resubscribe').click(function(event)
    {
        event.preventDefault();
        var $button = $(this);

        $.ajax($button.data('href'), {
            complete: function()
            {
                $.ajax('/newsletter/new', {
                    method: 'POST',
                    data: {
                        type: $button.data('type'),
                        wasTriggered: window.promptTriggered
                    }
                });
            },
            success: function()
            {
                $button.html('Subscribed!').prop('disabled', true);
            }
        });
    });

    $('#newsletter_subscribe, #modal_newsletter_subscribe').click(function(event)
    {
        var $button = $(this);
        $.ajax('/newsletter/new', {
            method: 'POST',
            data: {
                type: $button.data('type'),
                wasTriggered: window.promptTriggered
            }
        });
    });

    $('html').on('mouseleave', function()
    {
        $('#signup').modal('show');
    });

    $('#signup').on('show.bs.modal', function()
    {
        window.promptTriggered = true;
        $.ajax('/newsletter/disable');
    }).on('hidden.bs.modal', function()
    {
        $('#signup').remove();
    })
});
