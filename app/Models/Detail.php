<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {
    private float $li = 0.0; // Общая длина обрабатываемой поверхности
    private float $damageDiameter = 0.0; // Диаметр, который меняется в процессе работы

    protected $table = 'details';

    protected $fillable = [
        'type', 'name', 'diameter', 'wear_areas', 'wear',
    ];

    protected $casts = [
        'wear_areas' => 'array',
    ];

    public function operations()
    {
        return $this->hasMany(Operation::class, 'detail_id');
    }

    public function technologicalProcess()
    {
        return $this->hasOne(TechnologicalProcess::class);
    }

    /**
     * Расчет времени токарной обработки
     */
    public function turningTime(
        float $yi = 3.5,  // Величина врезания и перебега для детали
        int $ki = 1,      // Количество проходов для снятия припуска при глубине резания
        float $s1i = 0.5, // Подача режущего инструмента
        float $v1i = 50,  // Скорость резания
        float $tv1i = 3   // Длительность вспомогательных операций токарной обработки
    ): array {
        $length = count($this->wear_areas) * ($this->lengthDetail(array_sum($this->wear_areas), $yi)); // Длина обрабатываемой поверхности детали с учетом врезания и пробега
        $this->calculateNewDiameter($this->diameter, $this->wear_areas); // Диаметр повреждений

        $n1i = 318 * ($v1i / $this->diameter); // Число оборотов деталей в минуту

        // Расчет длительности основной токарной обработки
        $to1i = (($length * $ki) / ($n1i * $s1i));

        // Возврат суммы основной и вспомогательной длительности обработки
        return [
            'main_time' => ceil($to1i + $tv1i),
            'auxiliary_time' => ceil($tv1i),
        ];
    }

    /**
     * Расчет длины обрабатываемой поверхности детали с учетом врезания и пробега
     */
    private function lengthDetail(
        float $li, // Длина обрабатываемой поверхности по чертежу (обработка поврежденной поверхности)
        float $yi // Величина врезания и перебега для детали
    ): float {
        $this->li = $li + $yi;
        return $this->li;
    }

    /**
     * Расчет нового диаметра после обработки
     */
    private function calculateNewDiameter(
        float $initialDiameter, // Начальный диаметр
        array $wears // Массив повреждений
    ) {
        $totalWear = 0;

        // Вычисляем средний износ на каждом из участков
        foreach ($wears as $segmentLength) {
            // Рассчитываем средний износ для текущего участка
            $wearPerSegment = ($initialDiameter - ($initialDiameter - $segmentLength)) / $initialDiameter;
            // Добавляем его к общему износу
            $totalWear += $wearPerSegment;
        }

        return $totalWear;
    }

    /**
     * Расчет времени электродуговой наплавки
     */
    public function electricityWelding(
        float $k2i = 1 // Количество проходов при наплавке
    ): array {
        $density = 7.81; // Плотность проволки
//        dd($density);
        $lengthDetail = $this->li; // Длина обрабатываемой поверхности детали с учетом врезания и пробега
//        dd($lengthDetail);
        $dpri = ($this->diameter) - ($this->damageDiameter); // Диаметр проволки (начальный диаметр - диаметр детали после обработки)
//        dd($dpri);
        $isv2i = (40 * pow($this->diameter, 1 / 3)); // Сварочный ток (А)
//        dd($isv2i);
        $k2ni = (2.3 + 0.065 * ($isv2i / $dpri)); // Коэффициент наплавки
//        dd($k2ni);
        $v2ei = (($k2ni * $isv2i) / (pi() * pow($this->diameter, 2)) * $density); // Скорость наплавки
//        dd($v2ei);
        $n2i = ((1000 * $v2ei) / (60 * pi() * $this->diameter)); // Частота вращения детали
//        dd($n2i);
        $s2i = $this->stepWelding($dpri, 2, 2.5); // Шаг наплавки детали


        // Основное время
        $to2i = (($k2i * $lengthDetail) / ($n2i * $s2i));
        // Вспомогательное время
        $tv2i = (($to2i * 15) / 100);

        return [
            'main_time' => ceil($to2i + $tv2i),
            'auxiliary_time' => ceil($tv2i),
        ];

    }

    /**
     * Вычисление шага наплавки
     */
    private function stepWelding(float $dpri, float $minFactor, float $maxFactor): float {
        $factor = mt_rand($minFactor * 100, $maxFactor * 100) / 100; // Случайный коэффициент в диапазоне от minFactor до maxFactor
        return $factor * $dpri;
    }

    /**
     * Расчет времени вибродуговой наплавки
     */
    public function vibroWelding(
        float $k2i = 2, // Количество проходов при наплавке
        float $h2vi = 3 // Толщина наплавляемого слоя
    ): array {
        $nu = 0.8 / 0.9; // Коэффициент перехода
        $alpha = 0.7 / 0.85; // Коэффициент отклонения толщины
        $dpri = $this->diameter - $this->damageDiameter; // Диаметр проволки
        $ui = $this->voltage($h2vi); // Напряжение
        $lengthDetail = $this->li; // Длина обрабатываемой поверхности детали с учетом врезания и пробега
        $isv2i = $this->electricity($dpri); // Сварочный ток (А)
        $s2i = $this->stepWelding($dpri, 1.6, 2.2); // Шаг наплавки
        $vni = (0.1 * $isv2i * $ui) / $dpri; // Величина подачи проволки

        $v2vi = ((0.785 * pow($dpri, 2) * $vni * $nu) / ($h2vi * $s2i * $alpha)); // Скорость наплавки
        $n2i = ((1000 * $v2vi) / (60 * pi() * $this->diameter)); // Частота вращения детали

        // Основное время
        $to2i = (($k2i * $lengthDetail) / ($n2i * $s2i));
        // Вспомогательное время
        $tv2i = (($to2i * 15) / 100);

        return [
            'main_time' => ceil($to2i + $tv2i),
            'auxiliary_time' => $tv2i
            ];
    }

    /**
     * Расчет сварочного тока
     */
    private function electricity(float $dpri): float {
        $factor = mt_rand(60 * 100, 75 * 100) / 100;
        return $factor * $dpri;
    }

    /**
     * Расчет напряжения
     */
    private function voltage(float $h2vi): float {
        $ui = 0.0;

        if ($h2vi >= 1.0 && $h2vi <= 1.5) {
            $ui = rand(15, 20); // Случайное значение в диапазоне 15-20 В
        } elseif ($h2vi >= 2.0 && $h2vi <= 2.5) {
            $ui = rand(20, 25); // Случайное значение в диапазоне 20-25 В
        } else {
            $ui = rand(30, 40);
        }

        return $ui;
    }

    /**
     * Расчет времени третьей токарной обработки
     */
    public function secondTurningTime(
        float $yi = 3.5,  // Величина врезания и перебега для детали
        int $ki = 1,      // Количество проходов для снятия припуска при глубине резания
        float $s1i = 0.5, // Подача режущего инструмента
        float $v1i = 50,  // Скорость резания
        float $tv1i = 3   // Длительность вспомогательных операций токарной обработки
    ): array {
        $lengthDetail = count($this->wear_areas) * ($this->lengthDetail(($this->diameter / 2), $yi)); // Длина обрабатываемой поверхности детали с учетом врезания и пробега
        $this->calculateNewDiameter($this->diameter, $this->wear_areas); // Диаметр повреждений
        $n1i = 318 * ($v1i / $this->diameter); // Число оборотов деталей в минуту

        // Расчет длительности основной токарной обработки
        $to1i = (($lengthDetail * $ki) / ($n1i * $s1i));

        // Возврат суммы основной и вспомогательной длительности обработки
        return [
            'main_time' => ceil($to1i + $tv1i),
            'auxiliary_time' => ceil($tv1i),
        ];
    }

    /**
     * Расчет времени шлифования
     */
    public function grinding(
        float $snp4i = 0.4 // Продольная подача инструментов
    ): array {
        $lengthDetail = $this->li; // Длина детали
        $ki4 = rand(4, 10); // Количество проходов
        $v4i = rand(400, 600) / 100; // Скорость шлифования деталей
        $n4i = 318 * ($v4i / $this->diameter);
        $k = rand(12, 17) / 100; // Исправлено на mt_rand()

        // Вспомогательное время
        $tv4i = rand(270, 340) / 100; // Исправлено на mt_rand()
        // Основное время
        $to4i = (($lengthDetail * $ki4) / ($n4i * $snp4i)) * $k;

        return [
            'main_time' => ceil($to4i + $tv4i),
            'auxiliary_time' => ceil($tv4i)
        ];
    }
}
