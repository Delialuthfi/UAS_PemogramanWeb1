<?php
// Model Schedule
class Schedule {
    public $id, $doctor_id, $hari, $jam_mulai, $jam_selesai;
    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->doctor_id = $data['doctor_id'] ?? null;
        $this->hari = $data['hari'] ?? '';
        $this->jam_mulai = $data['jam_mulai'] ?? '';
        $this->jam_selesai = $data['jam_selesai'] ?? '';
    }
}
