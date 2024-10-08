<div class="row ">
    @php
        $total_number_of_item_per_page = $cards->perPage();
        $total_number_of_items = ($cards->total() > 0) ? $cards->total() : 0;
        $total_number_of_pages = $total_number_of_items / $total_number_of_item_per_page;
        $reminder = $total_number_of_items % $total_number_of_item_per_page;
        if ($reminder > 0) {
            $total_number_of_pages += 1;
        }
        $current_page = $cards->currentPage();
        $previous_page = $cards->currentPage() - 1;
        if($current_page == $cards->lastPage()){
            $show_end = $total_number_of_items;
        }else{
            $show_end = $total_number_of_item_per_page * $current_page;
        }

        $show_start = 0;
        if($total_number_of_items > 0){
            $show_start = ($total_number_of_item_per_page * $previous_page) + 1;
        }
    @endphp
    <div class="col-12">
        <div class="box_header d-flex flex-wrap align-items-center justify-content-between">
            <h5 class="font_16 f_w_500 mr_10 mb-0">{{__('defaultTheme.showing')}} @if($show_start == $show_end) {{getNumberTranslate($show_end)}} @else {{getNumberTranslate($show_start)}} - {{getNumberTranslate($show_end)}} @endif {{getNumberTranslate($total_number_of_items)}} {{__('common.results')}}</h5>
            <div class="box_header_right ">
                <div class="short_select d-flex align-items-center gap_10 flex-wrap">
                    <div class="prduct_showing_style">
                        <ul class="nav align-items-center" id="myTab" role="tablist">
                            <li class="nav-item lh-1">
                                <a class="nav-link view-product active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                    <img src="{{ showImage('frontend/amazy/img/svg/grid_view.svg') }}" alt="Grid View" title="Grid View">
                                </a>
                            </li>
                            <li class="nav-item lh-1">
                                <a class="nav-link view-product" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                    <img src="{{ showImage('frontend/amazy/img/svg/list_view.svg') }}" alt="List View" title="List View">
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="shorting_box">
                        <select name="paginate_by" class="amaz_select getFilterUpdateByIndex" id="paginate_by">
                            <option value="9" @if (isset($paginate) && $paginate == "9") selected @endif>{{__('common.show')}} {{getNumberTranslate(9)}} {{__('common.item’s')}}</option>
                            <option value="12" @if (isset($paginate) && $paginate == "12") selected @endif>{{__('common.show')}} {{getNumberTranslate(12)}} {{__('common.item’s')}}</option>
                            <option value="16" @if (isset($paginate) && $paginate == "16") selected @endif>{{__('common.show')}} {{getNumberTranslate(16)}} {{__('common.item’s')}}</option>
                            <option value="25" @if (isset($paginate) && $paginate == "25") selected @endif>{{__('common.show')}} {{getNumberTranslate(25)}} {{__('common.item’s')}}</option>
                            <option value="30" @if (isset($paginate) && $paginate == "30") selected @endif>{{__('common.show')}} {{getNumberTranslate(30)}} {{__('common.item’s')}}</option>
                        </select>
                    </div>
                    <div class="shorting_box">
                        <select class="amaz_select getFilterUpdateByIndex" name="sort_by" id="product_short_list">
                            <option value="new" @if (isset($sort_by) && $sort_by == "new") selected @endif>{{ __('common.new') }}</option>
                            <option value="old" @if (isset($sort_by) && $sort_by == "old") selected @endif>{{ __('common.old') }}</option>
                            <option value="alpha_asc" @if (isset($sort_by) && $sort_by == "alpha_asc") selected @endif>{{ __('defaultTheme.name_a_to_z') }}</option>
                            <option value="alpha_desc" @if (isset($sort_by) && $sort_by == "alpha_desc") selected @endif>{{ __('defaultTheme.name_z_to_a') }}</option>
                            <option value="low_to_high" @if (isset($sort_by) && $sort_by == "low_to_high") selected @endif>{{ __('defaultTheme.price_low_to_high') }}</option>
                            <option value="high_to_low" @if (isset($sort_by) && $sort_by == "high_to_low") selected @endif>{{ __('defaultTheme.price_high_to_low') }}</option>
                        </select>
                    </div>
                    <div class="flex-fill text-end">
                        <div class="category_toggler d-inline-block d-lg-none  gj-cursor-pointer">
                            <svg  width="19.5" height="13" viewBox="0 0 19.5 13">
                                <g id="filter-icon" transform="translate(28)">
                                    <rect id="Rectangle_1" data-name="Rectangle 1" width="19.5" height="2" rx="1" transform="translate(-28)" fill="#fd4949"/>
                                    <rect id="Rectangle_2" data-name="Rectangle 2" width="15.5" height="2" rx="1" transform="translate(-26 5.5)" fill="#fd4949"/>
                                    <rect id="Rectangle_3" data-name="Rectangle 3" width="5" height="2" rx="1" transform="translate(-20.75 11)" fill="#fd4949"/>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-content mb_30" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <!-- content  -->
        <div class="row custom_rowProduct">
            @if(count($cards)>0)
                @foreach($cards as $key => $card)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                        <div class="product_widget5 mb_30 style5">
                            <div class="product_thumb_upper">
                                @if(is_null($card->type))
                                    @php
                                        $thumbnail = showImage($card->thumbnail_image);
                                        $price_qty = getGiftcardwithDiscountPrice(@$card);
                                        $showData = [
                                            'name' => @$card->name,
                                            'url' => route('frontend.gift-card.show',$card->sku),
                                            'price' => $price_qty,
                                            'thumbnail' => $thumbnail
                                        ];
                                    @endphp
                                    <a href="{{route('frontend.gift-card.show',$card->sku)}}" class="thumb">
                                        <img src="{{$thumbnail}}" alt="{{@$card->name}}" title="{{@$card->name}}">
                                    </a>
                                @else
                                    @php
                                        $cardMultiInfo = \Modules\GiftCard\Entities\GiftCard::giftCardSellInfo($card->id);
                                        $thumbnail = showImage($card->thumbnail_image);
                                        $price_qty = getGiftcardMultiwithDiscountPrice($cardMultiInfo);
                                        $showData = [
                                            'name' => @$card->name,
                                            'url' => route('frontend.gift-card.show',$card->sku),
                                            'price' => $price_qty,
                                            'thumbnail' => $thumbnail
                                        ];
                                    @endphp
                                    <a href="{{route('frontend.gift-card.show.multiple',$card->sku)}}" class="thumb">
                                        <img src="{{$thumbnail}}" alt="{{@$card->name}}" title="{{@$card->name}}">
                                    </a>
                                @endif

                                <div class="product_action">
                                    <a href="javascript:void(0)" class="add_to_wishlist_from_search {{@$card->IsWishlist == 1?'is_wishlist':''}}" id="wishlistbtn_{{$card->id}}" data-product_id="{{$card->id}}" data-type="gift_card" data-seller_id="1"> <i class="far fa-heart"></i> </a>
                                    @if(is_null($card->type))
                                        <a class="add_to_cart_gift_thumnail" data-gift-card-id="{{ $card->id }}" data-seller="{{ App\Models\User::where('role_id', 1)->first()->id }}"
                                            data-base-price="@if($card->hasDiscount()) {{selling_price($card->sell_price, $card->discount_type, $card->discount)}} @else {{$card->sell_price}} @endif"
                                            data-prod_info="{{ json_encode($showData) }}" href="javascript:void(0)" > <i class="ti-bag"></i>
                                        </a>
                                    @else
                                        <a class="add_to_cart_gift_thumnail" data-gift-card-type="multiple" data-gift-card-id="{{ $card->id }}" data-seller="{{ App\Models\User::where('role_id', 1)->first()->id }}"
                                            data-base-price="@if($cardMultiInfo->hasDiscount()) {{selling_price($cardMultiInfo->gift_selling_price, $cardMultiInfo->gift_discount_type, $cardMultiInfo->gift_discount_amount)}} @else {{$cardMultiInfo->gift_selling_price}} @endif"
                                            data-prod_info="{{ json_encode($showData) }}" href="javascript:void(0)" > <i class="ti-bag"></i>
                                        </a>
                                    @endif
                                </div>
                                @if(is_null($card->type))
                                    @if($card->hasDiscount())
                                        @if($card->discount > 0)
                                            <span class="badge_1">
                                                @if(@$card->discount_type ==0)
                                                    -{{getNumberTranslate(@$card->discount)}}%
                                                @else
                                                    -{{single_price(@$card->discount)}}
                                                @endif
                                            </span>
                                        @endif
                                    @endif
                                @else
                                    @if($cardMultiInfo->hasDiscount())
                                        @if($cardMultiInfo->gift_discount_amount > 0)
                                            <span class="badge_1">
                                                @if(@$cardMultiInfo->gift_discount_type ==0)
                                                    -{{getNumberTranslate(@$cardMultiInfo->gift_discount_amount)}}%
                                                @else
                                                    -{{single_price(@$cardMultiInfo->gift_discount_amount)}}
                                                @endif
                                            </span>
                                        @endif
                                    @endif
                                @endif
                            </div>
                            <div class="product_star mx-auto">
                                @php
                                    $reviews = $card->reviews->where('status',1)->pluck('rating');
                                    if(count($reviews)>0){
                                        $value = 0;
                                        $rating = 0;
                                        foreach($reviews as $review){
                                            $value += $review;
                                        }
                                        $rating = $value/count($reviews);
                                        $total_review = count($reviews);
                                    }else{
                                        $rating = 0;
                                        $total_review = 0;
                                    }
                                @endphp
                                <x-rating :rating="$rating"/>
                            </div>
                            <div class="product__meta text-center">
                                @if(is_null($card->type))
                                    <a href="{{route('frontend.gift-card.show',$card->sku)}}">
                                        <h4>{{textLimit($card->name,28)}}</h4>
                                    </a>
                                @else
                                    <a href="{{route('frontend.gift-card.show.multiple',$card->sku)}}">
                                        <h4>{{textLimit($card->name,28)}}</h4>
                                    </a>
                                @endif

                                <div class="product_price d-flex align-items-center justify-content-between flex-wrap">
                                    @if(is_null($card->type))
                                        <a class="amaz_primary_btn add_cart add_to_cart add_to_cart_gift_thumnail" data-gift-card-id="{{ $card->id }}"
                                            data-seller="1" data-base-price="@if($card->hasDiscount()) {{selling_price($card->sell_price, $card->discount_type, $card->discount)}} @else {{$card->sell_price}} @endif"
                                            data-prod_info = "{{json_encode($showData)}}"
                                            href="javascript:void(0)"
                                            >
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" >
                                                <path d="M0.464844 1.14286C0.464844 0.78782 0.751726 0.5 1.10561 0.5H1.58256C2.39459 0.5 2.88079 1.04771 3.15883 1.55685C3.34414 1.89623 3.47821 2.28987 3.58307 2.64624C3.61147 2.64401 3.64024 2.64286 3.66934 2.64286H14.3464C15.0557 2.64286 15.5679 3.32379 15.3734 4.00811L13.8119 9.50163C13.5241 10.5142 12.6019 11.2124 11.5525 11.2124H6.47073C5.41263 11.2124 4.48508 10.5028 4.20505 9.47909L3.55532 7.10386L2.48004 3.4621L2.47829 3.45572C2.34527 2.96901 2.22042 2.51433 2.03491 2.1746C1.85475 1.84469 1.71115 1.78571 1.58256 1.78571H1.10561C0.751726 1.78571 0.464844 1.49789 0.464844 1.14286ZM4.79882 6.79169L5.44087 9.1388C5.56816 9.60414 5.98978 9.92669 6.47073 9.92669H11.5525C12.0295 9.92669 12.4487 9.60929 12.5795 9.14909L14.0634 3.92857H3.95529L4.78706 6.74583C4.79157 6.76109 4.79548 6.77634 4.79882 6.79169ZM7.72683 13.7857C7.72683 14.7325 6.96184 15.5 6.01812 15.5C5.07443 15.5 4.30942 14.7325 4.30942 13.7857C4.30942 12.8389 5.07443 12.0714 6.01812 12.0714C6.96184 12.0714 7.72683 12.8389 7.72683 13.7857ZM6.4453 13.7857C6.4453 13.5491 6.25405 13.3571 6.01812 13.3571C5.7822 13.3571 5.59095 13.5491 5.59095 13.7857C5.59095 14.0224 5.7822 14.2143 6.01812 14.2143C6.25405 14.2143 6.4453 14.0224 6.4453 13.7857ZM13.7073 13.7857C13.7073 14.7325 12.9423 15.5 11.9986 15.5C11.0549 15.5 10.2899 14.7325 10.2899 13.7857C10.2899 12.8389 11.0549 12.0714 11.9986 12.0714C12.9423 12.0714 13.7073 12.8389 13.7073 13.7857ZM12.4258 13.7857C12.4258 13.5491 12.2345 13.3571 11.9986 13.3571C11.7627 13.3571 11.5714 13.5491 11.5714 13.7857C11.5714 14.0224 11.7627 14.2143 11.9986 14.2143C12.2345 14.2143 12.4258 14.0224 12.4258 13.7857Z" fill="currentColor"/>
                                            </svg>
                                            {{__('defaultTheme.add_to_cart')}}
                                        </a>
                                    @else
                                        <a class="amaz_primary_btn add_cart add_to_cart add_to_cart_gift_thumnail" data-gift-card-type="multiple" data-gift-card-id="{{ $card->id }}"
                                            data-seller="1"
                                            data-base-price="@if($cardMultiInfo->hasDiscount()) {{selling_price($cardMultiInfo->gift_selling_price, $cardMultiInfo->gift_discount_type, $cardMultiInfo->gift_discount_amount)}} @else {{$cardMultiInfo->gift_selling_price}} @endif"
                                            data-prod_info = "{{json_encode($showData)}}"
                                            href="javascript:void(0)"
                                            >
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" >
                                                <path d="M0.464844 1.14286C0.464844 0.78782 0.751726 0.5 1.10561 0.5H1.58256C2.39459 0.5 2.88079 1.04771 3.15883 1.55685C3.34414 1.89623 3.47821 2.28987 3.58307 2.64624C3.61147 2.64401 3.64024 2.64286 3.66934 2.64286H14.3464C15.0557 2.64286 15.5679 3.32379 15.3734 4.00811L13.8119 9.50163C13.5241 10.5142 12.6019 11.2124 11.5525 11.2124H6.47073C5.41263 11.2124 4.48508 10.5028 4.20505 9.47909L3.55532 7.10386L2.48004 3.4621L2.47829 3.45572C2.34527 2.96901 2.22042 2.51433 2.03491 2.1746C1.85475 1.84469 1.71115 1.78571 1.58256 1.78571H1.10561C0.751726 1.78571 0.464844 1.49789 0.464844 1.14286ZM4.79882 6.79169L5.44087 9.1388C5.56816 9.60414 5.98978 9.92669 6.47073 9.92669H11.5525C12.0295 9.92669 12.4487 9.60929 12.5795 9.14909L14.0634 3.92857H3.95529L4.78706 6.74583C4.79157 6.76109 4.79548 6.77634 4.79882 6.79169ZM7.72683 13.7857C7.72683 14.7325 6.96184 15.5 6.01812 15.5C5.07443 15.5 4.30942 14.7325 4.30942 13.7857C4.30942 12.8389 5.07443 12.0714 6.01812 12.0714C6.96184 12.0714 7.72683 12.8389 7.72683 13.7857ZM6.4453 13.7857C6.4453 13.5491 6.25405 13.3571 6.01812 13.3571C5.7822 13.3571 5.59095 13.5491 5.59095 13.7857C5.59095 14.0224 5.7822 14.2143 6.01812 14.2143C6.25405 14.2143 6.4453 14.0224 6.4453 13.7857ZM13.7073 13.7857C13.7073 14.7325 12.9423 15.5 11.9986 15.5C11.0549 15.5 10.2899 14.7325 10.2899 13.7857C10.2899 12.8389 11.0549 12.0714 11.9986 12.0714C12.9423 12.0714 13.7073 12.8389 13.7073 13.7857ZM12.4258 13.7857C12.4258 13.5491 12.2345 13.3571 11.9986 13.3571C11.7627 13.3571 11.5714 13.5491 11.5714 13.7857C11.5714 14.0224 11.7627 14.2143 11.9986 14.2143C12.2345 14.2143 12.4258 14.0224 12.4258 13.7857Z" fill="currentColor"/>
                                            </svg>
                                            {{__('defaultTheme.add_to_cart')}}
                                        </a>
                                    @endif
                                    <p>
                                        <span>
                                            @if(getGiftcardwithoutDiscountPrice(@$card) != single_price(0))
                                                {{getGiftcardwithoutDiscountPrice(@$card)}}
                                            @endif
                                        </span>
                                        @if(is_null($card->type))
                                            {{getGiftcardwithDiscountPrice($card)}}
                                        @else
                                            @if($cardMultiInfo->hasDiscount())
                                                {{single_price(selling_price($cardMultiInfo->gift_selling_price, $cardMultiInfo->gift_discount_type, $cardMultiInfo->gift_discount_amount))}}
                                            @else
                                                {{single_price($cardMultiInfo->gift_selling_price)}}
                                            @endif
                                        @endif
                                    </p>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @else

            @endif
        </div>
        <!--/ content  -->
    </div>
    <div class="tab-pane fade " id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <!-- content  -->
        <div class="row">
            @if(count($cards)>0)
                @foreach($cards as $key => $card)
                    <div class="col-xl-12">
                        <div class="product_widget5 mb_30 list_style_product">
                            <div class="product_thumb_upper m-0">
                                @php
                                    $thumbnail = showImage($card->thumbnail_image);
                                    $price_qty = getGiftcardwithDiscountPrice(@$card);
                                    $showData = [];
                                    $showData = [
                                        'name' => @$card->name,
                                        'url' => route('frontend.gift-card.show',$card->sku),
                                        'price' => $price_qty,
                                        'thumbnail' => $thumbnail
                                    ];
                                @endphp
                                <a href="{{route('frontend.gift-card.show',$card->sku)}}" class="thumb">
                                    <img src="{{$thumbnail}}" alt="{{@$card->name}}" title="{{@$card->name}}">
                                </a>
                                <div class="product_action">
                                    <a href="" class="add_to_wishlist_from_search {{@$card->IsWishlist == 1?'is_wishlist':''}}" id="wishlistbtn_{{$card->id}}" data-product_id="{{$card->id}}" data-type="gift_card" data-seller_id="{{ App\Models\User::where('role_id', 1)->first()->id }}"> <i class="far fa-heart"></i> </a>
                                    <a class="add_to_cart_gift_thumnail" data-gift-card-id="{{ $card->id }}" data-seller="{{ App\Models\User::where('role_id', 1)->first()->id }}"
                                        data-base-price="@if($card->hasDiscount()) {{selling_price($card->sell_price, $card->discount_type, $card->discount)}} @else {{$card->sell_price}} @endif"
                                        data-prod_info = "{{json_encode($showData)}}"
                                        href="javascript:void(0)"
                                        > <i class="ti-bag"></i> </a>
                                </div>
                                @if($card->hasDiscount())
                                    @if($card->discount > 0)
                                        <span class="badge_1">
                                            @if(@$card->discount_type ==0)
                                                -{{getNumberTranslate(@$card->discount)}}%
                                            @else
                                                -{{single_price(@$card->discount)}}
                                            @endif
                                        </span>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <!--/ content  -->
    </div>
</div>
<input type="hidden" name="filterCatCol" class="filterCatCol" value="0">
@if($cards->lastPage() > 1)
    <x-pagination-component :items="$cards" type=""/>
@endif
