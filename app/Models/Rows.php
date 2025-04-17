<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Модель для хранения данных по загруженным excel */
class Rows extends Model {
    protected $fillable = ['id', 'name', 'date'];
}
