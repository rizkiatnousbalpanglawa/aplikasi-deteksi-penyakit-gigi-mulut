<?php
// Fungsi murni Certainty Factor — tanpa I/O database/session sehingga
// bisa diuji langsung (unit test) maupun dipakai handler diagnosa.
//
// CF tiap relasi = MB - MD (rentang -1 s.d. 1).
// Aturan kombinasi standar:
//   - keduanya positif : CF = CF1 + CF2 * (1 - CF1)
//   - keduanya negatif : CF = CF1 + CF2 * (1 + CF1)
//   - beda tanda       : CF = (CF1 + CF2) / (1 - min(|CF1|, |CF2|))

if (!function_exists('cf_gabung')) {
    function cf_gabung(float $lama, float $baru): float
    {
        if ($lama >= 0.0 && $baru >= 0.0) {
            return $lama + $baru * (1.0 - $lama);
        }

        if ($lama < 0.0 && $baru < 0.0) {
            return $lama + $baru * (1.0 + $lama);
        }

        $penyebut = 1.0 - min(abs($lama), abs($baru));
        if ($penyebut == 0.0) {
            return 0.0;
        }

        return ($lama + $baru) / $penyebut;
    }
}

if (!function_exists('cf_kombinasi')) {
    function cf_kombinasi(array $cfs): float
    {
        $hasil = 0.0;
        foreach ($cfs as $cf) {
            $hasil = cf_gabung($hasil, (float) $cf);
        }

        if ($hasil > 1.0) {
            return 1.0;
        }

        if ($hasil < -1.0) {
            return -1.0;
        }

        return $hasil;
    }
}
