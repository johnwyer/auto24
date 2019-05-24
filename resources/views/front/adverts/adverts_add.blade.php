@extends('front.adverts.layout')
@section('meta')
@stop

@section('script_top')
    window.info = <?php echo json_encode([
        'marks' => $marks,
        'colors' => $colors
    ]); ?>
@stop

@section('content')
    <div class="order adverts-add" id="adverts-add">
        <div class="order-wrapper">
            <div class="order-head">
                <ul>
                    <li :class="{active: progress.step === 1}">
                        <div class="order-head_back">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="9px" height="14px">
                                <path fill-rule="evenodd"  fill="rgb(142, 142, 142)"
                                      d="M7.500,13.999 L1.500,8.399 L1.500,8.400 L-0.000,7.000 L7.500,-0.001 L9.000,1.400 L3.000,6.999 L9.000,12.599 L7.500,13.999 Z"/>
                            </svg>
                        </div>
                        <div class="order-head_block" v-on:click="changeStep(1)">
                            <svg class="order-head_svg" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="26px" height="26px" viewBox="0 0 447.645 447.645" style="enable-background:new 0 0 447.645 447.645;" xml:space="preserve">
                                <path d="M447.639,244.402c0-8.805-1.988-17.215-5.578-24.909c-0.37-1.956-0.793-3.909-1.322-5.89l-38.884-96.365l-0.263-0.867
                                    c-13.605-40.509-32.963-78.001-82.049-78.001H131.868c-50.296,0-68.069,38.421-81.972,77.776l-40.673,96.6
                                    C3.343,222.167,0,232.944,0,244.402v29.986c0,4.636,0.548,9.171,1.59,13.539C0.577,290.566,0,293.41,0,296.408v89.185
                                    c0,13.078,10.602,23.682,23.68,23.682h49.14c13.071,0,23.673-10.604,23.673-23.682v-44.599h257.46v44.599
                                    c0,13.078,10.604,23.682,23.683,23.682h46.326c13.083,0,23.683-10.604,23.683-23.682v-89.195c0-2.987-0.583-5.844-1.588-8.474
                                    c1.038-4.375,1.588-8.905,1.588-13.54v-29.981H447.639z M78.754,125.821c15.483-43.683,27.934-57.018,53.114-57.018h187.664
                                    c24.995,0,38.913,14.873,53.056,56.83l28.375,57.502c-9.265-3.431-19.461-5.335-30.173-5.335H76.849
                                    c-9.645,0-18.862,1.551-27.366,4.358L78.754,125.821z M103.129,285.776H51.281c-9.335,0-16.906-7.578-16.906-16.912
                                    c0-9.337,7.571-16.91,16.906-16.91h51.848c9.339,0,16.91,7.573,16.91,16.91C120.039,278.198,112.463,285.776,103.129,285.776z
                                     M286.284,282.389h-120.6c-5.913,0-10.704-4.794-10.704-10.704c0-5.921,4.791-10.713,10.704-10.713h120.6
                                    c5.92,0,10.71,4.792,10.71,10.713C296.994,277.595,292.204,282.389,286.284,282.389z M395.051,285.776h-51.846
                                    c-9.343,0-16.91-7.578-16.91-16.912c0-9.337,7.573-16.91,16.91-16.91h51.846c9.343,0,16.916,7.573,16.916,16.91
                                    C411.967,278.198,404.394,285.776,395.051,285.776z"/>
                            </svg>
                            <span class="order-head_text">@lang('v.auto')</span>
                        </div>
                        <div class="order-head_next"><img src="{{asset('/img/right-arrow_2.svg')}}" alt=""></div>
                    </li>
                    <li :class="{active: progress.step === 2}">
                        <div class="order-head_back">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="9px" height="14px">
                                <path fill-rule="evenodd"  fill="rgb(142, 142, 142)"
                                      d="M7.500,13.999 L1.500,8.399 L1.500,8.400 L-0.000,7.000 L7.500,-0.001 L9.000,1.400 L3.000,6.999 L9.000,12.599 L7.500,13.999 Z"/>
                            </svg>
                        </div>
                        <div class="order-head_block" v-on:click="changeStep(2)">
                            <svg class="order-head_svg" width="28" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 84 77" style="enable-background:new 0 0 84 77;" xml:space="preserve">
                            <path d="M82.5,12.1L37.3,0.4l-3.4,13.1L0.1,16.8L6,76.5l46.5-4.6l-0.6-5.8l15.6,4L82.5,12.1z M47,67.4L10.4,71L5.5,21.3l27-2.7
                                L22.3,58.4l24.4,6.3L47,67.4z M28.4,54.8L40.9,6.5l35.6,9.2L63.9,64L28.4,54.8z"/>
                            </svg>
                            <span class="order-head_text">Внешний вид</span>
                        </div>
                        <div class="order-head_next"><img src="{{asset('/img/right-arrow_2.svg')}}" alt=""></div>
                    </li>
                    <li :class="{active: progress.step === 3}">
                        <div class="order-head_back">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="9px" height="14px">
                                <path fill-rule="evenodd"  fill="rgb(142, 142, 142)"
                                      d="M7.500,13.999 L1.500,8.399 L1.500,8.400 L-0.000,7.000 L7.500,-0.001 L9.000,1.400 L3.000,6.999 L9.000,12.599 L7.500,13.999 Z"/>
                            </svg>
                        </div>
                        <div class="order-head_block" v-on:click="changeStep(3)">
                            <svg class="order-head_svg" version="1.1" width="26px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                <path d="M97,49.3h3c0-2.6-0.3-5.2-0.7-7.8l-3,0.5l-0.2-1.4l3-0.6c-0.6-2.8-1.4-5.5-2.4-8.1l-2.8,1.1l-0.5-1.3l2.8-1.1
                                c-1.1-2.7-2.5-5.3-4-7.7l-2.5,1.7l-0.8-1.2l2.5-1.7c-1.6-2.4-3.5-4.7-5.5-6.8l-2.1,2.1l-1-1l2.1-2.1c-1.9-1.8-3.9-3.5-6-5l-1.7,2.5
                                L76,10.9l1.7-2.5c-2.3-1.6-4.8-2.9-7.4-4.1l-1.2,2.8l-1.3-0.5L69,3.7c-2.7-1.1-5.4-2-8.3-2.6l-0.6,3l-1.4-0.3l0.6-3
                                C56.6,0.3,53.7,0,50.7,0v3h-1.4V0c-2.6,0-5.2,0.3-7.8,0.7l0.6,3L40.8,4l-0.6-3c-2.8,0.6-5.5,1.4-8.1,2.4l1.1,2.8l-1.3,0.5l-1.1-2.8
                                c-2.7,1.1-5.3,2.5-7.7,4l1.7,2.6l-1.2,0.8l-1.7-2.5c-2.4,1.6-4.7,3.5-6.8,5.5l2.2,2.2l-1,1l-2.2-2.2c-1.8,1.9-3.5,3.9-5,6l2.5,1.7
                                L10.9,24l-2.5-1.7c-1.6,2.3-2.9,4.8-4.1,7.4l2.8,1.2l-0.5,1.3L3.8,31c-1.1,2.7-2,5.4-2.6,8.3l3,0.6l-0.3,1.4l-3-0.6
                                C0.3,43.5,0,46.4,0,49.3h3.1v1.4H0c0,2.6,0.3,5.2,0.7,7.8l3-0.6L4,59.3l-3,0.6c0.6,2.8,1.4,5.5,2.4,8.1l2.9-1.1l0.5,1.3l-2.9,1.1
                                C5,72,6.3,74.5,7.9,77l2.6-1.7l0.8,1.2l-2.6,1.7c1.6,2.4,3.5,4.7,5.5,6.8l2.2-2.2l1,1l-2.2,2.2c1.9,1.8,3.9,3.5,6,5l1.7-2.5l1.1,0.8
                                l-1.7,2.5c2.3,1.6,4.8,2.9,7.4,4.1l1.2-2.8l1.3,0.5L31,96.3c2.7,1.1,5.4,2,8.3,2.6l0.6-3l1.4,0.3l-0.6,3c2.8,0.5,5.7,0.8,8.7,0.9v-3
                                h1.4v3c2.6,0,5.2-0.3,7.8-0.7l-0.6-3l1.4-0.3l0.6,3c2.8-0.6,5.5-1.4,8.1-2.4l-1.1-2.8l1.3-0.5l1.1,2.8c2.7-1.1,5.3-2.5,7.7-4
                                l-1.7-2.5l1.2-0.8l1.7,2.5c2.4-1.6,4.7-3.5,6.8-5.5l-2.1-2.1l1-1l2.1,2.1c1.8-1.9,3.5-3.9,5-6l-2.5-1.7l0.8-1.1l2.5,1.7
                                c1.6-2.3,2.9-4.8,4.1-7.4l-2.8-1.2l0.5-1.3l2.8,1.2c1.1-2.7,2-5.4,2.6-8.3l-3-0.6l0.3-1.4l3,0.6c0.5-2.8,0.8-5.7,0.9-8.7h-3L97,49.3
                                L97,49.3z M50,87.9c-21,0-37.9-17-37.9-37.9c0-21,17-37.9,37.9-37.9S87.9,29,87.9,50C87.9,71,71,87.9,50,87.9z"/>
                                                            <path d="M50,16.2c-18.7,0-33.8,15.1-33.8,33.8S31.3,83.8,50,83.8c18.7,0,33.8-15.1,33.8-33.8S68.7,16.2,50,16.2z M55,22.9
                                c0.6-0.9,1.7-1.6,4.8-0.6c3.1,1.1,5.2,2.4,7,3.6c1.7,1.2,5.6,4.5,6.7,6.5c1.1,2,1.4,3,0.8,3.6c-1.1,1.2-10.9,4.2-12,4.3
                                c-1.2,0.1-3.1,0.5-4.8-0.7c-0.9-0.6-2-1.9-2.2-4.8C55,32.1,54.4,23.8,55,22.9z M59.3,49.9c-1.3,0.4-2.7-0.4-3.1-1.7s0.4-2.7,1.7-3.1
                                c1.3-0.4,2.7,0.4,3.1,1.7S60.6,49.5,59.3,49.9z M53.6,55.3c1.1-0.8,2.7-0.5,3.5,0.7c0.8,1.1,0.5,2.7-0.7,3.5
                                c-1.1,0.8-2.7,0.5-3.5-0.7C52.2,57.6,52.5,56.1,53.6,55.3z M50,38.6c1.4,0,2.5,1.1,2.5,2.5s-1.1,2.5-2.5,2.5c-1.4,0-2.5-1.1-2.5-2.5
                                S48.6,38.6,50,38.6z M46.6,58.6c-0.8,1.1-2.4,1.3-3.5,0.5c-1.1-0.8-1.3-2.4-0.5-3.5c0.8-1.1,2.4-1.3,3.5-0.5
                                C47.2,55.9,47.4,57.5,46.6,58.6z M40.8,49.7c-1.3-0.4-2.1-1.8-1.6-3.1c0.4-1.3,1.8-2.1,3.1-1.6c1.3,0.4,2.1,1.8,1.6,3.1
                                C43.5,49.4,42.1,50.1,40.8,49.7z M26.7,32.3c2-2.6,3.9-4.3,5.5-5.5c1.7-1.3,6-3.9,8.3-4.4c2.3-0.4,3.3-0.4,3.7,0.3
                                c0.8,1.4,0.7,11.6,0.5,12.8c-0.2,1.1-0.4,3.1-2.1,4.4c-0.9,0.7-2.4,1.4-5.2,0.7c-2.8-0.7-10.9-2.7-11.6-3.5
                                C25,36.2,24.7,34.9,26.7,32.3z M30.5,69.5c-1,0.4-2.3,0.3-4.3-2.3c-1.9-2.6-3-4.9-3.7-6.9c-0.7-2-2.1-6.9-1.8-9.2
                                c0.3-2.3,0.6-3.3,1.4-3.5c1.6-0.4,11.3,2.7,12.4,3.3c1,0.6,2.8,1.3,3.6,3.3c0.4,1,0.6,2.7-0.9,5.2S31.4,69.1,30.5,69.5z M57.7,78.3
                                c-3.1,0.9-5.7,1-7.7,1c-2.1,0-7.1-0.5-9.2-1.5c-2.1-1.1-2.9-1.7-2.7-2.5c0.2-1.6,6.5-9.7,7.4-10.4s2.3-2.2,4.3-2.2
                                c1.1,0,2.7,0.4,4.6,2.6c1.8,2.3,7,8.8,7,9.8C61.4,76.2,60.9,77.4,57.7,78.3z M73.5,67.5c-1.6,1.7-2.5,2.2-3.2,1.8
                                c-1.5-0.7-7.2-9.2-7.6-10.3c-0.5-1.1-1.4-2.8-0.7-4.8c0.3-1,1.2-2.5,3.9-3.5c2.7-1,10.5-3.9,11.5-3.6c1,0.3,2,1.2,1.9,4.4
                                c-0.2,3.2-0.8,5.7-1.4,7.7C77.2,61.2,75.2,65.8,73.5,67.5z"/>
                            </svg>
                            <span class="order-head_text">Комплектация</span>
                        </div>
                        <div class="order-head_next"><img src="{{asset('/img/right-arrow_2.svg')}}" alt=""></div>
                    </li>
                    <li :class="{active: progress.step === 4}">
                        <div class="order-head_back">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="9px" height="14px">
                                <path fill-rule="evenodd"  fill="rgb(142, 142, 142)"
                                      d="M7.500,13.999 L1.500,8.399 L1.500,8.400 L-0.000,7.000 L7.500,-0.001 L9.000,1.400 L3.000,6.999 L9.000,12.599 L7.500,13.999 Z"/>
                            </svg>
                        </div>
                        <div class="order-head_block" v-on:click="changeStep(4)">
                            <svg class="order-head_svg" width="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 98 71" style="enable-background:new 0 0 98 71;" xml:space="preserve">
                            <path d="M90.3,24h-2.6C82.4,11.9,77.3,5.6,76.2,4.3c-0.3-0.4-0.6-0.7-1.1-0.9C74.7,3.2,65.5,0,48.7,0S22.3,3.2,21.9,3.4
                                c-0.5,0.2-0.9,0.5-1.1,0.9C19.7,5.6,14.6,12,9.3,24H6.7C3,24,0,27,0,30.8l0,0v0.4c0,3.7,3,6.7,6.7,6.7H7V63c0,4.3,3.5,7.9,7.9,7.9h6
                                c4.3,0,7.9-3.5,7.9-7.8h39.5c0,4.3,3.5,7.8,7.9,7.8h6c4.3,0,7.9-3.5,7.9-7.9V37.9h0.2c3.7,0,6.7-3,6.7-6.7v-0.3
                                C97,27.1,94,24,90.3,24z M92.7,31.1c0,1.3-1.1,2.4-2.4,2.4l0,0h-2.2c-1.3,0-2.3,1-2.3,2.3l0,0v27.1c0,2-1.6,3.6-3.6,3.6l0,0h-6
                                c-2,0-3.6-1.6-3.6-3.5h6.8c1.2,0,2.2-0.9,2.2-2.1c0-1.2-0.9-2.2-2.1-2.2h-0.1H17.9c-1.2,0-2.2,0.9-2.2,2.1c0,1.2,0.9,2.2,2.1,2.2
                                c0,0,0,0,0.1,0h6.7c0,2-1.6,3.5-3.6,3.5h-6c-2,0-3.6-1.6-3.6-3.6l0,0v-27c0-1.3-1-2.3-2.3-2.3H6.7c-1.3,0-2.4-1.1-2.4-2.4l0,0v-0.3
                                c0-1.3,1.1-2.4,2.4-2.4H11c1.7,0,2.2-1.4,3-3c0,0,8.8-16.7,9.9-18.1c2.3-0.7,10.9-3,24.7-3S70.8,6.6,73,7.2c1.2,1.4,10,18.1,10,18.1
                                c0.8,1.5,1.3,3,3,3h4.3c1.3,0,2.4,1.1,2.4,2.4l0,0L92.7,31.1z"/>
                                                            <path d="M56.4,43.8h-8.9c-2.5,0-4.6-2.1-4.6-4.6V38h9c1.1,0,2-0.9,2-2s-0.9-2-2-2h-9v-1.7h7.3c1.1,0,2-0.9,2-2s-0.9-2-2-2H43v-1.5
                                c0-2.5,2.1-4.6,4.6-4.6h6.9c1.1,0,2-0.9,2-2s-0.8-1.9-1.9-2h-7c-4.7,0-8.6,3.9-8.6,8.6v1.5h-1.6c-1.1,0-2,0.9-2,2s0.9,2,2,2H39V34
                                h-2.5c-1.1,0-2,0.9-2,2s0.9,2,2,2h2.4v1.3c0,4.7,3.9,8.6,8.6,8.6h9c1.1,0,2-1,1.9-2.1C58.4,44.7,57.5,43.8,56.4,43.8L56.4,43.8z"/>
                            </svg>
                            <span class="order-head_text">Состояние и цена</span>
                        </div>
                        <div class="order-head_next"><img src="{{asset('/img/right-arrow_2.svg')}}" alt=""></div>
                    </li>
                    <li :class="{active: progress.step === 5}">
                        <div class="order-head_back">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="9px" height="14px">
                                <path fill-rule="evenodd"  fill="rgb(142, 142, 142)"
                                      d="M7.500,13.999 L1.500,8.399 L1.500,8.400 L-0.000,7.000 L7.500,-0.001 L9.000,1.400 L3.000,6.999 L9.000,12.599 L7.500,13.999 Z"/>
                            </svg>
                        </div>
                        <div class="order-head_block" v-on:click="changeStep(5)">
                            <svg class="order-head_svg" width="30px" height="26px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 31.939 31.939" style="enable-background:new 0 0 31.939 31.939;"
                                 xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M15.58,18.332h-0.777c-0.403,0-0.73-0.326-0.73-0.729c0-0.149,0.06-0.293,0.167-0.397c0.452-0.439,0.832-1.03,1.107-1.667
                                        c0.056,0.041,0.116,0.071,0.184,0.071c0.436,0,0.95-0.964,0.95-1.621c0-0.657-0.061-1.19-0.498-1.19
                                        c-0.052,0-0.106,0.009-0.162,0.023c-0.031-1.782-0.481-4.005-3.202-4.005c-2.839,0-3.17,2.219-3.202,3.999
                                        c-0.04-0.008-0.08-0.017-0.117-0.017c-0.437,0-0.497,0.533-0.497,1.19c0,0.657,0.512,1.621,0.949,1.621
                                        c0.054,0,0.104-0.015,0.151-0.042c0.274,0.627,0.649,1.206,1.094,1.641c0.107,0.104,0.167,0.246,0.167,0.396
                                        c0,0.403-0.327,0.73-0.73,0.73H9.656c-1.662,0-3.009,1.347-3.009,3.009v0.834c0,0.524,0.425,0.95,0.95,0.95h10.042
                                        c0.524,0,0.949-0.426,0.949-0.95v-0.834C18.589,19.68,17.242,18.332,15.58,18.332z"/>
                                    <path d="M24.589,10.077h-8.421c0.243,0.538,0.417,1.2,0.489,2.019c0.18,0.111,0.315,0.29,0.425,0.506h7.507
                                        c0.39,0,0.704-0.315,0.704-0.704v-1.117C25.293,10.393,24.979,10.077,24.589,10.077z"/>
                                    <path d="M24.589,14.678h-7.335c-0.199,0.752-0.689,1.539-1.368,1.749c-0.02,0.037-0.043,0.069-0.064,0.106v0.67h8.766
                                        c0.389,0,0.704-0.315,0.704-0.705v-1.116C25.293,14.993,24.979,14.678,24.589,14.678z"/>
                                    <path d="M24.589,19.279h-5.726c0.378,0.598,0.6,1.303,0.6,2.062v0.463h5.126c0.39,0,0.704-0.315,0.704-0.704v-1.117
                                        C25.293,19.594,24.979,19.279,24.589,19.279z"/>
                                    <path d="M27.615,3.057H4.325C1.936,3.057,0,4.993,0,7.382v17.176c0,2.39,1.936,4.325,4.325,4.325h23.29
                                        c2.389,0,4.324-1.936,4.324-4.325V7.382C31.939,4.993,30.004,3.057,27.615,3.057z M29.898,24.558c0,1.259-1.024,2.283-2.283,2.283
                                        H4.325c-1.259,0-2.283-1.024-2.283-2.283V7.382c0-1.259,1.024-2.283,2.283-2.283h23.29c1.259,0,2.283,1.024,2.283,2.283V24.558z"
                                    />
                                </g>
                            </g>
                            </svg>
                            <span class="order-head_text">Контакты</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="order-body" v-cloak>
                <transition name="fade" mode="out-in">
                    <div v-if="progress.step === 1">
                        <section class="order-body_up order-title">
                            <h1>Подать объявление</h1>
                        </section>
                        <section class="order-body_ul" v-if="progress.markaProgress > 1">
                            <ul>
                                <li class="img" v-if="progress.markaProgress > 1" key="foto"><img v-on:click="setMarkaProgress(1)" :src="'{{asset('/images/marks')}}/' + selectedMarkaImage" alt=""></li>
                                <li class="marka" v-if="progress.markaProgress > 1" key="marka"><span v-on:click="setMarkaProgress(1)" v-text="selectedMarkaName"></span></li>
                                <li class="model" v-if="progress.markaProgress > 2" key="model"><span v-on:click="setMarkaProgress(2)" v-text="selectedModelName"></span></li>
                            </ul>
                        </section>

                        <section class="order-body_up cars-list" v-if="progress.markaProgress === 1" key="cars-marka">
                            <h2 class="order-body_title">@lang('v.enter_name')</h2>
                            <div class="order-marks_find">
                                <input type="text" v-model="getMarka" placeholder="@lang('v.example_bmw')">
                            </div>
                            <h2 class="order-body_title">@lang('v.select_in_list')</h2>
                            <div v-cloak>
                                <div class="order-marks_table">
                                    <div class="order-marks_block"
                                         v-for="(item,index) in allMarkaComputed"
                                         :title="item.name"
                                         :key="'cars-mark' + item.id"
                                         v-on:click="selectM(item.id, item.image, item.name)"
                                         :style="{'backgroundImage': 'url({{asset('/images/marks/')}}/' + item.image + ')'}">
                                    </div>

                                    <div v-if="!isMarkaFilter">
                                        <div class="order-marks_block show-more"
                                             v-on:click="toggleAllMarksControl">
                                            <div class="marka-item"
                                                 v-if="!showAllMarks">
                                                <span>@lang('v.show_all')</span>
                                                <i class="icon-other-services"></i>
                                            </div>
                                            <div class="marka-item"
                                                 v-if="showAllMarks">
                                                <span>показать популярные</span>
                                                <i class="icon-other-services"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="order-body_up cars-list_model" v-else-if="progress.markaProgress === 2" key="cars-model">
                            <h2 class="order-body_title">@lang('v.name_model')</h2>
                            <div class="order-marks_find">
                                <input type="text"  placeholder="@lang('v.for_example')" v-model="getModel">
                            </div>
                            <h2 class="order-body_title">@lang('v.select_in_list')</h2>
                            <div v-cloak>
                                <div class="order-models_table">
                                    <div class="order-models_block"
                                         v-for="(item,index) in allModelsComputed"
                                         :key="'cars-model-' + item.model_id"
                                         v-on:click="selectModel(item.model_id, item.name)">
                                        <span v-text="item.name" :title="item.name"></span>
                                    </div>

                                    <div class="order-marks_block show-more"
                                         v-if="!isModelsFilter && checkModelsFilter()">
                                        <div class="marka-item"
                                             v-on:click="toggleAllModelsControl"
                                             v-if="!showAllModels">
                                            <span>@lang('v.show_all')</span>
                                        </div>
                                        <div class="marka-item"
                                             v-on:click="toggleAllModelsControl"
                                             v-if="showAllModels">
                                            <span>показать популярные</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="order-body_up cars-year" v-if="progress.markaProgress > 2" key="cars-year">
                            <h2 class="order-body_title">Выберите год выпуска</h2>
                            <car-years :initial-years="allDate"
                                       :initial-selected-year="selectedYear"
                                       v-on:cars-year-changed="carsYearChanged"></car-years>
                        </section>

                        <section class="order-body_up cars-body-type" v-if="progress.markaProgress > 3">
                            <h2 class="order-body_title">Тип кузова</h2>
                            <div class="items-list">
                                <div class="items-column">
                                    <el-radio-group v-model="selectedBodyTypeId"
                                                    v-on:change="selectBody()">
                                        <el-radio-button :label="item.id"
                                                         name="carsBody"
                                                         :key="'cars-body-' + item.id"
                                                         v-for="(item) in allBodies">
                                            <div class="el-radio-button__inner-text">
                                                <div class="image">
                                                    <img src="/img/temp/car-body.png" alt="" />
                                                </div>
                                                <div class="text">@{{ item.name  }}</div>
                                            </div>
                                        </el-radio-button>
                                    </el-radio-group>
                                </div>
                                <div class="items-column">
                                    <el-checkbox v-model="selectedWheelType">Правый руль</el-checkbox>
                                </div>
                            </div>
                        </section>

                        <section class="order-body_up cars-generation" v-if="progress.markaProgress > 4" key="cars-generation">
                            <h2 class="order-body_title">Поколение</h2>
                            <div class="items-list">
                                <el-radio-group v-model="selectedGenerationId"
                                                v-on:change="selectGeneration()">
                                    <el-radio-button :label="item.id"
                                                     name="carsGeneration"
                                                     :key="'cars-generation-' + item.id"
                                                     v-for="(item) in allGenerations">
                                        <div class="item-wrapper">
                                            <div class="item-image">
                                                <img :src="'/img/cars/' + item.image" alt="" />
                                            </div>
                                            <span class="item-title">@{{ item.name }}</span>
                                            <span class="item-year">@{{ item.year_begin }}-@{{ item.year_end }}</span>
                                        </div>
                                    </el-radio-button>
                                </el-radio-group>
                            </div>
                        </section>

                        <section class="order-body_up cars-engine" v-if="progress.markaProgress > 5" key="cars-engine">
                            <h2 class="order-body_title">Двигатель</h2>
                            <div class="items-list">
                                <el-radio-group v-model="selectedEngineId"
                                                v-on:change="selectEngine()">
                                    <el-radio-button :label="item.engine_id"
                                                     name="carsEngine"
                                                     :key="'cars-engine' + item.engine_id"
                                                     v-for="(item) in allEngines"><span>@{{ item.name  }}</span></el-radio-button>
                                </el-radio-group>
                                <el-checkbox v-model="selectedEngineGasEquipment">Газобалонное оборудование</el-checkbox>
                            </div>
                        </section>

                        <section class="order-body_up cars-drive" v-if="progress.markaProgress > 6" key="cars-drive">
                            <h2 class="order-body_title">Привод</h2>
                            <div class="items-list">
                                <el-radio-group v-model="selectedDriveId"
                                                v-on:change="selectDrive()">
                                    <el-radio-button :label="item.drive_id"
                                                     name="carsDrive"
                                                     :key="'cars-drive' + item.drive_id"
                                                     v-for="(item) in returnedDrives"><span>@{{ item.name }}</span></el-radio-button>
                                </el-radio-group>
                            </div>
                        </section>

                        <section class="order-body_up cars-gearbox" v-if="progress.markaProgress > 7" key="cars-gearbox">
                            <h2 class="order-body_title">Коробка передач</h2>
                            <div class="items-list">
                                <el-radio-group v-model="selectedGearboxId"
                                                v-on:change="selectGearbox()">
                                    <el-radio-button :label="item.gearbox_id"
                                                     name="carsGearbox"
                                                     :key="'cars-gearbox' + item.gearbox_id"
                                                     v-for="(item) in returnedGearboxes"><span>@{{ item.name }}</span></el-radio-button>
                                </el-radio-group>
                            </div>
                        </section>

                        <section class="order-body_up cars-engine-type" v-if="progress.markaProgress > 8" key="cars-modification">
                            <h2 class="order-body_title">Модификация</h2>
                            <div class="items-list">
                                <el-radio-group v-model="selectedModificationTypeId"
                                                v-on:change="selectModification()">
                                    <el-radio-button :label="item.modification_id"
                                                     name="carsModifications"
                                                     :key="'cars-modification' + item.modification_id"
                                                     v-for="(item) in returnedModifications">
                                        <div class="item-wrapper">
                                            <span class="item-horse-power">@{{ item.power }}</span>
                                            <span class="item-volume">@{{ item.motor }}</span>
                                            <span class="item-years">@{{ item.year_motor }}</span>
                                        </div>
                                    </el-radio-button>
z                                </el-radio-group>
                            </div>
                        </section>

                        <section class="order-body_btn" v-if="progress.markaProgress > 9">
                            <div class="button">
                                <el-button class="btn-red"
                                           {{--v-on:click.prevent="sendFinal"--}}
                                            v-on:click="goToStep(2)">ВНЕШНИЙ ВИД <i class="icon-arrow-right-long"></i></el-button>
                            </div>
                        </section>
                    </div>

                    <div v-if="progress.step === 2">
                        <section class="order-body_up cars-color" key="marka-color">
                            <h2 class="order-body_title">Выберите наиболее близкий цвет автомобиля</h2>
                            <car-colors :initial-colors="allColors"
                                        :initial-selected-color="selectedColor"
                                        v-on:cars-color-changed="carsColorChanged"></car-colors>

                        </section>
                        <section class="order-body_up cars-list" key="marka-photos">
                            <h2 class="order-body_title">Фотографии</h2>

                        </section>
                    </div>

                    <div v-if="progress.step === 3">
                        <section class="order-body_up cars-step3">
                            <div class="row col-3">
                                <div class="column">
                                    <h2 class="order-body_title">@{{ allComfort.name_ru }}</h2>
                                    <div class="form-group" v-for="(control, index) in allComfort.type_option">
                                        <template v-if="control === 'simp'">
                                            <el-select v-model="selectedComfort.value[index]"
                                                       :placeholder="allComfort.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-comfort-' + index + '-' + val"
                                                           v-for="val in allComfort.prop[index]"></el-option>
                                            </el-select>
                                        </template>
                                        <template v-if="control === 'check'">
                                            <el-checkbox v-model="selectedComfort.value[index]"
                                                         :true-label="allComfort.prop[index][0]"><span>@{{ allComplectation.prop[allComfort.prop[index][0]].name }}</span></el-checkbox>
                                        </template>
                                    </div>
                                </div>
                                <div class="column">
                                    <h2 class="order-body_title">@{{ allSaloonAndInteriors.name_ru }}</h2>
                                    <div class="form-group" v-for="(control, index) in allSaloonAndInteriors.type_option">
                                        <span v-if="control === 'simp'">
                                            <el-select v-model="selectedSaloonAndInteriors.value[index]"
                                                       :placeholder="allSaloonAndInteriors.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-saloon-and-interiors-' + index + '-' + val"
                                                           v-for="val in allSaloonAndInteriors.prop[index]"></el-option>
                                            </el-select>
                                        </span>
                                        <span v-if="control === 'check'">
                                            <el-checkbox v-model="selectedSaloonAndInteriors.value[index]"
                                                         :true-label="allSaloonAndInteriors.prop[index][0]"><span>@{{ allComplectation.prop[allSaloonAndInteriors.prop[index][0]].name }}</span></el-checkbox>
                                        </span>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="column-row">
                                        <h2 class="order-body_title">@{{ allAdjustingDriverSeat.name_ru }}</h2>
                                        <div class="form-group" v-for="(control, index) in allAdjustingDriverSeat.type_option">
                                        <span v-if="control === 'simp'">
                                            <el-select v-model="selectedAdjustingDriverSeat.value[index]"
                                                       :placeholder="allAdjustingDriverSeat.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-adjusting-driver-seat-' + index + '-' + val"
                                                           v-for="val in allAdjustingDriverSeat.prop[index]"></el-option>
                                            </el-select>
                                        </span>
                                            <span v-if="control === 'check'">
                                            <el-checkbox v-model="selectedAdjustingDriverSeat.value[index]"
                                                         :true-label="allAdjustingDriverSeat.prop[index][0]"><span>@{{ allComplectation.prop[allAdjustingDriverSeat.prop[index][0]].name }}</span></el-checkbox>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="column-row">
                                        <h2 class="order-body_title">@{{ allAlarmSystems.name_ru }}</h2>
                                        <div class="form-group" v-for="(control, index) in allAlarmSystems.type_option">
                                        <span v-if="control === 'simp'">
                                            <el-select v-model="selectedAlarmSystems.value[index]"
                                                       :placeholder="allAlarmSystems.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-alarm-system-' + index + '-' + val"
                                                           v-for="val in allAlarmSystems.prop[index]"></el-option>
                                            </el-select>
                                        </span>
                                            <span v-if="control === 'check'">
                                            <el-checkbox v-model="selectedAlarmSystems.value[index]"
                                                         :true-label="allAlarmSystems.prop[index][0]"><span>@{{ allComplectation.prop[allAlarmSystems.prop[index][0]].name }}</span></el-checkbox>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-1">
                                <div class="column">
                                    <h2 class="order-body_title">@{{ allSecurity.name_ru }}</h2>
                                    <div class="column-row">
                                        <div class="form-group" v-for="(control, index) in allSecurity.type_option">
                                            <span v-if="control === 'simp'">
                                                <el-select v-model="selectedSecurity.value[index]"
                                                           :placeholder="allSecurity.name_option_ru[index]">
                                                    <el-option :label="allComplectation.prop[val].name"
                                                               :value="val"
                                                               :key="'selected-security-' + index + '-' + val"
                                                               v-for="val in allSecurity.prop[index]"></el-option>
                                                </el-select>
                                            </span>
                                            <span v-if="control === 'check'">
                                                <el-checkbox v-model="selectedSecurity.value[index]"
                                                             :true-label="allSecurity.prop[index][0]"><span>@{{ allComplectation.prop[allSecurity.prop[index][0]].name }}</span></el-checkbox>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-3">
                                <div class="column">
                                    <h2 class="order-body_title">@{{ allExteriorFeatures.name_ru }}</h2>
                                    <div class="form-group" v-for="(control, index) in allExteriorFeatures.type_option">
                                        <span v-if="control === 'simp'">
                                            <el-select v-model="selectedExteriorFeatures.value[index]"
                                                       :placeholder="allExteriorFeatures.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-exterior-features-' + index + '-' + val"
                                                           v-for="val in allExteriorFeatures.prop[index]"></el-option>
                                            </el-select>
                                        </span>
                                        <span v-if="control === 'check'">
                                            <el-checkbox v-model="selectedExteriorFeatures.value[index]"
                                                         :true-label="allExteriorFeatures.prop[index][0]"><span>@{{ allComplectation.prop[allExteriorFeatures.prop[index][0]].name }}</span></el-checkbox>
                                        </span>
                                    </div>
                                </div>
                                <div class="column">
                                    <h2 class="order-body_title">@{{ allAdjustingPassengerSeat.name_ru }}</h2>
                                    <div class="form-group" v-for="(control, index) in allAdjustingPassengerSeat.type_option">
                                        <span v-if="control === 'simp'">
                                            <el-select v-model="selectedAdjustingPassengerSeat.value[index]"
                                                       :placeholder="allAdjustingPassengerSeat.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-adjusting-passenger-seat-' + index + '-' + val"
                                                           v-for="val in allAdjustingPassengerSeat.prop[index]"></el-option>
                                            </el-select>
                                        </span>
                                        <span v-if="control === 'check'">
                                            <el-checkbox v-model="selectedAdjustingPassengerSeat.value[index]"
                                                         :true-label="allAdjustingPassengerSeat.prop[index][0]"><span>@{{ allComplectation.prop[allAdjustingPassengerSeat.prop[index][0]].name }}</span></el-checkbox>
                                        </span>
                                    </div>
                                </div>
                                <div class="column">
                                    <h2 class="order-body_title">@{{ allHelpWithDrivingAndParking.name_ru }}</h2>
                                    <div class="form-group" v-for="(control, index) in allHelpWithDrivingAndParking.type_option">
                                        <span v-if="control === 'simp'">
                                            <el-select v-model="selectedHelpWithDrivingAndParking.value[index]"
                                                       :placeholder="allHelpWithDrivingAndParking.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-help-with-driving-and-parking-' + index + '-' + val"
                                                           v-for="val in allHelpWithDrivingAndParking.prop[index]"></el-option>
                                            </el-select>
                                        </span>
                                        <span v-if="control === 'check'">
                                            <el-checkbox v-model="selectedHelpWithDrivingAndParking.value[index]"
                                                         :true-label="allHelpWithDrivingAndParking.prop[index][0]"><span>@{{ allComplectation.prop[allHelpWithDrivingAndParking.prop[index][0]].name }}</span></el-checkbox>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-3">
                                <div class="column">
                                    <h2 class="order-body_title">@{{ allOverview.name_ru }}</h2>
                                    <div class="form-group" v-for="(control, index) in allOverview.type_option">
                                        <span v-if="control === 'simp'">
                                            <el-select v-model="selectedOverview.value[index]"
                                                       :placeholder="allOverview.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-overview-' + index + '-' + val"
                                                           v-for="val in allOverview.prop[index]"></el-option>
                                            </el-select>
                                        </span>
                                        <span v-if="control === 'check'">
                                            <el-checkbox v-model="selectedOverview.value[index]"
                                                         :true-label="allOverview.prop[index][0]"><span>@{{ allComplectation.prop[allOverview.prop[index][0]].name }}</span></el-checkbox>
                                        </span>
                                    </div>
                                </div>
                                <div class="column">
                                    <h2 class="order-body_title">@{{ allMultimedia.name_ru }}</h2>
                                    <div class="form-group" v-for="(control, index) in allMultimedia.type_option">
                                        <span v-if="control === 'simp'">
                                            <el-select v-model="selectedMultimedia.value[index]"
                                                       :placeholder="allMultimedia.name_option_ru[index]">
                                                <el-option :label="allComplectation.prop[val].name"
                                                           :value="val"
                                                           :key="'selected-multimedia-' + index + '-' + val"
                                                           v-for="val in allMultimedia.prop[index]"></el-option>
                                            </el-select>
                                        </span>
                                        <span v-if="control === 'check'">
                                            <el-checkbox v-model="selectedMultimedia.value[index]"
                                                         :true-label="allMultimedia.prop[index][0]"><span>@{{ allComplectation.prop[allMultimedia.prop[index][0]].name }}</span></el-checkbox>
                                        </span>
                                    </div>
                                </div>
                                <div class="column"></div>
                            </div>
                        </section>
                    </div>

                    <div v-if="progress.step === 4">
                        <section class="order-body_up cars-list" key="marka-">
                            <h2 class="order-body_title">Пробег</h2>

                        </section>
                    </div>

                    <div v-if="progress.step === 5">
                        <section class="order-body_up cars-list" key="marka-">
                            <h2 class="order-body_title">Регион продажи</h2>

                        </section>
                    </div>
                </transition>
            </div>
        </div>

    </div>
@stop