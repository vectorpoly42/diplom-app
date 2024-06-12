<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\ExcelData;
use App\Models\Operation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OrderController extends Controller
{
    public  function index()
    {
        $orders = Order::all();
        return view('allOrders', compact('orders'));
    }

    public function create()
    {
        $details = Detail::all();
        return view('order', compact('details'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number_of_details' => 'required|integer|min:2',
            'detail_ids' => 'required|array|min:2',
            'detail_ids.*' => 'exists:details,id|distinct',
        ]);

        // Создание новой заявки
        $order = Order::create([
            'number_of_details' => $validated['number_of_details'],
            'number_of_devices' => 4,
            'J_parameter' => 44
        ]);

        // Присоединение деталей к заявке
        $order->details()->attach($validated['detail_ids']);

        $numberOfTypes = count(Detail::select('type')->distinct()->pluck('type')->toArray());
        $numberOfTasks = count($validated['detail_ids']);

        $order->J_parameter = $this->countJ($numberOfTypes, $numberOfTasks);

        $operationsMatrix = $this->calculateSummarizedOperations($validated['detail_ids']);
        $order->T_l_w = json_encode($operationsMatrix);

        $typeMatrix = $this->createTypeMatrix($validated['detail_ids']);
        $order->A_w_i = json_encode($typeMatrix);

        $order->save();

//        dd($order);

        return redirect()->route('order.show', $order->id)->with('success', 'Заявка успешно создана');
    }

    public function show($id)
    {
        $order = Order::with('details')->findOrFail($id);
        $details = $order->details;

        return view('orderResults', compact('order', 'details'));
    }

    private function calculateSummarizedOperations(array $detailIds)
    {
        $workTypes = ['turning', 'electricityWelding', 'vibroWelding', 'secondTurning', 'grinding'];

        $summarizedOperations = [];

        foreach ($workTypes as $workType) {
            $summarizedOperations[$workType] = 0;
        }

        foreach ($detailIds as $detailId) {
            $operations = Operation::where('detail_id', $detailId)->get();

            // Для каждой операции обновляем суммарные операции
            foreach ($operations as $operation) {
                $summarizedOperations[$operation->type] += $operation->main_time;
            }
        }

        return $summarizedOperations;
    }

    private function createTypeMatrix(array $detailIds)
    {
        $types = Detail::select('type')->distinct()->pluck('type')->toArray();  // Получение уникальных типов деталей
        $typeMatrix = [];

        foreach ($detailIds as $detailId) {
            $detail = Detail::find($detailId);
            $typeRow = $this->createTypeRow($detail->type, $types);
            $typeMatrix[] = $typeRow;
        }

        return $typeMatrix;
    }

    private function createTypeRow($detailType, $types)
    {
        $typeRow = array_fill(0, count($types), 0); // Создаем строку, заполненную нулями

        foreach ($types as $index => $type) {
            if ($type == $detailType) {
                $typeRow[$index] = 1;
            } else {
                $typeRow[$index] = 0;
            }
        }

        return $typeRow;
    }


    private function countJ(int $n, $n_w): int
    {
        // Проверяем, что $n_w не равно нулю, чтобы избежать деления на ноль
        if ($n_w == 0) {
            return 0; // Возвращаем ноль или другое подходящее значение, если $n_w равно нулю
        }

        // Вычисляем верхнюю и нижнюю границу
        $lowerBound = 2 * $n;
        $upperBound = $n * (floor($n_w / 2));

        // Проверяем, что верхняя граница больше или равна нижней границе
        if ($upperBound < $lowerBound) {
            // Если нет, меняем местами значения, чтобы обеспечить корректные аргументы для mt_rand()
            $temp = $lowerBound;
            $lowerBound = $upperBound;
            $upperBound = $temp;
        }

        // Возвращаем случайное целое число в заданном диапазоне
        return mt_rand($lowerBound, $upperBound);
    }

    public function download(Request $request)
    {
        $id = $request->route('id');
        $order = Order::with('details')->findOrFail($id);

        $spreadsheet = new Spreadsheet();

        // Добавляем лист в книгу
        $sheet = $spreadsheet->getActiveSheet();

        // Заполняем данные в лист
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Number of Details');
        $sheet->setCellValue('C1', 'Number of Devices');
        $sheet->setCellValue('D1', 'J Parameter');
        $sheet->setCellValue('E1', 'T L W');
        $sheet->setCellValue('F1', 'A W I');
        $sheet->setCellValue('G1', 'P L W');

        $row = 2;
        $sheet->setCellValue('A'. $row, $order->id);
        $sheet->setCellValue('B'. $row, $order->number_of_details);
        $sheet->setCellValue('C'. $row, $order->number_of_devices);
        $sheet->setCellValue('D'. $row, $order->J_parameter);
        $sheet->setCellValue('E'. $row, json_encode($order->T_l_w));
        $sheet->setCellValue('F'. $row, json_encode($order->A_w_i));
        $sheet->setCellValue('G'. $row, $order->P_l_w?? '');


        // Сохраняем файл в памяти
        // Сохраняем файл в памяти и отправляем на скачивание
        $writer = new Xlsx($spreadsheet);
        $fileName = 'order_'. $order->id. '.xlsx';

        // Буферизация вывода
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="'. $fileName . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    public function showUploadForm()
    {
        return view('upload');
    }

    public function showData($id)
    {
        $excelData = ExcelData::findOrFail($id);
        return view('showData', compact('excelData'));
    }

    public function uploadAndProcessFile(Request $request)
    {
        // Проверка наличия файла в запросе
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        // Получение файла из запроса
        $file = $request->file('file');

        // Загружаем Excel файл
        $spreadsheet = IOFactory::load($file->getRealPath());

        // Считываем все листы и сохраняем данные в базу данных
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $sheetData = $sheet->toArray();

            ExcelData::create([
                'sheet_name' => $sheetName,
                'data' => $sheetData, // Laravel автоматически преобразует массив в JSON для сохранения
            ]);
        }

        return response()->json(['message' => 'File uploaded and processed successfully'], 200);
    }

    public function listData()
    {
        $excelData = ExcelData::all();
        return view('list', compact('excelData'));
    }

    public function showinfoData($id)
    {
        $excelData = ExcelData::findOrFail($id);
        return view('showData', compact('excelData'));
    }
}
