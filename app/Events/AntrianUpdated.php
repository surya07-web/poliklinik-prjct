<?php

namespace App\Events;

use App\Models\DaftarPoli;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntrianUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $no_antrian;
    public $poli;
    public $dokter;
    public $per_jadwal;

    public function __construct(DaftarPoli $antrian)
    {
        $this->no_antrian = $antrian->no_antrian;
        $this->poli = $antrian->jadwal?->poli?->nama;
        $this->dokter = $antrian->jadwal?->dokter?->name;

        $this->per_jadwal = collect([
            [
                'id' => $antrian->id_jadwal,
                'nomor' => $antrian->no_antrian
            ]
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('antrian'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'antrian.updated';
    }
}