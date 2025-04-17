<?php
namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Jobs\Forms\ProcessExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UploadExcelData extends Controller {

    public function showForm() {
        return view('forms/upload_excel_data', [
            // Получаем ключи для отображения прогресса
            'waiting_processing_excel_files' => array_map(fn(string $key) => [
                'key' => $key,
                'name' => Redis::get(config('app.redis_keys.rows_names_prefix').$key),
                'count' => Redis::get(config('app.redis_keys.rows_count_prefix').$key),
            ], $this->_getSessionKeys())
        ]);
    }

    public function upload(Request $request) {
        // Валидация
        $request->validate(['excel_file' => 'required|mimes:xlsx'], [
            'excel_file.required' => 'Выберите файл для загрузки',
            'excel_file.mimes' => 'Только XLSX файлы разрешены',
        ]);

        $file = $request->file('excel_file');
        $key = sha1(uniqid());

        $sessionKeys = $this->_getSessionKeys();
        $sessionKeys[] = $key;
        $sessionId = session()->getId();
        Redis::set(config('app.redis_keys.rows_uploaded_prefix').$sessionId, json_encode($sessionKeys));

        // Данные в оперативке для отправки статусов и процесса обработки
        Redis::set(config('app.redis_keys.rows_names_prefix').$key, $file->getClientOriginalName());
        Redis::set(config('app.redis_keys.rows_count_prefix').$key, 0);

        ProcessExcelImport::dispatch($file->store('imports'), $key, $sessionId);

        return back()->with('status', 'Файл отправлен в обработку');
    }

    private function _getSessionKeys(): array {
        // Что бы напрямую хранить индикаторы загруженных файлов в сессиях придётся городить костыли при обработке через jobs,
        // по этому храним в redis
        $sessionKeys = [];
        try {
            $redisValue = Redis::get(config('app.redis_keys.rows_uploaded_prefix').session()->getId());
            $sessionKeys = json_decode($redisValue !== false ? $redisValue : '[]', true);
        } catch(\Exception $th) {}
        return $sessionKeys;
    }
}
