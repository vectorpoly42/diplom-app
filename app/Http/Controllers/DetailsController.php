<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use Illuminate\Support\Facades\Request;

class DetailsController
{
    public function index(Request $request)
    {
        $details = [];

//        $detailsData = json_decode($request->input('details'), true);

        $detailsData = [
            [
                "initialDiameter" => 10.5,
                "damage" => [
                    "depth" => 0.1,
                    "width" => 0.05,
                ],
                "type" => 1,
            ],
            [
                "initialDiameter" => 11.5,
                "damage" => [
                    "depth" => 0.14,
                    "width" => 0.05,
                ],
                "type" => 1,
            ],
            [
                "initialDiameter" => 12.5,
                "damage" => [
                    "depth" => 0.14,
                    "width" => 0.05,
                ],
                "type" => 1,
            ],
            [
                "initialDiameter" => 15.0,
                "damage" => [
                    "depth" => 0.2,
                    "width" => 0.1,
                ],
                "type" => 2,
            ],
            [
                "initialDiameter" => 10.0,
                "damage" => [
                    "depth" => 0.2,
                    "width" => 0.4,
                ],
                "type" => 3,
            ],
            [
                "initialDiameter" => 99.0,
                "damage" => [
                    "depth" => 0.5,
                    "width" => 1.4,
                ],
                "type" => 3,
            ]
        ];

        foreach ($detailsData as $detailData) {
            $detail = new Detail($detailData['initialDiameter'], $detailData['type'], $detailData['damage']);

            $details[] = $detail;
        }

        $detailWorks = [];

        foreach ($details as $key => $detail) {
            $detailWorks[$key]['type'] = $detail->type;

            $detailWorks[$key]['turning'] = $detail->turningTime();

            if ($detail->type == 1 || $detail->type == 2) {
                $detailWorks[$key]['electricityWelding'] = $detail->electricityWelding();
            } else {
                $detailWorks[$key]['vibroWelding'] = $detail->vibroWelding();
            }

            $detailWorks[$key]['grinding'] = $detail->grinding();
        }

        $taskTypesMatrix = [];

        $taskTypes = array_values(array_unique(array_column($detailWorks, 'type')));

        foreach ($taskTypes as $type) {
            $taskTypesMatrix[$type] = 0;
        }

        $taskTypesMatrixRes = [];

        foreach ($detailWorks as $detId => $work) {
            $taskTypesMatrixRes[$detId] = $taskTypesMatrix;
            $taskTypesMatrixRes[$detId][$work['type']] = 1;
        }

        $matrix = [
            'p1' => [],
            'p2' => [],
            'p3' => [],
            'p4' => [],
        ];

        foreach ($detailWorks as $work) {
            $type = 't' . $work['type'];
            if (!isset($matrix['p1'][$type])) $matrix['p1'][$type] = 0;
            if (!isset($matrix['p2'][$type])) $matrix['p2'][$type] = 0;
            if (!isset($matrix['p3'][$type])) $matrix['p3'][$type] = 0;
            if (!isset($matrix['p4'][$type])) $matrix['p4'][$type] = 0;

            $matrix['p1'][$type] += $work['turning'];
            if (isset($work['electricityWelding'])) {
                $matrix['p2'][$type] += $work['electricityWelding'];
            } else {
                $matrix['p2'][$type] += $work['vibroWelding'];
            }
            $matrix['p3'][$type] += $work['grinding'];
        }

        foreach ($matrix['p1'] as $type => $time) {
            $matrix['p4'][$type] = $matrix['p1'][$type] + $matrix['p2'][$type] + $matrix['p3'][$type];
        }

        return response()->json(['matrix' => $matrix, 'taskTypesMatrix' => $taskTypesMatrixRes]);
    }

    //вернуть общую сумму заданий (деталей)
    //ремонтных станков = 4 (4 типа обработки)
    // количество типов заданий
    // J – общее количество позиций, которые занимают ПЗ в последовательностях их выполнения на приборах КС (J>W)
    //  будет 2 каких-то числа - минимальный и максимальный, из диапазона будет рандомное значение
    // ПЗ - один тип деталей (допустим 3 детали одного типа это и есть пакет заданий)

    // матрицу переналадок - вернуть вспомогательное время по каждому из приборов для каждого типа детали
}
