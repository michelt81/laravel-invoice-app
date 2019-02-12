function updateTotal() {
    var total = numeral($("#subtotal").html()).value() || 0;
    $('.tax-rate-total').each(function(rowIndex) {
        total += numeral($(this).html()).value() || 0;
    });
    $("#total").html(numeral(total).format());
}

function updateSubTotal() {
    var subTotal = numeral(0);
    $('.invoice-item').each(function(){
        subTotal.add(
            numeral($(".item_total", this).val()).value() || 0
        );
    });
    $("#subtotal").html(subTotal.format());
}

function updateRowTotal(thisItem) {
    var qty = parseInt($(".quantity", thisItem).val()) || 0;
    var price = numeral($(".unit_price", thisItem).val()).value() || 0;

    var itemTotal = numeral(qty * price);

    $(".item_total", thisItem).val(itemTotal.format());

    updateSubTotal();
    updateTaxTotals();
}

function updateTaxTotals() {

    for (i in taxRateTotals) {
        taxRateTotals[i] = 0;
    }

    $('select.tax_rate').each(function() {
        var thisItem = $(this).parents(".invoice-item");
        var itemTotal = $(".item_total", thisItem).val();
        itemTotal = numeral(itemTotal).value() || 0;
        taxRateTotals[$(this).val()] += $(this).val() / 100 * itemTotal;
    });

    $(".tax-rate-total").each(function() {
        $(this).html(
            numeral(taxRateTotals[$(this).data("rate")]).format()
        );
    });

    updateTotal();
}
/**
 * Update input numbers after adding or removing order entries
 */
function updateInputs() {
    $('.invoice-item').each(function(rowIndex){
        /// find each input with a name attribute inside each row
        $(this).find(':input[name]').each(function(){
            var name = $(this).attr('name');
            name = name.replace(/\[[0-9]+\]/g, '['+rowIndex+']');
            $(this).attr('name', name );

            var id = $(this).attr('id');
            if (id) {
                id = id.replace(/\-[0-9]+/g, '-' + rowIndex);
                $(this).attr('id', id);
            }

        });

        // update labels
        $(this).find("label[for]").each(function(){
            var forName = $(this).attr('for');
            forName = forName.replace(/\-[0-9]+/g, '-'+rowIndex);
            $(this).attr('for',forName);
        });
    });
}

$( document ).ready(function() {

    $(".add-item").click(function() {
        var thisItem = $(this).parents(".invoice-item");
        var newItem = thisItem.clone(true, true);
        newItem.find(':input').not("input[type=checkbox]").val('');
        newItem.insertAfter(thisItem);
        updateInputs();
        $('html, body').animate({
            scrollTop: thisItem.offset().top
        }, 500);
    });
    $(".remove-item").click(function() {
        if ($(".invoice-item").length != 1) {
            $(this).parents(".invoice-item").remove();
            updateInputs();
            updateSubTotal();
            updateTaxTotals();
            updateTotal();
        }
    });
    // new item when input entered.
    // listener on input field
    $(".invoice-item:last-child :input").focus(function() {
        console.log('test');
    });

    $(".quantity, .unit_price").on('input', function() {
        // update this row's total
        var thisItem = $(this).parents(".invoice-item");
        updateRowTotal(thisItem);
    });

    // $('.unit_price').priceFormat();


    $( ".product_name" ).autocomplete({
        minLength: 0,
        source: products,
        focus: function( event, ui ) {
            $( event.target ).val( ui.item.name );
            return false;
        },
        select: function( event, ui ) {
            var productInput = event.target;
            var invoiceItem = $(productInput).parents(".invoice-item")
            $(productInput).next(".product_id").val( ui.item.id );
            $(productInput).val( ui.item.name );
            $(".unit_price", invoiceItem).val( ui.item.price );

            return false;
        },
        change: function(event, ui) {

            var productInput = event.target;
            var invoiceItem = $(productInput).parents(".invoice-item");

            if (ui.item == null) {
                $(productInput).next(".product_id").val( "" );
            } else {
                $(productInput).next(".product_id").val( ui.item.id );
            }
        }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<div>" + item.name + "<br>" + item.desc + "</div>" )
            .appendTo( ul );
    };

    $(".choose_product").change(function() {
        var invoiceItem = $(this).parents(".invoice-item");
        if (this.checked) {
            $(".product_id", invoiceItem).show();
            $(".product_name", invoiceItem).removeAttr('required').hide();
            $(".save_new_div", invoiceItem).hide();
            $('.save_new', invoiceItem).prop('checked', false);
        } else {
            $(".product_id", invoiceItem).hide();
            $(".product_name", invoiceItem).prop('required',true).show();
            $(".save_new_div", invoiceItem).show();
        }
    });

    $(".product_id").change(function () {
        var invoiceItem = $(this).parents(".invoice-item");
        var selected = $("option:selected", this);

        if ($(".choose_product", invoiceItem).is(":checked")) {
            $(".unit_price", invoiceItem).val(
                numeral().set(selected.data("price")).format()
            );
            $(".tax_rate", invoiceItem).val(
                selected.data("tax_rate")
            );
        }
        updateRowTotal(invoiceItem);
    });

    $(".tax_rate").change(function() {
        updateTaxTotals();
    });

    // trigger change upon reload
    $(".choose_product, .product_id").change();

    $("#customer_id").change(function() {
        if ($(this).val()) {
            $("#email_save_action").show();
        } else {
            $("#email_save_action").hide();
        }
    }).change();

    $("#email_save_action").click(function() {
        var emailPresent = $("#customer_id :selected").data("email");
        if (!emailPresent) {
            bootbox.prompt({
                title: "{{ trans('messages.no_customer_email') }}",
                inputType: "email",
                callback: function (result) {
                    $.ajax({
                        type: "POST",
                        data: {_method: 'PUT', _token: $("#email_save_action").data("token"), 'email': result },
                        url: '/customer/' + $("#customer_id").val(),
                        success: function() {
                            $("#invoice").submit();
                        }
                    })
                }
            });
            return false;
        } else {
            // submit as normal
        }
    });

});