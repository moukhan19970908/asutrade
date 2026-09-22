<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Форма обращения в техподдержку на /support.
 *
 * Обращение только сохраняется в БД — никуда не пересылается.
 */
class SupportController extends Controller
{
    public function index(): View
    {
        return view('support');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'question' => ['required', 'string', 'max:5000'],
        ], [
            'phone.required' => 'Укажите номер телефона',
            'question.required' => 'Опишите вопрос',
            'question.max' => 'Вопрос слишком длинный, уместите в 5000 символов',
        ]);

        // Телефон храним цифрами — как в onec_users и cars, чтобы обращения
        // можно было сопоставить с клиентом.
        $phone = preg_replace('/\D+/', '', $data['phone']) ?? '';

        if ($phone === '') {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Номер телефона указан неверно']);
        }

        SupportRequest::create([
            'phone' => $phone,
            'question' => $data['question'],
        ]);

        return redirect()
            ->route('support')
            ->with('success', 'Обращение отправлено. Мы свяжемся с вами по указанному номеру.');
    }
}
