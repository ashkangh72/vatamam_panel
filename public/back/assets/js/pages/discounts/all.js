$('#discount-type').on('change', function() {
    switch ($(this).val()) {
        case 'percent':
            {
                $('.amount').hide();
                $('.percent').show();
                break;
            }
        case 'amount':
            {
                $('.amount').show();
                $('.percent').hide();
                break;
            }
    }
});

$('#discount-type').trigger('change');

$('#users-include').select2({
    rtl: true,
    width: '100%',
});


//--------------- generate random code
$('#generate-new-code').on('click', function() {
    let code = randomString(6);
    $('#main-card input[name="code"]').val(code);
});
