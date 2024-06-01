<?php

namespace App\Http\Controllers;

use App\Models\DetailModel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        $wear = json_decode($request->input('wear'), true);

        $detail = DetailModel::create([
            'initial_diameter' => $request->input('initialDiameter'),
            'wear' => $wear,
            'type' => $request->input('type'),
        ]);

        $detailWorks = [];

        foreach ($details as $key => $detail) {
            $detailWorks[$key]['turning'] = $detail->turningTime();
            $detailWorks[$key]['type'] = $detail->type;

            if ($detail->type == 1 || $detail->type == 2) {
                $detailWorks[$key]['electricityWelding'] = $detail->electricityWelding();
            } else {
                $detailWorks[$key]['vibroWelding'] = $detail->vibroWelding();
            }

            $detailWorks[$key]['grinding'] = $detail->grinding();
        }

        /** @var array<workType, instrument> */
        $result = [];

        foreach ($detailWorks as $detailId => $values) {
            $result[$detail->type]['turning'] += $values['turning'] ?? 0;
            $result[$detail->type]['electricityWelding'] += $values['electricityWelding'] ?? 0;
            $result[$detail->type]['vibroWelding'] += $values['vibroWelding'] ?? 0;
            $result[$detail->type]['grinding'] += $values['grinding'] ?? 0;
        }

        return $result;
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


