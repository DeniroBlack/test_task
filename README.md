### Задание 1:
Создать laravel-проект в git-репозитории (подойдет любой публичный сервис, например github). Первым коммитом залить чистый фреймворк, следом — реализацию задания.
Представьте, что это код в составе большого проекта, который поддерживается годами, и механика импорта будет использоваться реальными людьми: контент-менеджеры будут импортировать файлы, программисты - вносить доработки и поддерживать в том числе и эту часть проекта. Учитывайте человеческий фактор. Можно показать подходы проектирования, подходящие под задачу.
Например, подходы, которые облегчат дальнейшую разработку и поддержку.


Реализовать загрузку excel файла (xlsx) через форму.
Поля excel: 
 * id
 * name
 * date (d.m.Y)
Данные из файла хранить в БД.
Проанализировать данные в excel-файле и на основе этого анализа реализовать валидацию данных из excel.
Строки excel, не прошедшие валидацию, пропускать.
В конце импорта вывести в текстовый файл все сообщения о ошибках валидации строк, в виде:
<номер строки> - <ошибка1>, <ошибка2>, …
и запушить сгенерированный файл result.txt с отчётом о ошибках в репозиторий с тестовым заданием, отдельным коммитом.
Импортировать файл в БД.
Прогресс парсинга файла хранить в redis (уникальный ключ + количество обработанных строк).
Для парсинга excel можете использовать любой пакет composer, но процесс импорта необходимо реализовать самостоятельно.
Реализовать RESTful API контроллер для получения импортированных данных из базы с группировкой по date:

```
[
    {
    date: ‘xxxx-xx-xx’,
        items: [
            {
                …
            },
            {
                …
            }
        ]
    },
    {
    date: ‘zzzz-zz-zz’,
        items: ….
    },
]
```
Реализуйте через laravel echo передачу event-а на создание записи в rows
Напишите тесты

### Задание 2:
Проанализируйте задание 1 и составьте список уточняющих вопросов для менеджера, который его составил. Опишите на основании своего анализа задание 1 в виде задачи, которую можно передать джуниору. Поясните ход своих мыслей. Результат сохранить в файл “task2.txt” и закоммитить.

### Задание 3:
Расскажите подробнее про вашу реализацию:
Причины выбора именно этого пакета composer для парсинга excel.
Чем вы руководствовались, когда определяли правила валидации excel-файла.
Как вы анализировали производительность вашего решения?
Будет ли ваше решение стабильно при увеличении количества строк в файле в 100 раз?
Что можно было бы улучшить в вашей реализации?
Результат сохранить в файл “task3.txt” и закоммитить.
