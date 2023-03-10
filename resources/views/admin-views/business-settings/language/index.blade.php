@extends('layouts.admin.app')

@section('title',translate('messages.language'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title mr-3">
            <span class="page-header-icon">
                <img src="{{ asset('assets/admin/img/business.png') }}" class="w--26" alt="">
            </span>
            <span>
                {{ translate('messages.business') }} {{ translate('messages.setup') }}
            </span>
        </h1>

        <div class="row">
            <div class="col-md-12">
                <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
                    <!-- Nav -->
                    <ul class="nav nav-tabs mb-5 mt-5 border-0 nav--tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.business-settings.business-setup',  ['tab' => 'business']) }}"   aria-disabled="true">{{translate('messages.business')}} {{translate('messages.settings')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.business-settings.business-setup',  ['tab' => 'customer']) }}"  aria-disabled="true">{{translate('messages.customers')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.business-settings.business-setup',  ['tab' => 'deliveryman']) }}"  aria-disabled="true">{{translate('messages.delivery')}} {{translate('messages.man')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/business-settings/language') ?'active':'' }}" href="{{route('admin.business-settings.language.index')}}"  aria-disabled="true">{{translate('messages.Languages')}}</a>
                        </li>
                    </ul>
                    <!-- End Nav -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Header -->
    <div class="card mb-3">
        <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger mb-3" role="alert">
                    {{translate('changing_some_settings_will_take_time_to_show_effect_please_clear_session_or_wait_for_60_minutes_else_browse_from_incognito_mode')}}
                </div>

                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 d-flex">
                                    {{translate('language_table')}}
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <div class="d-flex gap-10 justify-content-sm-end">
                                    <button class="btn btn--primary btn-icon-split" data-toggle="modal" data-target="#lang-modal">
                                        <i class="tio-add"></i>
                                        <span class="text">{{translate('add_new_language')}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom" id="table-div">
                        <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                "columnDefs": [{
                                    "targets": [],
                                    "width": "5%",
                                    "orderable": false
                                }],
                                "order": [],
                                "info": {
                                "totalQty": "#datatableWithPaginationInfoTotalQty"
                                },

                                "entries": "#datatableEntries",

                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging":false
                            }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{ translate('SL')}}</th>
                                <th>{{translate('Id')}}</th>
                                <th>{{translate('Code')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('default')}} {{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($language=App\Models\BusinessSetting::where('key','system_language')->first())
                            @if($language=$language)
                            @foreach(json_decode($language['value'],true) as $key =>$data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data['id']}}</td>
                                    <td>{{$data['code']}}</td>
                                    <td>
                                        {{-- <label class="switcher mx-auto">
                                            <input type="checkbox"
                                                    onclick="updateStatus('{{route('admin.business-settings.language.update-status')}}','{{$data['code']}}')"
                                                    class="switcher_input" {{$data['status']==1?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label> --}}
                                        @if ($data['default']==true)
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$data['id']}}">
                                            <input type="checkbox" onclick="updateLangStatus()" class="toggle-switch-input" id="stocksCheckbox{{$data['id']}}" {{$data['status']==1?'checked':''}} disabled>
                                            <span class="toggle-switch-label mx-auto">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        @else
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$data['id']}}">
                                            <input type="checkbox" onclick="updateStatus('{{route('admin.business-settings.language.update-status')}}','{{$data['code']}}')" class="toggle-switch-input" id="stocksCheckbox{{$data['id']}}" {{$data['status']==1?'checked':''}}>
                                            <span class="toggle-switch-label mx-auto">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- <label class="switcher mx-auto">
                                            <input type="checkbox"
                                                    onclick="window.location.href ='{{route('admin.business-settings.language.update-default-status', ['code'=>$data['code']])}}'"
                                                    class="switcher_input" {{ ((array_key_exists('default', $data) && $data['default']==true) ? 'checked': ((array_key_exists('default', $data) && $data['default']==false) ? '' : 'disabled')) }}>
                                            <span class="switcher_control"></span>
                                        </label> --}}
                                        <label class="toggle-switch toggle-switch-sm" for="defaultCheck{{$data['id']}}">
                                            <input type="checkbox" onclick="window.location.href ='{{route('admin.business-settings.language.update-default-status', ['code'=>$data['code']])}}'" class="toggle-switch-input" id="defaultCheck{{$data['id']}}" {{ ((array_key_exists('default', $data) && $data['default']==true) ? 'checked': ((array_key_exists('default', $data) && $data['default']==false) ? '' : 'disabled')) }}>
                                            <span class="toggle-switch-label mx-auto">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-seconary btn-sm dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if($data['code']!='en')
                                                    <a class="dropdown-item" data-toggle="modal"
                                                        data-target="#lang-modal-update-{{$data['code']}}">{{translate('update')}}</a>
                                                    @if ($data['default']==true)
                                                    {{-- <a class="dropdown-item"
                                                    href="javascript:" onclick="default_language_delete_alert()">{{translate('Delete')}}</a> --}}
                                                    @else
                                                        <a class="dropdown-item delete"
                                                            id="{{route('admin.business-settings.language.delete',[$data['code']])}}">{{translate('Delete')}}</a>

                                                    @endif
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{route('admin.business-settings.language.translate',[$data['code']])}}">{{translate('Translate')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>

        <div class="modal fade" id="lang-modal" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{translate('new_language')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('admin.business-settings.language.add-new')}}" method="post"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message-text"
                                               class="col-form-label">{{translate('language')}}</label>
                                               <select name="code" class="form-control js-select2-custom">
                                                {{-- <option value="en">English</option> --}}
                                                <option value="af">Afrikaans</option>
                                                <option value="sq">Albanian - shqip</option>
                                                <option value="am">Amharic - ????????????</option>
                                                <option value="ar">Arabic - ??????????????</option>
                                                <option value="an">Aragonese - aragon??s</option>
                                                <option value="hy">Armenian - ??????????????</option>
                                                <option value="ast">Asturian - asturianu</option>
                                                <option value="az">Azerbaijani - az??rbaycan dili</option>
                                                <option value="eu">Basque - euskara</option>
                                                <option value="be">Belarusian - ????????????????????</option>
                                                <option value="bn">Bengali - ???????????????</option>
                                                <option value="bs">Bosnian - bosanski</option>
                                                <option value="br">Breton - brezhoneg</option>
                                                <option value="bg">Bulgarian - ??????????????????</option>
                                                <option value="ca">Catalan - catal??</option>
                                                <option value="ckb">Central Kurdish - ?????????? (???????????????? ????????????)</option>
                                                <option value="zh">Chinese - ??????</option>
                                                <option value="zh-HK">Chinese (Hong Kong) - ??????????????????</option>
                                                <option value="zh-CN">Chinese (Simplified) - ??????????????????</option>
                                                <option value="zh-TW">Chinese (Traditional) - ??????????????????</option>
                                                <option value="co">Corsican</option>
                                                <option value="hr">Croatian - hrvatski</option>
                                                <option value="cs">Czech - ??e??tina</option>
                                                <option value="da">Danish - dansk</option>
                                                <option value="nl">Dutch - Nederlands</option>
                                                <option value="en-AU">English (Australia)</option>
                                                <option value="en-CA">English (Canada)</option>
                                                <option value="en-IN">English (India)</option>
                                                <option value="en-NZ">English (New Zealand)</option>
                                                <option value="en-ZA">English (South Africa)</option>
                                                <option value="en-GB">English (United Kingdom)</option>
                                                <option value="en-US">English (United States)</option>
                                                <option value="eo">Esperanto - esperanto</option>
                                                <option value="et">Estonian - eesti</option>
                                                <option value="fo">Faroese - f??royskt</option>
                                                <option value="fil">Filipino</option>
                                                <option value="fi">Finnish - suomi</option>
                                                <option value="fr">French - fran??ais</option>
                                                <option value="fr-CA">French (Canada) - fran??ais (Canada)</option>
                                                <option value="fr-FR">French (France) - fran??ais (France)</option>
                                                <option value="fr-CH">French (Switzerland) - fran??ais (Suisse)</option>
                                                <option value="gl">Galician - galego</option>
                                                <option value="ka">Georgian - ?????????????????????</option>
                                                <option value="de">German - Deutsch</option>
                                                <option value="de-AT">German (Austria) - Deutsch (??sterreich)</option>
                                                <option value="de-DE">German (Germany) - Deutsch (Deutschland)</option>
                                                <option value="de-LI">German (Liechtenstein) - Deutsch (Liechtenstein)
                                                </option>
                                                <option value="de-CH">German (Switzerland) - Deutsch (Schweiz)</option>
                                                <option value="el">Greek - ????????????????</option>
                                                <option value="gn">Guarani</option>
                                                <option value="gu">Gujarati - ?????????????????????</option>
                                                <option value="ha">Hausa</option>
                                                <option value="haw">Hawaiian - ????lelo Hawai??i</option>
                                                <option value="he">Hebrew - ??????????</option>
                                                <option value="hi">Hindi - ??????????????????</option>
                                                <option value="hu">Hungarian - magyar</option>
                                                <option value="is">Icelandic - ??slenska</option>
                                                <option value="id">Indonesian - Indonesia</option>
                                                <option value="ia">Interlingua</option>
                                                <option value="ga">Irish - Gaeilge</option>
                                                <option value="it">Italian - italiano</option>
                                                <option value="it-IT">Italian (Italy) - italiano (Italia)</option>
                                                <option value="it-CH">Italian (Switzerland) - italiano (Svizzera)</option>
                                                <option value="ja">Japanese - ?????????</option>
                                                <option value="kn">Kannada - ???????????????</option>
                                                <option value="kk">Kazakh - ?????????? ????????</option>
                                                <option value="km">Khmer - ???????????????</option>
                                                <option value="ko">Korean - ?????????</option>
                                                <option value="ku">Kurdish - Kurd??</option>
                                                <option value="ky">Kyrgyz - ????????????????</option>
                                                <option value="lo">Lao - ?????????</option>
                                                <option value="la">Latin</option>
                                                <option value="lv">Latvian - latvie??u</option>
                                                <option value="ln">Lingala - ling??la</option>
                                                <option value="lt">Lithuanian - lietuvi??</option>
                                                <option value="mk">Macedonian - ????????????????????</option>
                                                <option value="ms">Malay - Bahasa Melayu</option>
                                                <option value="ml">Malayalam - ??????????????????</option>
                                                <option value="mt">Maltese - Malti</option>
                                                <option value="mr">Marathi - ???????????????</option>
                                                <option value="mn">Mongolian - ????????????</option>
                                                <option value="ne">Nepali - ??????????????????</option>
                                                <option value="no">Norwegian - norsk</option>
                                                <option value="nb">Norwegian Bokm??l - norsk bokm??l</option>
                                                <option value="nn">Norwegian Nynorsk - nynorsk</option>
                                                <option value="oc">Occitan</option>
                                                <option value="or">Oriya - ???????????????</option>
                                                <option value="om">Oromo - Oromoo</option>
                                                <option value="ps">Pashto - ????????</option>
                                                <option value="fa">Persian - ??????????</option>
                                                <option value="pl">Polish - polski</option>
                                                <option value="pt">Portuguese - portugu??s</option>
                                                <option value="pt-BR">Portuguese (Brazil) - portugu??s (Brasil)</option>
                                                <option value="pt-PT">Portuguese (Portugal) - portugu??s (Portugal)</option>
                                                <option value="pa">Punjabi - ??????????????????</option>
                                                <option value="qu">Quechua</option>
                                                <option value="ro">Romanian - rom??n??</option>
                                                <option value="mo">Romanian (Moldova) - rom??n?? (Moldova)</option>
                                                <option value="rm">Romansh - rumantsch</option>
                                                <option value="ru">Russian - ??????????????</option>
                                                <option value="gd">Scottish Gaelic</option>
                                                <option value="sr">Serbian - ????????????</option>
                                                <option value="sh">Serbo-Croatian - Srpskohrvatski</option>
                                                <option value="sn">Shona - chiShona</option>
                                                <option value="sd">Sindhi</option>
                                                <option value="si">Sinhala - ???????????????</option>
                                                <option value="sk">Slovak - sloven??ina</option>
                                                <option value="sl">Slovenian - sloven????ina</option>
                                                <option value="so">Somali - Soomaali</option>
                                                <option value="st">Southern Sotho</option>
                                                <option value="es">Spanish - espa??ol</option>
                                                <option value="es-AR">Spanish (Argentina) - espa??ol (Argentina)</option>
                                                <option value="es-419">Spanish (Latin America) - espa??ol (Latinoam??rica)
                                                </option>
                                                <option value="es-MX">Spanish (Mexico) - espa??ol (M??xico)</option>
                                                <option value="es-ES">Spanish (Spain) - espa??ol (Espa??a)</option>
                                                <option value="es-US">Spanish (United States) - espa??ol (Estados Unidos)
                                                </option>
                                                <option value="su">Sundanese</option>
                                                <option value="sw">Swahili - Kiswahili</option>
                                                <option value="sv">Swedish - svenska</option>
                                                <option value="tg">Tajik - ????????????</option>
                                                <option value="ta">Tamil - ???????????????</option>
                                                <option value="tt">Tatar</option>
                                                <option value="te">Telugu - ??????????????????</option>
                                                <option value="th">Thai - ?????????</option>
                                                <option value="ti">Tigrinya - ????????????</option>
                                                <option value="to">Tongan - lea fakatonga</option>
                                                <option value="tr">Turkish - T??rk??e</option>
                                                <option value="tk">Turkmen</option>
                                                <option value="tw">Twi</option>
                                                <option value="uk">Ukrainian - ????????????????????</option>
                                                <option value="ur">Urdu - ????????</option>
                                                <option value="ug">Uyghur</option>
                                                <option value="uz">Uzbek - o???zbek</option>
                                                <option value="vi">Vietnamese - Ti???ng Vi???t</option>
                                                <option value="wa">Walloon - wa</option>
                                                <option value="cy">Welsh - Cymraeg</option>
                                                <option value="fy">Western Frisian</option>
                                                <option value="xh">Xhosa</option>
                                                <option value="yi">Yiddish</option>
                                                <option value="yo">Yoruba - ??d?? Yor??b??</option>
                                                <option value="zu">Zulu - isiZulu</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label">{{translate('direction')}} :</label>
                                        <select class="form-control" name="direction">
                                            <option value="ltr">LTR</option>
                                            <option value="rtl">RTL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('Add')}} <i
                                    class="fa fa-plus"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if ($language)

        @foreach(json_decode($language['value'],true) as $key =>$data)
            <div class="modal fade" id="lang-modal-update-{{$data['code']}}" tabindex="-1" role="dialog"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{translate('new_language')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('admin.business-settings.language.update')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="message-text"
                                                   class="col-form-label">{{translate('language')}}</label>
                                                   <select name="code" id="lang_code" class="form-control js-select2-custom" disabled>
                                                    {{-- <option value="en" {{ $data['code']== 'en'?'selected':'' }}>English</option> --}}
                                                    <option value="af" {{ $data['code']== 'af'?'selected':'' }}>Afrikaans</option>
                                                    <option value="sq" {{ $data['code']== 'sq'?'selected':'' }}>Albanian - shqip</option>
                                                    <option value="am" {{ $data['code']== 'am'?'selected':'' }}>Amharic - ????????????</option>
                                                    <option value="ar" {{ $data['code']== 'ar'?'selected':'' }}>Arabic - ??????????????</option>
                                                    <option value="an" {{ $data['code']== 'an'?'selected':'' }}>Aragonese - aragon??s</option>
                                                    <option value="hy" {{ $data['code']== 'hy'?'selected':'' }}>Armenian - ??????????????</option>
                                                    <option value="ast" {{ $data['code']== 'ast'?'selected':'' }}>Asturian - asturianu</option>
                                                    <option value="az" {{ $data['code']== 'az'?'selected':'' }}>Azerbaijani - az??rbaycan dili</option>
                                                    <option value="eu" {{ $data['code']== 'eu'?'selected':'' }}>Basque - euskara</option>
                                                    <option value="be" {{ $data['code']== 'be'?'selected':'' }}>Belarusian - ????????????????????</option>
                                                    <option value="bn" {{ $data['code']== 'bn'?'selected':'' }}>Bengali - ???????????????</option>
                                                    <option value="bs" {{ $data['code']== 'bs'?'selected':'' }}>Bosnian - bosanski</option>
                                                    <option value="br" {{ $data['code']== 'br'?'selected':'' }}>Breton - brezhoneg</option>
                                                    <option value="bg" {{ $data['code']== 'bg'?'selected':'' }}>Bulgarian - ??????????????????</option>
                                                    <option value="ca" {{ $data['code']== 'ca'?'selected':'' }}>Catalan - catal??</option>
                                                    <option value="ckb" {{ $data['code']== 'ckb'?'selected':'' }}>Central Kurdish - ?????????? (???????????????? ????????????)</option>
                                                    <option value="zh" {{ $data['code']== 'zh'?'selected':'' }}>Chinese - ??????</option>
                                                    <option value="zh-HK" {{ $data['code']== 'zh-HK'?'selected':'' }}>Chinese (Hong Kong) - ??????????????????</option>
                                                    <option value="zh-CN" {{ $data['code']== 'zh-CN'?'selected':'' }}>Chinese (Simplified) - ??????????????????</option>
                                                    <option value="zh-TW" {{ $data['code']== 'zh-TW'?'selected':'' }}>Chinese (Traditional) - ??????????????????</option>
                                                    <option value="co" {{ $data['code']== 'co'?'selected':'' }}>Corsican</option>
                                                    <option value="hr" {{ $data['code']== 'hr'?'selected':'' }}>Croatian - hrvatski</option>
                                                    <option value="cs" {{ $data['code']== 'cs'?'selected':'' }}>Czech - ??e??tina</option>
                                                    <option value="da" {{ $data['code']== 'da'?'selected':'' }}>Danish - dansk</option>
                                                    <option value="nl" {{ $data['code']== 'nl'?'selected':'' }}>Dutch - Nederlands</option>
                                                    <option value="en-AU" {{ $data['code']== 'en-AU'?'selected':'' }}>English (Australia)</option>
                                                    <option value="en-CA" {{ $data['code']== 'en-CA'?'selected':'' }}>English (Canada)</option>
                                                    <option value="en-IN" {{ $data['code']== 'en-IN'?'selected':'' }}>English (India)</option>
                                                    <option value="en-NZ" {{ $data['code']== 'en-NZ'?'selected':'' }}>English (New Zealand)</option>
                                                    <option value="en-ZA" {{ $data['code']== 'en-ZA'?'selected':'' }}>English (South Africa)</option>
                                                    <option value="en-GB" {{ $data['code']== 'en-GB'?'selected':'' }}>English (United Kingdom)</option>
                                                    <option value="en-US" {{ $data['code']== 'en-US'?'selected':'' }}>English (United States)</option>
                                                    <option value="eo" {{ $data['code']== 'eo'?'selected':'' }}>Esperanto - esperanto</option>
                                                    <option value="et" {{ $data['code']== 'et'?'selected':'' }}>Estonian - eesti</option>
                                                    <option value="fo" {{ $data['code']== 'fo'?'selected':'' }}>Faroese - f??royskt</option>
                                                    <option value="fil" {{ $data['code']== 'fil'?'selected':'' }}>Filipino</option>
                                                    <option value="fi" {{ $data['code']== 'fi'?'selected':'' }}>Finnish - suomi</option>
                                                    <option value="fr" {{ $data['code']== 'fr'?'selected':'' }}>French - fran??ais</option>
                                                    <option value="fr-CA" {{ $data['code']== 'fr-CA'?'selected':'' }}>French (Canada) - fran??ais (Canada)</option>
                                                    <option value="fr-FR" {{ $data['code']== 'fr-FR'?'selected':'' }}>French (France) - fran??ais (France)</option>
                                                    <option value="fr-CH" {{ $data['code']== 'fr-CH'?'selected':'' }}>French (Switzerland) - fran??ais (Suisse)</option>
                                                    <option value="gl" {{ $data['code']== 'gl'?'selected':'' }}>Galician - galego</option>
                                                    <option value="ka" {{ $data['code']== 'ka'?'selected':'' }}>Georgian - ?????????????????????</option>
                                                    <option value="de" {{ $data['code']== 'de'?'selected':'' }}>German - Deutsch</option>
                                                    <option value="de-AT" {{ $data['code']== 'de-AT'?'selected':'' }}>German (Austria) - Deutsch (??sterreich)</option>
                                                    <option value="de-DE" {{ $data['code']== 'de-DE'?'selected':'' }}>German (Germany) - Deutsch (Deutschland)</option>
                                                    <option value="de-LI" {{ $data['code']== 'de-LI'?'selected':'' }}>German (Liechtenstein) - Deutsch (Liechtenstein)
                                                    </option>
                                                    <option value="de-CH" {{ $data['code']== 'de-CH'?'selected':'' }}>German (Switzerland) - Deutsch (Schweiz)</option>
                                                    <option value="el" {{ $data['code']== 'el'?'selected':'' }}>Greek - ????????????????</option>
                                                    <option value="gn" {{ $data['code']== 'gn'?'selected':'' }}>Guarani</option>
                                                    <option value="gu" {{ $data['code']== 'gu'?'selected':'' }}>Gujarati - ?????????????????????</option>
                                                    <option value="ha" {{ $data['code']== 'ha'?'selected':'' }}>Hausa</option>
                                                    <option value="haw" {{ $data['code']== 'haw'?'selected':'' }}>Hawaiian - ????lelo Hawai??i</option>
                                                    <option value="he" {{ $data['code']== 'he'?'selected':'' }}>Hebrew - ??????????</option>
                                                    <option value="hi" {{ $data['code']== 'hi'?'selected':'' }}>Hindi - ??????????????????</option>
                                                    <option value="hu" {{ $data['code']== 'hu'?'selected':'' }}>Hungarian - magyar</option>
                                                    <option value="is" {{ $data['code']== 'is'?'selected':'' }}>Icelandic - ??slenska</option>
                                                    <option value="id" {{ $data['code']== 'id'?'selected':'' }}>Indonesian - Indonesia</option>
                                                    <option value="ia" {{ $data['code']== 'ia'?'selected':'' }}>Interlingua</option>
                                                    <option value="ga" {{ $data['code']== 'ga'?'selected':'' }}>Irish - Gaeilge</option>
                                                    <option value="it" {{ $data['code']== 'it'?'selected':'' }}>Italian - italiano</option>
                                                    <option value="it-IT" {{ $data['code']== 'it-IT'?'selected':'' }}>Italian (Italy) - italiano (Italia)</option>
                                                    <option value="it-CH" {{ $data['code']== 'it-CH'?'selected':'' }}>Italian (Switzerland) - italiano (Svizzera)</option>
                                                    <option value="ja" {{ $data['code']== 'ja'?'selected':'' }}>Japanese - ?????????</option>
                                                    <option value="kn" {{ $data['code']== 'kn'?'selected':'' }}>Kannada - ???????????????</option>
                                                    <option value="kk" {{ $data['code']== 'kk'?'selected':'' }}>Kazakh - ?????????? ????????</option>
                                                    <option value="km" {{ $data['code']== 'km'?'selected':'' }}>Khmer - ???????????????</option>
                                                    <option value="ko" {{ $data['code']== 'ko'?'selected':'' }}>Korean - ?????????</option>
                                                    <option value="ku" {{ $data['code']== 'ku'?'selected':'' }}>Kurdish - Kurd??</option>
                                                    <option value="ky" {{ $data['code']== 'ky'?'selected':'' }}>Kyrgyz - ????????????????</option>
                                                    <option value="lo" {{ $data['code']== 'lo'?'selected':'' }}>Lao - ?????????</option>
                                                    <option value="la" {{ $data['code']== 'la'?'selected':'' }}>Latin</option>
                                                    <option value="lv" {{ $data['code']== 'lv'?'selected':'' }}>Latvian - latvie??u</option>
                                                    <option value="ln" {{ $data['code']== 'ln'?'selected':'' }}>Lingala - ling??la</option>
                                                    <option value="lt" {{ $data['code']== 'lt'?'selected':'' }}>Lithuanian - lietuvi??</option>
                                                    <option value="mk" {{ $data['code']== 'mk'?'selected':'' }}>Macedonian - ????????????????????</option>
                                                    <option value="ms" {{ $data['code']== 'ms'?'selected':'' }}>Malay - Bahasa Melayu</option>
                                                    <option value="ml" {{ $data['code']== 'ml'?'selected':'' }}>Malayalam - ??????????????????</option>
                                                    <option value="mt" {{ $data['code']== 'mt'?'selected':'' }}>Maltese - Malti</option>
                                                    <option value="mr" {{ $data['code']== 'mr'?'selected':'' }}>Marathi - ???????????????</option>
                                                    <option value="mn" {{ $data['code']== 'mn'?'selected':'' }}>Mongolian - ????????????</option>
                                                    <option value="ne" {{ $data['code']== 'ne'?'selected':'' }}>Nepali - ??????????????????</option>
                                                    <option value="no" {{ $data['code']== 'no'?'selected':'' }}>Norwegian - norsk</option>
                                                    <option value="nb" {{ $data['code']== 'nb'?'selected':'' }}>Norwegian Bokm??l - norsk bokm??l</option>
                                                    <option value="nn" {{ $data['code']== 'nn'?'selected':'' }}>Norwegian Nynorsk - nynorsk</option>
                                                    <option value="oc" {{ $data['code']== 'oc'?'selected':'' }}>Occitan</option>
                                                    <option value="or" {{ $data['code']== 'or'?'selected':'' }}>Oriya - ???????????????</option>
                                                    <option value="om" {{ $data['code']== 'om'?'selected':'' }}>Oromo - Oromoo</option>
                                                    <option value="ps" {{ $data['code']== 'ps'?'selected':'' }}>Pashto - ????????</option>
                                                    <option value="fa" {{ $data['code']== 'fa'?'selected':'' }}>Persian - ??????????</option>
                                                    <option value="pl" {{ $data['code']== 'pl'?'selected':'' }}>Polish - polski</option>
                                                    <option value="pt" {{ $data['code']== 'pt'?'selected':'' }}>Portuguese - portugu??s</option>
                                                    <option value="pt-BR" {{ $data['code']== 'pt-BR'?'selected':'' }}>Portuguese (Brazil) - portugu??s (Brasil)</option>
                                                    <option value="pt-PT" {{ $data['code']== 'pt-PT'?'selected':'' }}>Portuguese (Portugal) - portugu??s (Portugal)</option>
                                                    <option value="pa" {{ $data['code']== 'pa'?'selected':'' }}>Punjabi - ??????????????????</option>
                                                    <option value="qu" {{ $data['code']== 'qu'?'selected':'' }}>Quechua</option>
                                                    <option value="ro" {{ $data['code']== 'ro'?'selected':'' }}>Romanian - rom??n??</option>
                                                    <option value="mo" {{ $data['code']== 'mo'?'selected':'' }}>Romanian (Moldova) - rom??n?? (Moldova)</option>
                                                    <option value="rm" {{ $data['code']== 'rm'?'selected':'' }}>Romansh - rumantsch</option>
                                                    <option value="ru" {{ $data['code']== 'ru'?'selected':'' }}>Russian - ??????????????</option>
                                                    <option value="gd" {{ $data['code']== 'gd'?'selected':'' }}>Scottish Gaelic</option>
                                                    <option value="sr" {{ $data['code']== 'sr'?'selected':'' }}>Serbian - ????????????</option>
                                                    <option value="sh" {{ $data['code']== 'sh'?'selected':'' }}>Serbo-Croatian - Srpskohrvatski</option>
                                                    <option value="sn" {{ $data['code']== 'sn'?'selected':'' }}>Shona - chiShona</option>
                                                    <option value="sd" {{ $data['code']== 'sd'?'selected':'' }}>Sindhi</option>
                                                    <option value="si" {{ $data['code']== 'si'?'selected':'' }}>Sinhala - ???????????????</option>
                                                    <option value="sk" {{ $data['code']== 'sk'?'selected':'' }}>Slovak - sloven??ina</option>
                                                    <option value="sl" {{ $data['code']== 'sl'?'selected':'' }}>Slovenian - sloven????ina</option>
                                                    <option value="so" {{ $data['code']== 'so'?'selected':'' }}>Somali - Soomaali</option>
                                                    <option value="st" {{ $data['code']== 'st'?'selected':'' }}>Southern Sotho</option>
                                                    <option value="es" {{ $data['code']== 'es'?'selected':'' }}>Spanish - espa??ol</option>
                                                    <option value="es-AR" {{ $data['code']== 'es-AR'?'selected':'' }}>Spanish (Argentina) - espa??ol (Argentina)</option>
                                                    <option value="es-419" {{ $data['code']== 'es-419'?'selected':'' }}>Spanish (Latin America) - espa??ol (Latinoam??rica)
                                                    </option>
                                                    <option value="es-MX" {{ $data['code']== 'es-MX'?'selected':'' }}>Spanish (Mexico) - espa??ol (M??xico)</option>
                                                    <option value="es-ES" {{ $data['code']== 'es-ES'?'selected':'' }}>Spanish (Spain) - espa??ol (Espa??a)</option>
                                                    <option value="es-US" {{ $data['code']== 'es-US'?'selected':'' }}>Spanish (United States) - espa??ol (Estados Unidos)
                                                    </option>
                                                    <option value="su" {{ $data['code']== 'su'?'selected':'' }}>Sundanese</option>
                                                    <option value="sw" {{ $data['code']== 'sw'?'selected':'' }}>Swahili - Kiswahili</option>
                                                    <option value="sv" {{ $data['code']== 'sv'?'selected':'' }}>Swedish - svenska</option>
                                                    <option value="tg" {{ $data['code']== 'tg'?'selected':'' }}>Tajik - ????????????</option>
                                                    <option value="ta" {{ $data['code']== 'ta'?'selected':'' }}>Tamil - ???????????????</option>
                                                    <option value="tt" {{ $data['code']== 'tt'?'selected':'' }}>Tatar</option>
                                                    <option value="te" {{ $data['code']== 'te'?'selected':'' }}>Telugu - ??????????????????</option>
                                                    <option value="th" {{ $data['code']== 'th'?'selected':'' }}>Thai - ?????????</option>
                                                    <option value="ti" {{ $data['code']== 'ti'?'selected':'' }}>Tigrinya - ????????????</option>
                                                    <option value="to" {{ $data['code']== 'to'?'selected':'' }}>Tongan - lea fakatonga</option>
                                                    <option value="tr" {{ $data['code']== 'tr'?'selected':'' }}>Turkish - T??rk??e</option>
                                                    <option value="tk" {{ $data['code']== 'tk'?'selected':'' }}>Turkmen</option>
                                                    <option value="tw" {{ $data['code']== 'tw'?'selected':'' }}>Twi</option>
                                                    <option value="uk" {{ $data['code']== 'uk'?'selected':'' }}>Ukrainian - ????????????????????</option>
                                                    <option value="ur" {{ $data['code']== 'ur'?'selected':'' }}>Urdu - ????????</option>
                                                    <option value="ug" {{ $data['code']== 'ug'?'selected':'' }}>Uyghur</option>
                                                    <option value="uz" {{ $data['code']== 'uz'?'selected':'' }}>Uzbek - o???zbek</option>
                                                    <option value="vi" {{ $data['code']== 'vi'?'selected':'' }}>Vietnamese - Ti???ng Vi???t</option>
                                                    <option value="wa" {{ $data['code']== 'wa'?'selected':'' }}>Walloon - wa</option>
                                                    <option value="cy" {{ $data['code']== 'cy'?'selected':'' }}>Welsh - Cymraeg</option>
                                                    <option value="fy" {{ $data['code']== 'fy'?'selected':'' }}>Western Frisian</option>
                                                    <option value="xh" {{ $data['code']== 'xh'?'selected':'' }}>Xhosa</option>
                                                    <option value="yi" {{ $data['code']== 'yi'?'selected':'' }}>Yiddish</option>
                                                    <option value="yo" {{ $data['code']== 'yo'?'selected':'' }}>Yoruba - ??d?? Yor??b??</option>
                                                    <option value="zu" {{ $data['code']== 'zu'?'selected':'' }}>Zulu - isiZulu</option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="col-form-label">{{translate('direction')}} :</label>
                                            <select class="form-control" name="direction">
                                                <option
                                                    value="ltr" {{isset($data['direction'])?$data['direction']=='ltr'?'selected':'':''}}>
                                                    LTR
                                                </option>
                                                <option
                                                    value="rtl" {{isset($data['direction'])?$data['direction']=='rtl'?'selected':'':''}}>
                                                    RTL
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('update')}} <i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        @endif
    </div>
@endsection

@push('script_2')
    <!-- Page level custom scripts -->
    <script>

        function updateStatus(route, code) {
            $.get({
                url: route,
                data: {
                    code: code,
                },
                success: function (data) {
                    toastr.success('{{translate('status_updated_successfully')}}');
                }
            });
        }
    </script>

    <script>

            // color select select2
            $('.country-var-select').select2({
                templateResult: codeSelect,
                templateSelection: codeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function codeSelect(state) {
                var code = state.title;
                if (!code) return state.text;
                return "<img class='image-preview' src='" + code + "'>" + state.text;
            }

    </script>

    <script>

            $(".delete").click(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: '{{translate('Are you sure to delete this')}}?',
                    text: "{{translate('You will not be able to revert this')}}!",
                    showCancelButton: true,
                    confirmButtonColor: 'primary',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{translate('Yes')}}, {{translate('delete it')}}!'
                }).then((result) => {
                    if (result.value) {
                        window.location.href = $(this).attr("id");
                    }
                })
            });


    </script>
    <script>
        function default_language_delete_alert()
        {
            toastr.warning('{{translate('default language can not be deleted! to delete change the default language first!')}}');
        }
    </script>
    <script>
        function updateLangStatus()
        {
            toastr.warning('{{translate('default language can not be updated! to update change the default language first!')}}');
        }
    </script>
@endpush
