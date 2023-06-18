$('#start_at_picker').on('keydown', function (e) {
    e.preventDefault();
    $(this).val('');
    $('#start_at').val('');
});
$('#end_at_picker').on('keydown', function (e) {
    e.preventDefault();
    $(this).val('');
    $('#end_at').val('');
});

let minimum_purchase = $('.minimum_purchase');
minimum_purchase.attr('autocomplete', 'off');
$(document).on('keyup', '.minimum_purchase', function() {
    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }
    let text = number_format($(this).val()) + ' تومان';
    $(this).next('.form-text').text(text);
});
minimum_purchase.trigger('keyup');

let products_commission_percent = $('.products_commission_percent');
products_commission_percent.attr('autocomplete', 'off');
$(document).on('keyup', '.products_commission_percent', function() {
    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }

    let text;
    if ($(this).val() > 100) {
        $(this).next('.form-text').remove();
        $(this).after('<small class="form-text text-danger amount-helper"></small>');
        text = 'مقدار نامعتبر!'
    } else {
        text = $(this).val() + '%';
    }

    $(this).next('.form-text').text(text);
});
products_commission_percent.trigger('keyup');

let discounted_products_commission_percent = $('.discounted_products_commission_percent');
discounted_products_commission_percent.attr('autocomplete', 'off');
$(document).on('keyup', '.discounted_products_commission_percent', function() {
    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }

    let text;
    if ($(this).val() > 100) {
        $(this).next('.form-text').remove();
        $(this).after('<small class="form-text text-danger amount-helper"></small>');
        text = 'مقدار نامعتبر!'
    } else {
        text = $(this).val() + '%';
    }
    $(this).next('.form-text').text(text);
});
discounted_products_commission_percent.trigger('keyup');

let discounted_orders_commission_percent = $('.discounted_orders_commission_percent');
discounted_orders_commission_percent.attr('autocomplete', 'off');
$(document).on('keyup', '.discounted_orders_commission_percent', function() {
    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }

    let text;
    if ($(this).val() > 100) {
        $(this).next('.form-text').remove();
        $(this).after('<small class="form-text text-danger amount-helper"></small>');
        text = 'مقدار نامعتبر!'
    } else {
        text = $(this).val() + '%';
    }
    $(this).next('.form-text').text(text);
});
discounted_orders_commission_percent.trigger('keyup');

function addCampaignTariff() {
    let template = $('#tariffs-template').clone();

    let tariff = $('#campaign-tariffs-area').append(template.html()).find('.single-tariff:last');
    let count = ++tariffsCount;

    tariff.find('input[name="minimum_purchase"]').attr('name', `tariffs[${count}][minimum_purchase]`);
    tariff.find('input[name="products_commission_percent"]').attr('name', `tariffs[${count}][products_commission_percent]`);
    tariff.find('input[name="discounted_products_commission_percent"]').attr('name', `tariffs[${count}][discounted_products_commission_percent]`);
    tariff.find('input[name="discounted_orders_commission_percent"]').attr('name', `tariffs[${count}][discounted_orders_commission_percent]`);

    tariffsSortable();

    setTimeout(() => {
        tariff.removeClass('animated fadeIn');
    }, 700);
}

$(document).on('click', '#add-campaign-tariff', function () {
    addCampaignTariff();
});

if (tariffsCount === 0) {
    addCampaignTariff();
}

function tariffsSortable() {
    $('#campaign-tariffs-area').sortable({
        opacity: .75,
        start: function (e, ui) {
            ui.placeholder.css({
                'height': ui.item.outerHeight(),
                'margin-bottom': ui.item.css('margin-bottom'),
            });
        },
        helper: function (e, tr) {
            let $originals = tr.children();
            let $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        }
    });
}

tariffsSortable();

$(document).on('click', '.remove-tariff', function () {
    let tariff = $(this).closest('.single-tariff');

    tariff.addClass('animated fadeOut');

    setTimeout(() => {
        tariff.remove();
    }, 500);
});
