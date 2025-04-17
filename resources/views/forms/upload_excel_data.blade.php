@extends('app')

@section('content')
    <div class="row justify-content-center">

        <!-- Вывод ошибок -->
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Успех -->
        @if(session('status'))
            <div class="col-12">
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            </div>
            <script></script>
        @endif

        <!-- Отображение процесса обработки -->
        @if(count($waiting_processing_excel_files))
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <div id="excel-data-watch-ids" class="d-none">{{
                        json_encode(collect($waiting_processing_excel_files)->pluck('key'))
                    }}</div>
                @foreach ($waiting_processing_excel_files as $file)
                    <p>
                        Загруженный файл
                        <strong>{{ $file['name'] }}</strong>
                        <i id="excel-processing-{{ $file['key'] }}">{{
                            $file['current'] ? $file['current'] . ' строк обработано' : 'Ожидает начала обработки'
                        }}</i>
                    </p>
                @endforeach
                </div>
            </div>
        @endif

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Загрузка Excel файла</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('forms.upload_excel_data') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="excelFile" class="form-label">Выберите файл</label>
                            <input
                                type="file"
                                class="form-control"
                                id="excelFile"
                                name="excel_file"
                                accept=".xlsx, .xls"
                                required
                            >
                            <div class="form-text">
                                Поддерживаемые форматы: .xlsx, .xls
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Загрузить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
