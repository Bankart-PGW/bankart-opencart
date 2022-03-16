var selectedCard;
var $paymentForm = $('#bankart_gateway_form');
var $paymentFormSubmitButton = $("#bankart_gateway_form_submit");
var $paymentFormCardTypeInput = $('#bankart_gateway_card_type');
var $paymentFormTokenInput = $('#bankart_gateway_token');
var $paymentTypeButtons = $('#bankart-type-buttons');

$('button[data-card-type]').on('click', function () {
    $('#bankart-payment-form').attr('style', 'display: none !important');
    $('#bankart-instalments-form').attr('style', 'display: none !important');
    selectCard($(this).data('cardType'));
});

$paymentFormSubmitButton.on('click', function () {
    $('.bankart-input-wrapper').removeClass('bankart-error');
    $paymentFormSubmitButton.prop("disabled", true);
    submitForm();
});

var selectCard = function (cardType) {
    var card = cards[cardType];
    if (!card) {
        return;
    }
    selectedCard = card;

    /**
     * show selection
     */
    $('button[data-card-type]').removeClass('bankart-btn-success');
    $('button[data-card-type="' + card.type + '"]').addClass('bankart-btn-success');

    /**
     * set form data
     */
    $paymentFormCardTypeInput.val(selectedCard.type);

    /**
     * seamless integration
     */
    if (card.integrationKey) {
        bankartGatewaySeamless.init(card.integrationKey);
        setTimeout(function () {
            $('#bankart-payment-form').show();
        }, 300);
    }

    /**
     * populate and show instalments, first delete pre-exesting values
     */
    if (card.instalments > 1) {
        $('#bankart-instalments').children().not(':first').remove();
        for (let inst_num = 2; inst_num <= card.instalments; inst_num++) {
            $('#bankart-instalments').append($('<option>', { 
                value: inst_num,
                text : inst_num 
            }));
        };
        setTimeout(function () {
            $('#bankart-instalments-form').show();
        }, 100);
    }

    /**
     * redirect integration
     */
    $paymentFormSubmitButton.prop("disabled", false);
};

var submitForm = function (e) {
    /**
     * seamless integration
     */
    if (selectedCard.integrationKey) {
        $('.bankart-error-text').attr('style', 'display: none !important');
        bankartGatewaySeamless.submit(
            function (token) {
                $paymentFormTokenInput.val(token);
                $paymentForm.submit();
            },
            function (errors) {
                errors.forEach(function (error) {
                    $('#bankart-error-'  + error.attribute + '-' + error.key.substring(7)).show();
                    switch(error.attribute) {
                        case 'cvv':
                        case 'card_holder':
                            $('#bankart-' + error.attribute).addClass('bankart-error');
                            $('#bankart-error-'  + error.attribute + '-' + error.key.substring(7)).show();
                            break;
                        case 'number':
                            $('#bankart-card-' + error.attribute).addClass('bankart-error');
                            $('#bankart-error-'  + error.attribute + '-' + error.key.substring(7)).show();
                            break;
                        case 'month':
                        case 'year':
                            $('#bankart-expiry-' + error.attribute).addClass('bankart-error');
                            $('#bankart-error-year-' + error.key.substring(7)).show();
                            if (error.key == 'errors.expired') {
                                $('#bankart-expiry-month').addClass('bankart-error');
                            }
                            break;
                    }

                });
                $paymentFormSubmitButton.prop("disabled", false);
            });
        return;
    }
    /**
     * redirect integration
     */
    $paymentForm.submit();
};

/**
 * seamless
 */
var bankartGatewaySeamless = function () {
    var payment;
    var $seamlessForm = $('#bankart-payment-form');
    var $seamlessCardHolderInput = $('#bankart-card_holder', $seamlessForm);
    var $seamlessExpiryMonthInput = $('#bankart-expiry-month', $seamlessForm);
    var $seamlessExpiryYearInput = $('#bankart-expiry-year', $seamlessForm);
    var $seamlessCardNumberInput = $('#bankart-card-number', $seamlessForm);
    var $seamlessCvvInput = $('#bankart-cvv', $seamlessForm);

    var init = function (integrationKey) {

        var style = {
            'background' : 'transparent',
            'height': '100%',
            'border': 'none',
            'border-radius': '3px',
            'font-family': '"Arial", sans-serif',
            'font-weight': 'bold',
            'color': '#555',
            'padding': '6px',
            'padding-left': '8px',
            'padding-right': '8px',                
            'display': 'inline',
            'line-height': '1.42857143',
            'font-size': '14px',
            'box-sizing': 'border-box',
            'margin': '0',
            'outline' : '0',
            'box-shadow': 'inset 0 0px 0px rgba(0, 0, 0, .0)', 
        };
        payment = new PaymentJs("1.2");
        payment.init(integrationKey, $seamlessCardNumberInput.prop('id'), $seamlessCvvInput.prop('id'), function (payment) {
            payment.setNumberStyle(style);
            payment.setCvvStyle(style);
            // Focus events
            payment.numberOn('focus', function() {
                $seamlessCardNumberInput.addClass('bankart-focus');
            });
            payment.cvvOn('focus', function() {
                $seamlessCvvInput.addClass('bankart-focus');
            });
            // Blur events
            payment.numberOn('blur', function() {
                $seamlessCardNumberInput.removeClass('bankart-focus');
            });
            payment.cvvOn('blur', function() {
                $seamlessCvvInput.removeClass('bankart-focus');
            });   
        });
    };

    var submit = function (success, error) {
        payment.tokenize(
            {
                card_holder: $seamlessCardHolderInput.val(),
                month: $seamlessExpiryMonthInput.val(),
                year: $seamlessExpiryYearInput.val(),
            },
            function (token, cardData) {
                success.call(this, token);
            },
            function (errors) {
                error.call(this, errors);
                $paymentFormSubmitButton.prop("disabled", false);
            }
        );
    };

    return {
        init: init,
        submit: submit,
    };
}();

if ($('button[data-card-type]').length === 1) {
    setTimeout(function () {
        selectCard($('button[data-card-type]').first().data('cardType'));
    }, 300);
}