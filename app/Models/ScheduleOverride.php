<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScheduleOverride extends Model
{
    protected $fillable = [
        'date',
        'is_closed',
        'open_time',
        'break_start',
        'break_end',
        'close_time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];

    /**
     * Retorna todos os slots (strings 'HH:MM') para uma data.
     * Respeita override se existir, senão usa regras padrão.
     */
    public static function getSlotsForDate(string $date): array
    {
        $override = static::where('date', $date)->first();
        $carbonDate = Carbon::parse($date);
        $dow = $carbonDate->dayOfWeek; // 0=Dom, 1=Seg, 6=Sab

        if ($override) {
            if ($override->is_closed) return [];

            return static::buildSlots(
                $override->open_time,
                $override->break_start,
                $override->break_end,
                $override->close_time
            );
        }

        // Regras padrão do Lima's: segunda a sábado, 8h às 18h, fechado pro almoço das 12h às 14h
        if ($dow === 0) return []; // Domingo = fechado

        return static::buildSlots('08:00', '12:00', '14:00', '18:00');
    }

    /**
     * Retorna o override ativo para uma data, ou null se usar padrão.
     */
    public static function forDate(string $date): ?self
    {
        return static::where('date', $date)->first();
    }

    /**
     * Descrição legível do horário de um dia (para exibir na agenda).
     */
    public static function descriptionForDate(string $date): string
    {
        $override = static::where('date', $date)->first();
        $carbonDate = Carbon::parse($date);
        $dow = $carbonDate->dayOfWeek;

        if ($override) {
            if ($override->is_closed) return 'Fechado (customizado)';
            $open  = substr($override->open_time, 0, 5);
            $close = substr($override->close_time, 0, 5);
            $desc = "{$open} – {$close}";
            if ($override->break_start && $override->break_end) {
                $desc .= " | Almoço " . substr($override->break_start, 0, 5) . "–" . substr($override->break_end, 0, 5);
            }
            if ($override->notes) $desc .= " ({$override->notes})";
            return $desc . ' ✏️';
        }

        if ($dow === 0) return 'Fechado';
        return '08:00 – 18:00 | Almoço 12:00–14:00';
    }

    /**
     * Slots de 30 min. O fechamento e o início do almoço são o fim do último atendimento
     * (ex.: fecha 12:00 → último horário 11:30).
     */
    private static function buildSlots(string $open, ?string $breakStart, ?string $breakEnd, string $close): array
    {
        $slots = [];

        if ($breakStart && $breakEnd) {
            $cur  = Carbon::parse($open);
            $mEnd = Carbon::parse($breakStart);
            while ($cur->lt($mEnd)) {
                $slots[] = $cur->format('H:i');
                $cur->addMinutes(30);
            }

            $cur = Carbon::parse($breakEnd);
            $end = Carbon::parse($close);
            while ($cur->lt($end)) {
                $slots[] = $cur->format('H:i');
                $cur->addMinutes(30);
            }
        } else {
            $cur = Carbon::parse($open);
            $end = Carbon::parse($close);
            while ($cur->lt($end)) {
                $slots[] = $cur->format('H:i');
                $cur->addMinutes(30);
            }
        }

        return $slots;
    }
}
