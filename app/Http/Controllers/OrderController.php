<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    public function create()
    {
        $details = Detail::all();
        return view('requests.create', compact('details'));
    }

    public function store(Request $request)
    {
        // Валидация данных формы
//        $request->validate([
//            // Правила валидации, если нужно
//        ]);

        // Создание заявки в БД
        $requestData = [
            // Данные заявки из формы, например:
            'detail_id' => $request->input('detail_id'),
            // Другие поля заявки
        ];

        $order = RequestModel::create($requestData);

        return view('requests.created', ['order' => $order]);
    }

    public function showResults($id)
    {
        $order = RequestModel::findOrFail($id);
        $details = Detail::whereIn('id', json_decode($order->detail_ids))->get();

        // Здесь вы можете выполнить свои расчеты и передать результаты в представление

        return view('requests.results', compact('order', 'details'));
    }
}
