<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class TestController extends Controller
{
    public function upload(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Допустимые типы файлов и макс. размер
        ]);

        // Получаем файл
        $file = $request->file('image');

        // Генерация случайного имени
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

        // Путь для сохранения файла
        $destinationPath = public_path('storage');

        // Перемещаем файл в указанное место
        $file->move($destinationPath, $filename);

        // Возвращаем полный URL файла
        return response()->json([
            'message' => 'Файл успешно загружен',
            'file_path' => asset('storage/' . $filename),
        ]);
    }
}
