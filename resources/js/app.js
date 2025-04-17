import './bootstrap'

import Echo from 'laravel-echo'
import SocketIO from 'socket.io-client'

const echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    client: SocketIO,
    transports: ['websocket', 'polling'],
    forceBase64: false,
    path: '/socket.io'
})

/** Запуск отслеживания обработки файла */
function startExcelDataFileUpload(key) {
    const excelDataProcessElement = document.getElementById(`excel-processing-${key}`)
    if (excelDataProcessElement) {
        echo.channel(`excel-data-import-final.${key}`)
            .listen('ExcelDataImportProcessed', (data) => {
                excelDataProcessElement.innerHTML = data.current ? data.current + ' строк обработано' : 'Ожидает начала обработки'
            })
        echo.channel(`excel-data-import-process.${key}`)
            .listen('ExcelDataImportFinal', (data) => {
                excelDataProcessElement.innerHTML = '<u>Обработка завершена</u>'
                // Отключаемся от каналов после завершения работы
                echo.channel(`excel-data-import-final.${key}`).stopListening('ExcelDataImportProcessed')
                echo.channel(`excel-data-import-final.${key}`).stopListening('ExcelDataImportFinal')
            })
    }
}

document.addEventListener("DOMContentLoaded", () => {
    let ids = document.getElementById('excel-data-watch-ids') || '[]'
    try {ids = JSON.parse(ids)} catch (e) {ids = []}
    ids.forEach(key => startExcelDataFileUpload(key))
})
