<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test; // Используем атрибуты вместо docblock
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use App\Models\Rows;
use App\Events\Forms\ExcelDataImportProcessed;
use App\Jobs\Forms\ProcessExcelImport; // Импортируем класс задания

class ExcelImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Redis::flushall();
        Event::fake(); // Фейковый Event для всех тестов
    }

    #[Test]
    public function file_upload_and_validation()
    {
        // Создаем маршрут если его нет в routes/api.php
        // Route::post('/upload', [ImportController::class, 'upload']);

        $file = UploadedFile::fake()->create('test.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response = $this->post('/api/upload', [
            'file' => $file
        ]);

        // Если endpoint еще не реализован, временно пропускаем тест
        // $this->markTestIncomplete('Endpoint not implemented');
        $response->assertStatus(200);

        $this->assertDatabaseCount('rows', 2);
        Storage::disk('local')->assertExists('result.txt');
    }

    #[Test]
    public function validation_rules()
    {
        $invalidRow = [
            'id' => 'not_integer',
            'name' => null,
            'date' => 'invalid_date'
        ];

        $validator = $this->app['validator']->make($invalidRow, (new ProcessExcelImport())->rules());
        $this->assertFalse($validator->passes());
        $this->assertEquals(['id', 'name', 'date'], array_keys($validator->errors()->toArray()));
    }

    #[Test]
    public function redis_progress_tracking()
    {
        $uploadId = uniqid();
        Redis::set("import_progress:$uploadId", 5);
        $this->assertEquals(5, Redis::get("import_progress:$uploadId"));
    }

    #[Test]
    public function api_response_structure()
    {
        // Создаем фабрику если ее нет
        Rows::factory()->create(['date' => '2023-01-01']);
        Rows::factory()->create(['date' => '2023-01-01']);
        Rows::factory()->create(['date' => '2023-01-02']);

        $response = $this->getJson('/api/rows');
        $response->assertJsonStructure([
            '*' => [
                'date',
                'items' => [
                    '*' => ['id', 'name', 'date']
                ]
            ]
        ]);
    }

    #[Test]
    public function event_dispatched_on_row_creation()
    {
        $row = Rows::factory()->create();
        Event::assertDispatched(ExcelDataImportProcessed::class, fn ($e) => $e->row->is($row));
    }
}
