$(document).ready(function() {
    $('.cartAdd').click(function(event)
    {
        var $addLnk = $(this);
        $.ajax($addLnk.attr('href'), {
            complete: function(data, status)
            {
                if(data.status == 410)
                {
                    alert('Sorry, the item you have attempted to add is no longer available.');
                    $addLnk.addClass('disabled');
                    $addLnk.html('<i class="fa fa-ban"></i> Unavailable');
                }
            },
            success: function(data)
            {
                $('.liveCartCount').html(data.count);
                $('.quickCart').fadeIn();
                $addLnk.html('<i class="fa fa-shopping-cart"></i> In Cart');
                $addLnk.addClass('disabled');
                var trackContent = {
                    content_name: $addLnk.data('productname'),
                    content_category: encodeURI($addLnk.data('productcategory')),
                    content_ids: [$addLnk.data('productid')],
                    value: $addLnk.data('productprice')
                };

                fbq('track', 'AddToCart', trackContent);
            }
        });
        return false;
    });

    $('.cartDelete').click(function(event)
    {
        var $delLnk = $(this);
        $.ajax($delLnk.attr('href'), {
            success: function(data)
            {
                $('.liveCartCount').html(data.count);
                $delLnk.closest('tr.cartProduct').fadeOut(
                    400,
                    function()
                    {
                        $(this).remove();
                        $('#cartTotal').html('$'+data.subtotal);
                        $('#cartGst').html('$'+data.gst);
                        $('#cartGrandTotal').html('$'+data.grandTotal);
                    }
                );
            }
        })
        return false;
    });

    $('.cartEmpty').click(function(event)
    {
        var $emLnk = $(this);
        $.ajax($emLnk.attr('href'), {
            success: function(data)
            {
                $('.liveCartCount').html(data.count);
                $emLnk.closest('table').find('tr.cartProduct').fadeOut(
                    400,
                    function()
                    {
                        $(this).remove();
                        $('#cartTotal').html('$'+data.subtotal);
                        $('#cartGst').html('$'+data.gst);
                        $('#cartGrandTotal').html('$'+data.grandTotal);
                        $('#checkoutLink').remove();
                    }
                );
            }
        });

        return false;
    });

    $('.updateQuantities').click(function(event)
    {
        var $qLnk = $(this);
        $.ajax($qLnk.attr('href'), {
            data: $('#cartUpdate').serializeArray(),
            success: function(data)
            {
                $('.liveCartCount').html(data.count);
                $('#cartTotal').html('$'+data.subtotal);
                $('#cartGst').html('$'+data.gst);
                $('#cartGrandTotal').html('$'+data.grandTotal);
            }
        });

        return false;
    });
});
