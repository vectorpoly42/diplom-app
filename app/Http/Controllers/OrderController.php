<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Operation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
}
