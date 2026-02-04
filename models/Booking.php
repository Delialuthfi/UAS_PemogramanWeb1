<?php
// Model Booking
class Booking {
    public $id, $user_id, $doctor_id, $schedule_id, $keluhan, $status, $created_at;
    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->doctor_id = $data['doctor_id'] ?? null;
        $this->schedule_id = $data['schedule_id'] ?? null;
        $this->keluhan = $data['keluhan'] ?? '';
        $this->status = $data['status'] ?? 'pending';
        $this->created_at = $data['created_at'] ?? '';
    }
}
