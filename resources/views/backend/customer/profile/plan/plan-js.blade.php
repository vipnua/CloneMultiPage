<script>
    function showInfoPlan() {
        let select = $('select[name="select-plan"]').find(':selected').data('data')
        // $('select[name="select-plan"]')
        if (select) {
            $('#plan-description-preview').children().remove();
            $('.preview-show').show()
            $('.preview-hide').hide()
            $('#plan-name-preview').text(select.name)
            $('#plan-interval-preview').text(select.interval)
            $('#plan-profile-preview').text(select.profile)
            $('#plan-profile-share-preview').text(select.profile_share)
            $('#plan-price-preview').text(select.price)
            $('#plan-price-vn-preview').text(select.price_vn)
            $('#plan-description-preview').append($.parseHTML(select.describe))

            let country = '{{$user->country ?? 'US'}}';

            switch (country) {
                case 'VN':
                    $('input[name = amount]').val(select.price_vn);
                    $('select[name = currency]').val('VND');
                    break;

                case 'SG':
                    $('input[name = amount]').val(select.price);
                    $('select[name = currency]').val('SGD');
                    break;

                case 'MY':
                    $('input[name = amount]').val(select.price);
                    $('select[name = currency]').val('MYR');
                    break;

                default:
                    $('input[name = amount]').val(select.price);
                    $('select[name = currency]').val('USD');
                    break;
            }


        } else {
            $('.preview-hide').show()
            $('.preview-show').hide()
        }
    }

    $(document).on('change', 'select[name="select-plan"]', function () {
        showInfoPlan()
    })
    $(document).on('change', 'select[name="currency"]', function () {
        // console.log($(this).val())
        let select = $('select[name="select-plan"]').find(':selected').data('data')
        switch ($(this).val()) {
            case 'VND':
                $('input[name = amount]').val(select?.price_vn);
                break;

            default:
                $('input[name = amount]').val(select?.price);
                break;
        }
    })
    $(document).on('click', '#btn-auto-generate', function (e) {
        e.preventDefault();
        $('input[name=transaction_id]').val(generateTransection({{auth()->user()->id}}))
    })

    function generateTransection(auth_id) {
        return `AUTO_${auth_id}_${Date.now()}`
    }

    showInfoPlan()
</script>
