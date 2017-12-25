@extends('layouts.app')


@section('styles')
<style>
    .dropdown-menu {
        min-width: 0 !important
    }
    .chosen-container {
        font-size: 18px !important
    }
    .chosen-single {
        height: 70px !important;
        line-height: 70px !important;
        background: none !important;
        text-align: center
    }
    #tokens_ssp_table tfoot {
        display: table-header-group;
    }
</style>
@endsection


@section('content')

    <!-- begin header -->
    @section('page-name') Токены @endsection
    @include('layouts.headers.home')
    <!-- end header -->


    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <!-- begin items -->
            <div class="items">

                @if (Auth::user()->status !== 'accountant')
                <!-- begin items__add -->
                <div class="items__add">
                    <form class="form" method="POST" action="{{ url('/home/tokens') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить токен</h2>
                        </header>

                        <div class="form__item{{ $errors->has('card') ? ' form__item--error' : '' }}">
                            <label for="card">Карта</label>
                            <select name="card" id="card" class="chosen-js-select">
                                @foreach ($cards as $card)<option value="{{ $card->id }}" title="{{ $card->currency }}">...{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} ({{ $card->currency }}) {{ $card->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('action') ? ' form__item--error' : '' }}">
                            <label for="action">Действие</label>
                            <select name="action" id="action">
                                <option value="deposit" >Пополнить</option>
                                <option value="withdraw">Списать</option>
                            </select>
                            @if ($errors->has('action'))
                                <p>{{ $errors->first('action') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('value') ? ' form__item--error' : '' }}">
                            <label for="value">Количество денег</label>
                            <input id="value" class="money_input" type="number" step="0.01" name="value">
                            @if ($errors->has('value'))
                                <p>{{ $errors->first('value') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('rate') ? ' form__item--error' : '' }}">
                            <label for="rate">Курс относительно USD</label>
                            <input id="rate" class="readonly" type="number" step="0.000001" min="0" name="rate" value="{{ old('rate') }}" readonly required>
                            @if ($errors->has('rate'))
                                <p>{{ $errors->first('rate') }}</p>
                            @endif
                        </div>

                        <div class="form__item">
                            <button type="button" id="get_rate">
                                <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Обновить курс
                            </button>
                        </div>

                        <div class="form__item{{ $errors->has('ask') ? ' form__item--error' : '' }}">
                            <label for="ask">Описание</label><br>
                            <textarea name="ask" id="ask" cols="50" rows="5" placeholder="краткий комментарий. не обязательно"></textarea>
                            @if ($errors->has('value'))
                                <p>{{ $errors->first('value') }}</p>
                            @endif
                        </div>

                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                            </button>
                        </div>

                    </form>
                </div>
                <!-- end items__add -->
                @endif

                <div class="items__list">
                    <h2>Список токенов</h2>
                    <form class="js-form" action="#" method="post">
                        <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="table-responsive">
                            <table class="table" id="tokens_ssp_table">
                                <thead>
                                    <tr>
                                        <td>Дата</td>
                                        <td>Пользователь</td>
                                        <td>Карта</td>
                                        <td>Сумма</td>
                                        <td>Валюта</td>
                                        <td>Курс</td>
                                        <td>Действие</td>
                                        <td>Описание</td>
                                        <td>Отзыв</td>
                                        <td>Статус</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>

            </div>
            <!-- end items -->

        </div>
    </main>
    <!-- end main -->

    <!-- begin beep -->
    <div class="beep" style="visibility: hidden">
        <audio id="sound1">
            <source src="{{ url('audio/filling-your-inbox.mp3') }}">
        </audio>
    </div>
    <!-- end beep -->

    <!-- Modal begin -->
    <div class="modal fade" id="modal_window" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->
@endsection


@section('scripts_end')
    <script>
        $(document).ready(function() {

            let user_status = "{{Auth::user()->status}}";
            let columnDefs_json = {};
            if (user_status != 'admin' && user_status != 'accountant') {
                columnDefs_json = {
                    "targets": [1],
                    "visible": false,
                    "searchable": true
                };
            }

            $('#tokens_ssp_table').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": "{{url('/api/tokens')}}",
                "responsive": true,
                "columns":[
                    {data: 'date'},
                    {data: 'user_name'},
                    {data: 'card_code'},
                    {data: 'value'},
                    {data: 'currency'},
                    {data: 'rate'},
                    {data: 'action'},
                    {data: 'ask'},
                    {data: 'ans'},
                    {data: 'status'},
                    {data: 'tools'}
                ],
                "columnDefs": [columnDefs_json],
                "initComplete": function () {
                    // let table = this;
                    // table.api().columns(2).every(function () {
                    //     var column = this;
                    //     var input = document.createElement("input");
                    //     input.style.maxWidth = '100px';
                    //     $(input).appendTo($(column.footer()).empty())
                    //     .on('change', function () {
                    //         column.search($(this).val(), false, false, true).draw();
                    //     });
                    // });
                }
            });

            const BEEP = (soundObj) => {
                let sound = document.getElementById(soundObj);
                if (sound)
                    sound.play();
            }

            var tokens_count   = null;
            let checkTokensUrl = "{{url('/api/token_notify')}}";

            const checkTokens = () => {
                $.ajax({
                    url: checkTokensUrl,
                    success: function(result){

                        if (tokens_count == null) {
                            tokens_count = result;
                            return;
                        }

                        if (tokens_count < result) {
                            BEEP("sound1");
                            alert("Новый токен! Обновите страницу");
                        }

                        if (tokens_count > result) {
                            BEEP("sound1");
                            alert("Токен обработан! Обновите страницу");
                        }

                        tokens_count = result;
                    }
                });
            }
            checkTokens();
            setInterval(checkTokens, 300000);
        });
    </script>
@endsection