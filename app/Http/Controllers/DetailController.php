<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Operation;
use App\Models\TechnologicalProcess;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetailController extends Controller
{
    public function allDetails()
    {
        $details = Detail::all();

        return view('details', compact('details'));

    }

    public function create()
    {
        return view('addDetail');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'initialDiameter' => 'required|numeric',
            'wearsCount' => 'required|integer|min:1',
            'wear_areas' => 'required|array|min:1',
            'wear_areas.*' => 'numeric',
            'type' => 'required|integer'
        ]);

        $wear_areas = $validatedData['wear_areas'];

        if (!is_array($wear_areas)) {
            $wears_area = json_decode($wear_areas, true);
        }

        $detail = Detail::create([
            'name' => $validatedData['name'],
            'diameter' => $validatedData['initialDiameter'],
            'wear_areas' => $wear_areas,
            'type' => $validatedData['type'],
            'wear' => $request->wear,
        ]);

        Log::info($validatedData);

        $operationsData = [
            'turning' => $detail->turningTime()
        ];

        if ($detail->type == 1 || $detail->type == 2) {
            $operationsData['electricityWelding'] = $detail->electricityWelding();
        } else {
            $operationsData['vibroWelding'] = $detail->vibroWelding();
        }

        $operationsData = array_merge($operationsData, [
            'secondTurning' => $detail->secondTurningTime(),
            'grinding' => $detail->grinding(),
        ]);

        $operations = [];

        foreach ($operationsData as $type => $time) {
            $main_time = isset($time['main_time']) ? $time['main_time'] : null;
            $auxiliary_time = isset($time['auxiliary_time']) ? $time['auxiliary_time'] : null;
            $operation = Operation::create([
                'detail_id' => $detail->id,
                'type' => $type,
                'main_time' => $main_time,
                'auxiliary_time' => $auxiliary_time,
            ]);

            $operations[] = $operation->toArray(); // Добавление операции в список
        }

        // Создание технологического процесса
        TechnologicalProcess::create([
            'detail_id' => $detail->id,
            'operations' => json_encode($operations), // Сохранение списка операций в формате JSON
        ]);

        // Возвращаем ответ или выполняем другие действия

        return redirect()->route('detail.create')->with('success', 'Detail created successfully with operations calculated.');
    }
}

    // mark: что нужно вернуть?

    //вернуть общую сумму заданий (деталей)
    //ремонтных станков = 4 (4 типа обработки)
    // количество типов заданий
    // J – общее количество позиций, которые занимают ПЗ в последовательностях их выполнения на приборах КС (J>W)
    //  будет 2 каких-то числа - минимальный и максимальный, из диапазона будет рандомное значение
    // ПЗ - один тип деталей (допустим 3 детали одного типа это и есть пакет заданий)

    // mark: матрицы

    // каждая деталь проходит 4 стадии обработки (4 прибора)
    // нужно вернуть время по каждому типу на одном приборе
    // сформировать матрицу по приборам и типам (пример):
    //    п1     п2    п3  п4
    // т1  1     2     3   4
    // т2  1     2     3   4
    // т3  1     2     3   4
    //

    // матрицу переналадок - вернуть вспомогательное время по каждому из приборов для каждого типа детали

    // вернуть матрицу соответствия задания типу заданий


