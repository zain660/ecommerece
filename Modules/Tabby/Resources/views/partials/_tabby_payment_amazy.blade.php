<div class="col-lg-12">

    <form id="contactForm" enctype="multipart/form-data" action="{{route('frontend.order_payment')}}" class="p-0" method="POST">
        @csrf
        <input type="hidden" name="method" value="Tabby">
        <input type="hidden" name="amount" value="{{ $total_amount - $coupon_am}}">
        <input type="hidden" name="purpose" value="order_payment">
        <button class="btn_1 d-none" id="tabby_btn" type="submit">{{ __('wallet.continue_to_pay') }}</button>
    </form>
</div>
