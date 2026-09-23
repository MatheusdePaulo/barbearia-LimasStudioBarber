<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Lembretes de agendamento e expiração de pendentes (a cada minuto)
Schedule::command('app:send-appointment-reminders')->everyMinute();
Schedule::command('appointments:expirar-pendentes')->everyMinute();

// Aniversariantes (todo dia às 08:00) — DATE_FORMAT porque o Limas usa MySQL (o strftime era do SQLite)
Schedule::call(function () {
    $hoje = now()->format('m-d');
    $aniversariantes = \App\Models\User::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$hoje])->get();

    foreach ($aniversariantes as $user) {
        \Log::info("Parabéns enviado para: {$user->name}");
        // Enviar mensagem de Parabéns via WhatsApp aqui
    }
})->dailyAt('08:00');
