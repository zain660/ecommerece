<div class="product_reviews_wrapper">
    <div class="product_reviews_wrapper_head d-flex align-items-center justify-content-between">
        <h4 class="font_20 f_w_700 m-0">{{__('review.customer_feedback')}}</h4>
    </div>
    <div class="course_cutomer_reviews">
        <div class="course_feedback">
            @php
                $all_ratings = $all_reviews->pluck('rating');
                if(count($all_ratings)>0){
                    $value = 0;
                    $rating = 0;
                    foreach($all_ratings as $review){
                        $value += $review;
                    }
                    $rating = $value/count($all_ratings);
                    $total_review = count($all_ratings);
                }else{
                    $rating = 0;
                    $total_review = 1;
                }
                $five_stars = ($all_reviews->where('rating',5)->count() * 100)/$total_review;
                $four_stars = ($all_reviews->where('rating',4)->count() * 100)/$total_review;
                $three_stars = ($all_reviews->where('rating',3)->count() * 100)/$total_review;
                $two_stars = ($all_reviews->where('rating',2)->count() * 100)/$total_review;
                $one_stars = ($all_reviews->where('rating',1)->count() * 100)/$total_review;


                $sumation_of_stars = $five_stars + $four_stars +  $three_stars + $two_stars + $one_stars;

                $five_star_fill = ($sumation_of_stars * $five_stars) / 100;
                $four_star_fill = ($sumation_of_stars * $four_stars) / 100;
                $three_star_fill = ($sumation_of_stars * $three_stars) / 100;
                $two_star_fill = ($sumation_of_stars * $two_stars) / 100;
                $one_star_fill = ($sumation_of_stars * $one_stars) / 100;
            @endphp
            <div class="course_feedback_left">
                <h2>{{round($rating,1)}}</h2>
                <div class="feedmak_stars">
                    <x-rating :rating="$rating"/>
                </div>
                <span>{{count($all_ratings)}} {{__('review.verified_ratings')}}</span>
            </div>
            <div class="feedbark_progressbar">
                <div class="single_progrssbar">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $five_star_fill }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="rating_percent d-flex align-items-center">
                        <div class="feedmak_stars d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span>{{round($five_stars,2)}}%</span>
                    </div>
                </div>
                <div class="single_progrssbar">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $four_star_fill }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="rating_percent d-flex align-items-center">
                        <div class="feedmak_stars d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span>{{round($four_stars,1)}}%</span>
                    </div>
                </div>
                <div class="single_progrssbar">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $three_star_fill }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="rating_percent d-flex align-items-center">
                        <div class="feedmak_stars d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span>{{round($three_stars,1)}}%</span>
                    </div>
                </div>
                <div class="single_progrssbar">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $two_star_fill }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="rating_percent d-flex align-items-center">
                        <div class="feedmak_stars d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span>{{round($two_stars,1)}}%</span>
                    </div>
                </div>
                <div class="single_progrssbar">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $one_star_fill }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="rating_percent d-flex align-items-center">
                        <div class="feedmak_stars d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span>{{round($one_stars,1)}}%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="customers_reviews">
            @if(count($reviews) > 0)
                @foreach(@$reviews as $key => $review)
                    <div class="single_reviews flex-column">
                        <div class="single_reviews">
                            <div class="thumb">
                                @if(@$review->customer->avatar != null)
                                    {{\Illuminate\Support\Str::limit(@$review->customer->first_name,1,$end='')}}{{\Illuminate\Support\Str::limit(@$review->customer->last_name,1,$end='')}}
                                @elseif($review->is_anonymous == 1)
                                    <img src="{{showImage('frontend/default/img/avatar.jpg')}}" alt="{{@$review->customer->first_name.' '.@$review->customer->last_name}}" title="{{@$review->customer->first_name.' '.@$review->customer->last_name}}">
                                @else
                                    <img src="{{showImage(@$review->customer->avatar)}}" alt="{{@$review->customer->first_name.' '.@$review->customer->last_name}}" title="{{@$review->customer->first_name.' '.@$review->customer->last_name}}">
                                @endif
                            </div>
                            <div class="review_content">
                                <div class="review_content_head d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="review_content_head_left">
                                        <h4 class="f_w_700 font_20" >{{$review->is_anonymous==1?'Unknown Name':@$review->customer->first_name.' '.@$review->customer->last_name}}</h4>
                                        <div class="rated_customer d-flex align-items-center">
                                            <div class="feedmak_stars">
                                                @php
                                                    $rating = $review->rating;
                                                @endphp
                                                <x-rating :rating="$rating"/>
                                            </div>
                                            <span>{{$review->updated_at->diffForHumans()}}</span>
                                        </div>
                                    </div>
                                </div>
                                <p>{{$review->review}}</p>

                                @if($review->images->count())
                                    <div class="review_img_div">
                                        @foreach($review->images as $key => $image)
                                            <img class="review_img" src="{{showImage($image->image)}}" alt="">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if(@$review->reply)
                        <div class="single_reviews">
                            <div class="thumb">
                                @if(@$review->customer->avatar != null)
                                    {{\Illuminate\Support\Str::limit(@$review->customer->first_name,1,$end='')}}{{\Illuminate\Support\Str::limit(@$review->customer->last_name,1,$end='')}}
                                @elseif($review->is_anonymous == 1)
                                    <img src="{{showImage('frontend/default/img/avatar.jpg')}}" alt="{{@$review->customer->first_name.' '.@$review->customer->last_name}}" title="{{@$review->customer->first_name.' '.@$review->customer->last_name}}" />
                                @else
                                    <img src="{{showImage(@$review->customer->avatar)}}" alt="{{@$review->customer->first_name.' '.@$review->customer->last_name}}" title="{{@$review->customer->first_name.' '.@$review->customer->last_name}}" />
                                @endif
                            </div>
                            <div class="review_content">
                                <div class="review_content_head d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="review_content_head_left">
                                        <h4 class="f_w_700 font_20" >{{@$review->seller->first_name}}</h4>
                                        <div class="rated_customer d-flex align-items-center">
                                            <span>{{$review->reply->created_at->diffForHumans()}}</span>
                                        </div>
                                    </div>
                                </div>
                                <p>{{@$review->reply->review}}</p>

                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach

            @else
            <p>{{ __('defaultTheme.no_review_found') }}</p>
            @endif
        </div>
    </div>

</div>
<div class="mb_30 mt_30">
    @if($reviews->lastPage() > 1)
        <x-pagination-component :items="$reviews" type=""/>
    @endif
</div>


