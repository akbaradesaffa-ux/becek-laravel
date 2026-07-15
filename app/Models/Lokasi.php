<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    public const HARI_OPERASIONAL = [
        'senin' => 'Senin',
        'selasa' => 'Selasa',
        'rabu' => 'Rabu',
        'kamis' => 'Kamis',
        'jumat' => 'Jumat',
        'sabtu' => 'Sabtu',
        'minggu' => 'Minggu',
    ];

    protected $table = 'tb_lokasi';
    public $timestamps = false;

    protected $fillable = [
        'kode_lokasi',
        'nama',
        'kategori',
        'area',
        'rentang_harga',
        'link_google_maps',
        'jalur_foto',
        'hari_operasional',
        'jam_buka',
        'jam_tutup',
        'is_recommended',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
    ];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'tb_lokasi_fasilitas', 'lokasi_id', 'fasilitas_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'lokasi_id');
    }

    public function fotos()
    {
        return $this->hasMany(LokasiFoto::class, 'lokasi_id')->orderBy('urutan')->orderBy('id');
    }

    public function jadwalOperasional()
    {
        return $this->hasMany(JadwalOperasional::class, 'lokasi_id')->orderBy('urutan')->orderBy('id');
    }

    public function getFasilitasStringAttribute(): string
    {
        return $this->fasilitas->pluck('nama_fasilitas')->implode(', ');
    }

    public function getJadwalOperasionalFormAttribute(): array
    {
        $jadwal = [];
        $records = $this->relationLoaded('jadwalOperasional')
            ? $this->jadwalOperasional
            : $this->jadwalOperasional()->get();
        $recordsByDay = $records->keyBy('hari');

        foreach (self::HARI_OPERASIONAL as $key => $label) {
            $record = $recordsByDay->get($key);

            if ($record) {
                $status = !$record->is_buka
                    ? 'tutup'
                    : ($record->is_24_jam ? '24_jam' : 'buka');

                $jadwal[$key] = [
                    'status' => $status,
                    'jam_buka' => $record->jam_buka ? substr((string) $record->jam_buka, 0, 5) : '',
                    'jam_tutup' => $record->jam_tutup ? substr((string) $record->jam_tutup, 0, 5) : '',
                ];
                continue;
            }

            if ($this->jam_buka && $this->jam_tutup) {
                $jadwal[$key] = [
                    'status' => 'buka',
                    'jam_buka' => substr((string) $this->jam_buka, 0, 5),
                    'jam_tutup' => substr((string) $this->jam_tutup, 0, 5),
                ];
                continue;
            }

            $jadwal[$key] = [
                'status' => 'tutup',
                'jam_buka' => '',
                'jam_tutup' => '',
            ];
        }

        return $jadwal;
    }

    public function getHariOperasionalLabelAttribute(): string
    {
        $now = Carbon::now('Asia/Jakarta');
        $keys = array_keys(self::HARI_OPERASIONAL);
        $key = $keys[$now->dayOfWeekIso - 1] ?? 'senin';

        return self::HARI_OPERASIONAL[$key];
    }

    public function getJamOperasionalLabelAttribute(): string
    {
        $todayKey = $this->todayKey();
        $schedule = $this->jadwal_operasional_form[$todayKey] ?? null;

        return $this->scheduleLabel($schedule);
    }

    public function getStatusOperasionalAttribute(): string
    {
        $now = Carbon::now('Asia/Jakarta');
        $schedules = $this->jadwal_operasional_form;
        $todayKey = $this->todayKey($now);
        $todaySchedule = $schedules[$todayKey] ?? null;

        if ($this->isScheduleOpenAt($todaySchedule, $now, $now->copy()->startOfDay())) {
            return 'Buka sekarang';
        }

        $previousDay = $now->copy()->subDay();
        $previousKey = $this->todayKey($previousDay);
        $previousSchedule = $schedules[$previousKey] ?? null;

        if ($this->isOvernightSchedule($previousSchedule)
            && $this->isScheduleOpenAt($previousSchedule, $now, $previousDay->copy()->startOfDay())) {
            return 'Buka sekarang';
        }

        return 'Tutup';
    }

    public function getRingkasanJadwalOperasionalAttribute(): array
    {
        $schedules = $this->jadwal_operasional_form;
        $days = array_keys(self::HARI_OPERASIONAL);
        $groups = [];
        $current = null;

        foreach ($days as $index => $day) {
            $label = $this->scheduleLabel($schedules[$day] ?? null);

            if ($current && $current['label'] === $label) {
                $current['end'] = $day;
                $groups[array_key_last($groups)] = $current;
                continue;
            }

            $current = [
                'start' => $day,
                'end' => $day,
                'label' => $label,
            ];
            $groups[] = $current;
        }

        return array_map(function (array $group): string {
            $dayLabel = self::HARI_OPERASIONAL[$group['start']];
            if ($group['start'] !== $group['end']) {
                $dayLabel .= ' - ' . self::HARI_OPERASIONAL[$group['end']];
            }

            return $dayLabel . ': ' . $group['label'];
        }, $groups);
    }

    public function getJadwalOperasionalTextAttribute(): string
    {
        return implode("\n", $this->ringkasan_jadwal_operasional);
    }

    private function todayKey(?Carbon $date = null): string
    {
        $date ??= Carbon::now('Asia/Jakarta');
        $keys = array_keys(self::HARI_OPERASIONAL);

        return $keys[$date->dayOfWeekIso - 1] ?? 'senin';
    }

    private function scheduleLabel(?array $schedule): string
    {
        if (!$schedule) {
            return 'Jadwal belum diatur';
        }

        return match ($schedule['status'] ?? 'tutup') {
            '24_jam' => '24 Jam',
            'buka' => !empty($schedule['jam_buka']) && !empty($schedule['jam_tutup'])
                ? $schedule['jam_buka'] . ' - ' . $schedule['jam_tutup']
                : 'Jadwal belum diatur',
            default => 'Tutup',
        };
    }

    private function isOvernightSchedule(?array $schedule): bool
    {
        if (($schedule['status'] ?? null) !== 'buka') {
            return false;
        }

        if (empty($schedule['jam_buka']) || empty($schedule['jam_tutup'])) {
            return false;
        }

        return $schedule['jam_tutup'] <= $schedule['jam_buka'];
    }

    private function isScheduleOpenAt(?array $schedule, Carbon $now, Carbon $scheduleDate): bool
    {
        if (!$schedule || ($schedule['status'] ?? 'tutup') === 'tutup') {
            return false;
        }

        if (($schedule['status'] ?? null) === '24_jam') {
            return $now->isSameDay($scheduleDate);
        }

        if (empty($schedule['jam_buka']) || empty($schedule['jam_tutup'])) {
            return false;
        }

        $open = $scheduleDate->copy()->setTimeFromTimeString($schedule['jam_buka']);
        $close = $scheduleDate->copy()->setTimeFromTimeString($schedule['jam_tutup']);

        if ($close->lessThanOrEqualTo($open)) {
            $close->addDay();
        }

        return $now->betweenIncluded($open, $close);
    }
}
