@extends('front/layout/layout')
@section('meta')
@stop
@section('content')
    <div id="page-login">
        <div class="modal-mask modal-auth modal-reset-password">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-body">
                        <h2 class="title">@lang('v.reseat_pass')</h2>
                        <div class="block-enter" v-if="passwordResetStr.progress === 1" v-cloak>
                            <form v-on:submit.prevent="resetPasswordStr($event)" novalidate autocomplete="off" method="POST">
                                <div class="input-wr">
                                    <label for="" class="label"><span>@lang('v.enter_new_pass')</span></label>
                                    <input type="password"
                                           v-validate="'required|min:6|max:125'"
                                           name="new_password"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('new_password') }"
                                           v-on:focus="removeError('new_password'), removeServerError('passwordResetStr', 'password')"
                                           class="input"
                                           tabindex="1" />
                                    <span :class="{ error: errors.has('new_password') }"
                                          v-if="errors.has('new_password')"
                                          v-cloak>@lang('v.enter_new_pass')ь</span>
                                </div>
                                {{csrf_field()}}
                                <div class="input-wr">
                                    <label for="" class="label"><span>@lang('v.repeat_enter_new_pass')</span></label>
                                    <input type="password"
                                           v-validate="'required|min:6|max:125'"
                                           name="re_new_password"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('re_new_password') }"
                                           v-on:focus="removeError('re_new_password'), removeServerError('passwordResetStr', 'password')"
                                           class="input"
                                           tabindex="2" />
                                    <span :class="{ error: errors.has('re_new_password') }"
                                          v-if="errors.has('re_new_password')"
                                          v-cloak>@lang('v.er_enter_new_pass')</span>
                                    <span class="error"
                                          v-if="hasServerErrors('passwordResetStr', 'password')"
                                          v-cloak
                                          v-text="getServerErrors('passwordResetStr', 'password')"></span>
                                </div>
                                <div class="submit">
                                    {{--<button tabindex="3"
                                            :disabled="passwordResetStr.isLoading">ПОДТВЕРДИТЬ</button>--}}
                                    <el-button type="primary"
                                               native-type="submit"
                                               tabindex="3"
                                               :loading="isLoading.passwordResetStr"
                                               :disabled="isLoading.passwordResetStr">@lang('v.confirm')</el-button>
                                </div>
                            </form>
                        </div>
                        <div class="block-enter" v-if="passwordResetStr.progress === 2" v-cloak>
                            <p>@lang('v.new') <strong>@lang('v.success_install')</strong>. @lang('v.to_home_res').</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="container">
        <form class="form-horizontal" role="form" method="POST" action="">
            {{ csrf_field() }}

            <input type="text" name="value" placeholder="Introdu Adresa de email sau telefonul">

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Send Password Reset Link
                    </button>
                </div>
            </div>
        </form>
    </div>--}}
@endsection
