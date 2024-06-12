<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use Illuminate\Support\Facades\Request;

class DetailsController
{
    public function index()
    {
        $details = Detail::all()->toArray();
        $availableTypes = Detail::select('type')->distinct()->pluck('type')->toArray();
        dd();
        return view('details', compact('details', 'availableTypes'));
    }

    //вернуть общую сумму заданий (деталей)
    //ремонтных станков = 4 (4 типа обработки)
    // количество типов заданий
    // J – общее количество позиций, которые занимают ПЗ в последовательностях их выполнения на приборах КС (J>W)
    //  будет 2 каких-то числа - минимальный и максимальный, из диапазона будет рандомное значение
    // ПЗ - один тип деталей (допустим 3 детали одного типа это и есть пакет заданий)

    // матрицу переналадок - вернуть вспомогательное время по каждому из приборов для каждого типа детали
}
