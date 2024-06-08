<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Operation;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function create()
    {
        return view('addDetail');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'initialDiameter' => 'required|numeric',
            'wearCount' => 'required|integer|min:1',
            'wear' => 'required|array|min:1',
            'wear.*' => 'numeric',
            'type' => 'required|integer',
        ]);

        $wear = $validatedData['wear'];

        if (!is_array($wear)) {
            $wear = json_decode($wear, true);
        }

        $detail = Detail::create([
            'name' => $validatedData['name'],
            'diameter' => $validatedData['initialDiameter'],
            'wear' => $wear,
            'type' => $validatedData['type'],
        ]);

        $operations['turning'] = $detail->turningTime();

        if ($detail->type == 1 || $detail->type == 2) {
            $operations['electricityWelding'] = $detail->electricityWelding();
        } else {
            $operations['vibroWelding'] = $detail->vibroWelding();
        }
        $operations = [
            'secondTurning' => $detail->secondTurningTime(),
            'grinding' => $detail->grinding(),
        ];

        foreach ($operations as $type => $time) {
            Operation::create([
                'detail_id' => $detail->id,
                'type' => $type,
                'time' => $time,
            ]);
        }

        return redirect()->route('detail.create')->with('success', 'Detail created successfully with operations calculated.');
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
}


