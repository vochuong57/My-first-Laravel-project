<footer class="footer">
    <div class="uk-container uk-container-center">
        <div class="footer-upper">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-5">
                    <div class="footer-contact">
                        <a href="" class="image img-scaledown"><img src="{{ $systems['homepage_favicon'] }}" alt=""></a>
                        <div class="footer-slogan">{{ $systems['homepage_slogan'] }}</div>
                        <div class="company-address">
                            <div class="address">{{ __('frontend.address') }}: {{ $systems['contact_office'] }}</div>
                            <div class="phone">Hotline: {{ $systems['contact_hotline'] }}</div>
                            <div class="email">Email: {{ $systems['contact_email'] }}</div>
                            <div class="hour">{{ __('frontend.work_time') }}: {{ $systems['contact_work_hour'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-3-5">
                    <div class="footer-menu">
                        <div class="uk-grid uk-grid-medium">
                            @if(isset($menus['menu-footer']) && count($menus['menu-footer']))
                            @foreach($menus['menu-footer'] as $key => $val)
                            @php
                                $name = $val['item']->languages->first()->getOriginal('pivot_name');
                                $canonical = write_url($val['item']->languages->first()->getOriginal('pivot_canonical'));
                            @endphp
                            <div class="uk-width-large-1-4">
                                <div class="ft-menu">
                                    <div class="heading">{{ $name }}</div>
                                    @if(count($val['children']))
                                    <ul class="uk-list uk-clearfix">
                                        @foreach($val['children'] as $key => $val)
                                        @php
                                            $name = $val['item']->languages->first()->getOriginal('pivot_name');
                                            $canonical = write_url($val['item']->languages->first()->getOriginal('pivot_canonical'));
                                        @endphp
                                        <li><a href="{{ $canonical }}">{{ $name }}</a></li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-1-5">
                    <div class="fanpage-facebook" style="padding-top: 70px">
                        <div class="ft-menu">
                            <div class="heading">Fanpage Facebook</div>
                            <!-- <div class="fanpage">
                                <div class="fb-page" data-href="https://www.facebook.com/facebook" data-tabs="" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/facebook" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="copyright-text">{!! $systems['homepage_copyright'] !!}</div>
                <div class="copyright-contact">
                    <div class="uk-flex uk-flex-middle">
                        <div class="phone-item">
                            <div class="p">Hotline: {{ $systems['contact_hotline'] }}</div>
                            <div class="worktime">{{ __('frontend.work') }}: 8:00 - 22:00</div>
                        </div>
                        <div class="phone-item">
                            <div class="p">Support: {{ $systems['contact_hotline'] }}</div>
                            <div class="worktime">{{ __('frontend.support') }} 24/7</div>
                        </div>
                    </div>
                </div>
                <div class="social">
                    <div class="uk-flex uk-flex-middle">
                        <div class="span">{{ __('frontend.follow_us') }}:</div>
                        <div class="social-list">
                            @php
                                $socials = ['facebook', 'youtube', 'twitter'];
                            @endphp
                            <div class="uk-flex uk-flex-middle">
                                @foreach($socials as $key => $val)
                                <a target="_blank" href="{{ $systems['social_'.$val] }}" class=""><i class="fa fa-{{ $val }}"></i></a>
                                @endforeach
                                <!-- <a href="" class=""><i class="fa fa-twitter"></i></a>
                                <a href="" class=""><i class="fa fa-skype"></i></a>
                                <a href="" class=""><i class="fa fa-youtube"></i></a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>