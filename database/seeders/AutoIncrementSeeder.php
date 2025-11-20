<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AutoIncrementSeeder extends Seeder {
    protected int $increment = 1;
    
    protected function getIncrement(): int {
        $this->increment += 1;
        return $this->increment - 1;
    }
}