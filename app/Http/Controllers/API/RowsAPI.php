<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Rows;

class RowsAPI extends Controller
{
    /**
     * Получить данные, сгруппированные по дате
     */
    public function index()
    {
        // Можно докинуть параметры фильтра и лимитов
        return Rows::all()
            ->groupBy('date')
            ->map(function ($items, $date) {
                return [
                    'date' => $date,
                    'items' => $items->map->only(['id', 'name', 'created_at'])
                ];
            })
            ->values();
    }
}
