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
                        <div class="block-enter" v-if="passwordReset.progress === 1" v-cloak>
                            <form action="" v-on:submit.prevent="resetPassword($event)" novalidate autocomplete="off">
                                <div class="input-wr">
                                    <label for="" class="label"><span>@lang('v.email_phone')</span></label>
                                    <input type="text"
                                           v-validate="'required|max:125'"
                                           name="email_phone"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('email_phone') }"
                                           v-on:focus="removeError('email_phone'), removeServerError('passwordReset', 'emailPhone')"
                                           class="input"
                                           tabindex="1" />
                                    <span :class="{ error: errors.has('email_phone') }"
                                          v-if="errors.has('email_phone')"
                                          v-cloak>@lang('v.ent_em_pas')</span>
                                    <span class="error"
                                          v-if="hasServerErrors('passwordReset', 'emailPhone')"
                                          v-cloak
                                          v-text="getServerErrors('passwordReset', 'emailPhone')"></span>
                                </div>
                                <div class="submit">
{{--                                    <button tabindex="2"
                                            :disabled="passwordReset.isLoading">ВОССТАНОВИТЬ ПАРОЛЬ</button>--}}
                                    <el-button type="primary"
                                               native-type="submit"
                                               :loading="isLoading.passwordReset"
                                               :disabled="isLoading.passwordReset">@lang('v.res_pass')</el-button>
                                </div>
                            </form>
                        </div>
                        <div class="block-enter" v-if="passwordReset.progress === 2" v-cloak>
                            <p>@lang('v.link_pass') <strong>@lang('v.open_link')</strong> @lang('v.conf')</p>
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
