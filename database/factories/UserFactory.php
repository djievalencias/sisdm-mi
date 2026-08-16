<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    /**
     * Realistic Indonesian employee data (faker_locale = id_ID).
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $birth  = fake()->dateTimeBetween('-52 years', '-20 years');
        $hired  = fake()->dateTimeBetween('-6 years', '-4 months');

        return [
            'id_atasan'          => null, // backfilled in UserSeeder
            'nama'               => fake()->name($gender),
            'nik'                => fake()->unique()->nik($gender, $birth), // exactly 16 digits
            'email'              => fake()->unique()->userName() . '@mebelinternational.co.id',
            'npwp'               => fake()->unique()->numerify('################'),
            'password'           => static::$password ??= Hash::make('password123'),
            'no_telepon'         => fake()->unique()->numerify('08##########'),
            'jenis_kelamin'      => $gender === 'male' ? 'L' : 'P',
            'tempat_lahir'       => fake()->city(),
            'tanggal_lahir'      => $birth->format('Y-m-d'),
            'tanggal_perekrutan' => $hired->format('Y-m-d'),
            'agama'              => fake()->randomElement(['Islam', 'Islam', 'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'pendidikan'         => fake()->randomElement(['SMA', 'SMK Teknik Mesin', 'SMK Teknik Furnitur', 'D3 Teknik Industri', 'S1 Teknik Industri', 'S1 Manajemen', 'S1 Akuntansi']),
            'status_perkawinan'  => fake()->randomElement(['Menikah', 'Belum menikah']),
            'alamat'             => fake()->streetAddress(),
            'rt'                 => sprintf('%03d', fake()->numberBetween(1, 12)),
            'rw'                 => sprintf('%03d', fake()->numberBetween(1, 8)),
            'kelurahan'          => fake()->randomElement(['Kranggan', 'Sidomulyo', 'Tahunan', 'Mulyoharjo', 'Panggang', 'Bapangan', 'Demaan', 'Saripan']),
            'kecamatan'          => fake()->randomElement(['Jepara', 'Tahunan', 'Mlonggo', 'Batealit', 'Pecangaan']),
            'kabupaten_kota'     => fake()->randomElement(['Jepara', 'Kudus', 'Semarang', 'Demak']),
            'is_aktif'           => true,
            'is_admin'           => false,
            'is_archived'        => false,
            'is_remote'          => fake()->boolean(10),
            'email_verified_at'  => now(),
            'remember_token'     => Str::random(10),
        ];
    }

    public function archived(): static
    {
        return $this->state(fn () => [
            'is_archived' => true,
            'is_aktif'    => false,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
