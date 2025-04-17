<?php
namespace App\Jobs\Forms;


use Validator;
use Storage;
use App\Models\Rows;
use App\Events\Forms\ExcelDataImportProcessed;
use App\Events\Forms\ExcelDataImportFinal;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessExcelImport implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $file, protected string $key, protected string $sessionId) {}

    public function handle() {
        $filePath = storage_path("app/private/{$this->file}");
        $spreadSheet = IOFactory::load($filePath);
        $sheet = $spreadSheet->getActiveSheet();
        $errors = [];

        $totalRows = $sheet->getHighestDataRow();
        foreach ($sheet->getRowIterator(2) as $row) { // Строки с данными
            $data = [
                'id' => $sheet->getCell('A' . $row->getRowIndex())->getValue(),
                'name' => $sheet->getCell('B' . $row->getRowIndex())->getValue(),
                'date' => $sheet->getCell('C' . $row->getRowIndex())->getValue(),
            ];

            $validator = Validator::make($data, [

                // ПО ЗАДАЧЕ НЕ ПОНЯТНО ОБНОВЛЯТЬ УЖЕ СУЩЕСТВУЮЩИЕ ID ИЛИ НЕТ, ПО ЛОГИКЕ ДОЛЖНО ОБНОВЛЯТЬСЯ,
                // РАЗ ЗАГРУЗИТЬ ФАЙЛ МОГУТ РАЗНЫЕ ЛЮДИ, НО ТАК КАК ЭТО НЕ УТОЧНЕНО, ДУБЛИ ПРОПУСКАЕМ ПОКА
                'id' => 'required|integer|min:1|unique:rows,id',

                'name' => 'required|string',
                'date' => 'required|date_format:d.m.Y',

            ], [
                'id.required' => 'Не указан ID',
                'id.integer' => 'Неверный формат ID',
                'id.unique' => 'ID уже используется',
                'id.min' => 'ID должен быть больше нуля',
                'name.required' => 'Не указано имя',
                'date.required' => 'Не указана дата',
                'date.date_format' => 'Неверный формат даты должна быть в формате дд.мм.гггг',
            ]);

            // Отправляем прогресс
            Redis::incr(config('app.redis_keys.rows_count_prefix') . $this->key);

            // Можно было через $dispatchEvent в модели реализовать, но тогда не будут переданы параметры
            ExcelDataImportProcessed::dispatch([
                'key' => $this->key,
                'total' => $totalRows,
                'current' => Redis::get($this->key),
            ], $this->key);

            // Собираем ошибки
            if ($validator->fails()) {
                $errors[$row->getRowIndex()] = $validator->errors();
                continue;
            }

            // Сохраняем запись
            Rows::create([
                'id' => $data['id'],
                'name' => $data['name'],
                'date' => Carbon::createFromFormat('d.m.Y', $data['date']),
            ]);
        }

        $this->saveReport($errors);

        event(new ExcelDataImportFinal('Импорт завершен', $this->key, $this->sessionId));

        unlink($filePath);
    }

    private function saveReport(array $errors) {
        $report = collect($errors)->map(
            fn($e, $line) => "$line - " . implode(', ', $e->all())
        );
        Storage::put('result.txt', $report->implode(PHP_EOL));
    }
}
