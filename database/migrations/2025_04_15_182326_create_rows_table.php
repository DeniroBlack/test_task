<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Создание таблицы в которую импортируются данные из Excel. */
    public function up(): void
    {
        Schema::create('rows', function (Blueprint $table) {

            // ТАК КАК НЕ БЫЛО УТОЧНЕНИЙ ЧТО ЭТО ЗА ТАБЛИЦА, И ДЛЯ ЧЕГО ОНА ПО БИЗНЕС ЛОГИКЕ НУЖНА,
            // ТО НЕ ПОНЯТНО, ДОЛЖНО ЛИ ЭТО БЫТЬ ПЕРВИЧНЫМ КЛЮЧОМ ИЛИ NUMBER. ПО ЭТОМУ ОСТАВЛЯЕМ СТРОКУ,
            // ТАК КАК ПО ДЛИНЕ ВВЕДЁННОГО ID МОГУТ бЫТЬ ОГРАНИЧЕНИЯ
            $table->string('id');

            $table->string('name');
            $table->date('date');
            $table->timestamps();

            // Добавляем индекс для более быстрого поиска при проверке уникальности.
            $table->index('id');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('rows');
    }
};
