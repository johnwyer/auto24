@extends('front/layout/layout')
@section('meta')

@stop
@section('content')
    <script>
        var priceList = {!!  json_encode($data['price_list']) !!};
        var asId = {!! $data['autoservice']->id !!}
        var perPage = {!! json_encode($data['per_page'])!!}
        @if(!empty($data['autoservice']->image))
            var autoImage = '{{asset('images/autoservice/'.$data['autoservice']->image)}}';
        @else
            var autoImage = "/images/autoservice/no_photo.png";
        @endif

        var review = {!! json_encode($data['rating']['comment'])!!};
        var totalReview = {{(int)$data['rating']['total']}};
    </script>
<div class="card" id="card" v-cloak>
    <div class="card-info">
        <h1>{!! $data['autoservice']->name !!} </h1>
        <div class="card-info_adr">
            <div class="wrapper adr">
                <img src="{{asset('/img/point_vue_map.svg')}}" alt="">
                <?php
                $arr = [
                    $data['autoservice']->id_district,
                    $data['autoservice']->id_city,
                ];

                $location_info = array_flatten(\App\Models\Localization::whereIn('id',$arr)->orderBy('id','asc')->select('name_'.Lang::getLocale())->get()->toArray());
                ?>
                <span>@if(isset($location_info[0])){{$location_info[0]}},@endif @if(isset($location_info[1])){{$location_info[1]}},@endif {!! $data['autoservice']->address !!}</span>
            </div>
            <div class="wrapper map">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 275.334 275.334" style="enable-background:new 0 0 275.334 275.334;" xml:space="preserve" width="23px" height="23">
                    <path d="M137.667,168.021c16.737,0,30.354-13.617,30.354-30.354s-13.617-30.354-30.354-30.354s-30.354,13.617-30.354,30.354
                        S120.93,168.021,137.667,168.021z M137.667,119.313c10.121,0,18.354,8.233,18.354,18.354s-8.233,18.354-18.354,18.354
                        s-18.354-8.233-18.354-18.354S127.546,119.313,137.667,119.313z"/>
                                    <path d="M269.334,131.667h-23.775c-3.015-54.818-47.074-98.877-101.892-101.892V6c0-3.313-2.687-6-6-6s-6,2.687-6,6v29.605
                        c0,3.313,2.687,6,6,6c52.969,0,96.062,43.093,96.062,96.062s-43.093,96.062-96.062,96.062s-96.062-43.093-96.062-96.062
                        c0-36.783,21.452-70.817,54.651-86.704c2.989-1.431,4.253-5.013,2.822-8.002c-1.43-2.988-5.015-4.252-8.002-2.822
                        c-18.131,8.676-33.473,22.217-44.366,39.158c-10.11,15.724-15.897,33.718-16.924,52.37H6c-3.313,0-6,2.687-6,6s2.687,6,6,6h23.775
                        c3.015,54.818,47.074,98.877,101.892,101.892v23.775c0,3.313,2.687,6,6,6s6-2.687,6-6v-23.775
                        c54.818-3.015,98.877-47.074,101.892-101.892h23.775c3.313,0,6-2.687,6-6S272.647,131.667,269.334,131.667z"/>
                </svg>
                <span v-on:click="goMap">@lang('v.show_map')</span>
            </div>
        </div>
        <section class="card-info_up" >
            <div class="card-info_grid">
{{--                <div class="tel">
                    <transition name="fade" mode="out-in">

                    </transition>
                </div>--}}
                <div class="grafic"
                     :class="{opened: isScheduleOpened, active: isActviveDay}"
                     v-on:click="showSchedule">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         width="21px" height="21px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                        <path d="M587.572,186.881c-32.266-75.225-87.096-129.934-162.949-162.285C386.711,8.427,346.992,0.168,305.497,0.168
                            c-41.488,0-80.914,8.181-118.784,24.428C111.488,56.861,56.415,111.535,24.092,186.881C7.895,224.629,0,264.176,0,305.664
                            c0,41.496,7.895,81.371,24.092,119.127c32.323,75.346,87.403,130.348,162.621,162.621c37.877,16.247,77.295,24.42,118.784,24.42
                            c41.489,0,81.214-8.259,119.12-24.42c75.853-32.352,130.683-87.403,162.956-162.621C603.819,386.914,612,347.16,612,305.664
                            C612,264.176,603.826,224.757,587.572,186.881z M538.724,440.853c-24.021,41.195-56.929,73.876-98.375,98.039
                            c-41.195,24.021-86.332,36.135-134.845,36.135c-36.47,0-71.27-7.024-104.4-21.415c-33.129-14.384-61.733-33.294-85.661-57.215
                            c-23.928-23.928-42.973-52.811-57.214-85.997c-14.199-33.065-21.08-68.258-21.08-104.735c0-48.52,11.921-93.428,35.807-134.509
                            c23.971-41.231,56.886-73.947,98.039-98.04c41.146-24.092,85.99-36.142,134.502-36.142c48.52,0,93.649,12.121,134.845,36.142
                            c41.446,24.164,74.283,56.879,98.375,98.039c24.092,41.153,36.135,85.99,36.135,134.509
                            C574.852,354.185,562.888,399.399,538.724,440.853z"/>
                        <path d="M324.906,302.988V129.659c0-10.372-9.037-18.738-19.41-18.738c-9.701,0-18.403,8.366-18.403,18.738v176.005
                            c0,0.336,0.671,1.678,0.671,2.678c-0.671,6.024,1.007,11.043,5.019,15.062l100.053,100.046c6.695,6.695,19.073,6.695,25.763,0
                            c7.694-7.695,7.188-18.86,0-26.099L324.906,302.988z"/>
                </svg>
                    <?php
                        $dec = json_decode($data['autoservice']->week);
                        if(!count($dec)){
                            $dec = [
                                (object) ['id'=> 1, 'from' => '08:00', 'to' => '23:00', 'no' => 'false', 'name_ru' => 'Понедельник', 'name_ro'=>'Luni'],
                                (object) ['id'=> 2, 'from' => '08:00', 'to' => '23:00' ,'no' => 'false','name_ru' => 'Вторник','name_ro'=>'Marti'],
                                (object) ['id'=> 3, 'from' => '08:00', 'to' => '23:00' ,'no' => 'false','name_ru' => 'Среда','name_ro'=>'Miercuri'],
                                (object) ['id'=> 4, 'from' => '08:00', 'to' => '23:00' ,'no' => 'false','name_ru' => 'Четверг','name_ro'=>'Joi'],
                                (object) ['id'=> 5, 'from' => '08:00', 'to' => '23:00' ,'no' => 'false','name_ru' => 'Пятница','name_ro'=>'Vineri'],
                                (object) ['id'=> 6, 'from' => '08:00', 'to' => '23:00' ,'no' => 'true' ,'name_ru' => 'Суббота','name_ro'=>'Simbata'],
                                (object) ['id'=> 7, 'from' => '08:00', 'to' => '23:00' ,'no' => 'true' ,'name_ru' => 'Воскресенье','name_ro'=>'Duminica']
                            ];
                            $dec = (object) $dec;
                        }
                    ?>
                        @foreach($dec as $item)
                            @if($item->id == date("N"))
                                @if($item->no !== "true")
                                <?php
                                    $actv = 0;
                                    $current_time = (float)str_replace(":",".",date('H-i'));

                                    $v = new \DateTime($item->from);
                                    $start_at = (float)str_replace(":",".",date_format($v,'H:i'));
                                    $v = new \DateTime($item->to);
                                    $stop_at = (float)str_replace(":",".",date_format($v,'H:i'));
                                    if($current_time > $start_at && $current_time < $stop_at ){
                                        $actv = 1;
                                    }
                                ?>
                                    <span>@lang('v.today_after') {{$item->to}}</span>
                                @else
                                    <span>@lang('v.today_week')</span>
                                @endif
                            @endif
                        @endforeach
                    <div class="grafic-inner">
                        <ul>
                            @foreach($dec as $item)
                                <li @if($item->no === "true") class="active" @endif><span>{{$item->$name_user}}</span><b>@if($item->no === "true") @lang('v.weekend') @else {{$item->from}} — {{$item->to}} @endif </b></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="rate">
                    <el-rate v-if="{{$data['rating']['rating']}}"
                            :value="{{number_format($data['rating']['rating'], 1, '.', ' ')}}"
                            allow-half
                            disabled-void-color="#c8c8c8"
                            show-text
                            :colors="['#ffc323','#ffc323','#ffc323']"
                            disabled>
                    </el-rate>
                    <el-rate v-else
                            :value="{{number_format($data['rating']['rating'], 1, '.', ' ')}}"
                            allow-half
                            disabled-void-color="#c8c8c8"
                            :colors="['#ffc323','#ffc323','#ffc323']"
                            disabled>
                    </el-rate>
                    <span v-on:click="goToReview({{$data['rating']['total']}})">
                        <span v-text="{{$data['rating']['total']}} > 0 ? {{$data['rating']['total']}} : 'нет'"></span>
                        <span v-text="returnText({{$data['rating']['total']}},['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                    </span>
                </div>
                <div class="record">
                    <a href="{{URL::route('order',$data['autoservice']->slug)}}">@if(!Auth::guard('dealer')->user()) <button class="btn-red">@lang('v.repair_record')</button> @endif</a>
                </div>
                <div class="comfort">
                    @if(count($data['option']))
                        <ul>
                            @foreach($data['option'] as $item)
                                <li>
                                    <div class="svg">{!! $item->image !!}</div>
                                    <span>{{$item->$name_user}}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="slider" id="slider">
                @if(count($data['image']))
                    <?php
                    $sliderCounter = count($data['image']);
                    $indicatorPosition = 'none';
                    $sliderArrows = 'never';
                    if($sliderCounter > 1) {
                        $indicatorPosition = '';
                        $sliderArrows = 'hover';
                    }
                    ?>
                    <el-carousel :height="height"
                                 :autoplay="false"
                                 indicator-position="{{ $indicatorPosition }}"
                                 arrow="{{ $sliderArrows }}">
                        @foreach($data['image'] as $item)
                            <el-carousel-item key="item{{$item->id}}">
                                <div class="element" style="background-image: url({{asset('images/autoservice/'.$item->image)}})"></div>
                            </el-carousel-item>
                        @endforeach
                    </el-carousel>
                @endif
            </div>
        </section>
        <section class="card-price">
            <h2>@lang('v.price_list')</h2>
            <div class="card-price_marka">
                @if(count($data['mark']))
                <div class="title">@lang('v.serve_brands')</div>
                <div class="marka-wrapper" id="marks-list">
                    <?php $marksCounter = 0; $showLink = false; ?>
                    @foreach($data['mark'] as $item)
                    <transition name="fade" mode="out-in" appear>
                        <div class="marka-block @if($marksCounter > 18) hidden @endif" key="marka_item_<?php echo($marksCounter)?>" title="">
                            <div class="marka-item" style="background-image: url({{asset('images/marks/'.$item->image)}})"></div>
                        </div>
                    </transition>
                        @if($marksCounter > 18 && !$showLink)
                            <div class="marka-block show-more"
                                 v-if="showMoreMarks"
                                 v-on:click="showMarks">
                                <div class="marka-item">
                                    <span>@lang('v.show_all')</span>
                                    <i class="icon-other-services"></i>
                                </div>
                            </div>
                            <?php $showLink = true; ?>
                        @endif
                        <?php $marksCounter++ ?>
                    @endforeach
                    {{--</transition-group>--}}
                </div>
                @endif
                <div class="title">@lang('v.select_name_service')</div>
                    <div class="price_find order-services_find">
                        {{--<find-select v-model="selected"
                                     filterable
                                     placeholder="Например: замена масла"
                                     no-match-text="Услуга не найдена"
                                     no-data-text="Услуг нет">
                            <el-option
                                    v-for="(item,index) in priceListReverted"
                                    :key="item.id_unic"
                                    :label="item.name_ru"
                                    --}}{{--:value-key="index+''"--}}{{--
                                    :value="item.id+','+item.id_unic">
                            </el-option>
                        </find-select>
                    </div>--}}
                {{--<div class="order-services_find">--}}
                    <service-find :price-list="priceList" :selecteditem.sync="selectedService"></service-find>
                </div>
                <div class="title">@lang('v.select_special')</div>
                <div class="price_wrapper">
                    <ul>
                        <li v-for="(item,index) in priceList" v-on:click="showInner($event)" :ref="'li'+item.id">
                            <div class="svg" v-html="item.image ? item.image : priceListBlock"></div>
                            <div class="link" v-text="item.name_ru"></div>
                            <div class="inner" v-if="item.service.length" v-on:click.stop>
                                <div class="inner-title">
                                    <span>@lang('v.services')</span>
                                    <span>@lang('v.price_at')</span>
                                </div>
                                <div class="inner-scroll">
                                    <el-scrollbar :noresize="false"
                                                  :native="false"
                                                  :view-style="{'max-height':'50vh'}">
                                        <template v-if="item.service.length">
                                            <div class="inner-block">
                                                <ul>
                                                    <li v-for="(item,index) in item.service"
                                                        :tabindex="index + 50"
                                                        :ref="item.id + '_' + item.ident">
                                                        <div>
                                                            <p v-text="item.name_ru"></p>
                                                        </div>
                                                        <div>
                                                            <b v-text="item.price"></b>
                                                            <b span>@lang('v.mdl')</b>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </template>
                                    </el-scrollbar>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="card-review" class="card-review" v-if="{{$data['rating']['total']}} > 0">
            <h2> <span>
                    <span v-text="{{$data['rating']['total']}} > 0 ? {{$data['rating']['total']}} : '@lang("v.not")'"></span>
                    <span v-text="returnText({{$data['rating']['total']}},['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                </span>
            </h2>
            <div class="card-review_wrapper">
                <template v-for="item in review">
                    <transition name="fade" mode="out-in" appear>
                        <div class="card-review_block">
                            <div class="up">
                                <div class="name">
                                    <h4 v-text="item.name" class="name-title"></h4>
                                    <el-rate
                                            v-model.number="item.rate"
                                            allow-half
                                            disabled-void-color="#c8c8c8"
                                            show-text
                                            :colors="['#ffc323','#ffc323','#ffc323']"
                                            disabled>
                                    </el-rate>
                                </div>
                                <div class="date">@{{ item.date | chatDate }}</div>
                            </div>
                            <div class="text" v-text="item.text"></div>
                        </div>
                    </transition>
                </template>
                <p class="card-review_add">
                    <button v-if="totalReview > 10"  v-on:click="addReview" class="card-review_addbutton">@lang('v.load_more')
                        <span v-if="(totalReview - 10) < 10" v-text="(totalReview - 10)"></span>
                        <span v-if="(totalReview - 10) < 10" v-text="returnText((totalReview - 10),['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                        <span v-if="(totalReview - 10) > 10" >10</span>
                        <span v-if="(totalReview - 10) > 10" v-text="returnText(10,['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                    </button>
                </p>
            </div>
        </section>
        <section class="card-map">
            <div id="card-map" v-cord="{lat: {{$data['autoservice']->cord_x}}, lng: {{$data['autoservice']->cord_y}} }"></div>
        </section>
    </div>

    @if(count($data['nearby_autoservice']))
    <div class="card-around">
        <div class="card-around_wrapper">
            <div class="maintitle">@lang('v.service_nearby')</div>
            <div class="card-around_in">

                @foreach($data['nearby_autoservice'] as $item)
                    <div class="block">
                    <div class="img" style="background-image: url({{asset('images/autoservice/'.$item['image'])}})">
                        <a href="{{URL::route('autoservice_page',$item['slug'])}}"></a>
                    </div>
                    <div class="text">
                        <div class="title"><h3><a href="{{URL::route('autoservice_page',$item['slug'])}}">{{$item['name']}}</a></h3></div>
                        <div class="adr"> {{$item['address']}}</div>
                        <div class="rate">
                            <el-rate
                                    :value="{{$item['rating']['rating']}}"
                                    allow-half
                                    disabled-void-color="#c8c8c8"
                                    show-text
                                    :colors="['#ffc323','#ffc323','#ffc323']"
                                    disabled v-if="{{$item['rating']['rating']}}">
                            </el-rate>
                            <el-rate
                                    :value="{{$item['rating']['rating']}}"
                                    allow-half
                                    disabled-void-color="#c8c8c8"

                                    :colors="['#ffc323','#ffc323','#ffc323']"
                                    disabled v-else>
                            </el-rate>
                            <span>
                                  <span v-text="{{$item['rating']['total']}} > 0 ? {{$item['rating']['total']}} : '@lang("v.not")'"></span>
                                  <span v-text="returnText({{$item['rating']['total']}}, ['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

    @if(isset($actv) && $actv)
        <script>
            var isActviveDay = 1;
        </script>
    @endif
@stop

