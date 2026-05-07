<?php

namespace Database\Seeders;

use App\Models\BodyComposition;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
	public function run(): void
	{
		$user = User::updateOrCreate(
			['email' => '86719@siswa.unimas.my'],
			[
				'name' => 'Nathanael Douglas',
				'password' => Hash::make('password'),
				'role' => 'user',
				'account_status' => 'Active',
				'notifications_enabled' => true,
				'email_alerts_enabled' => true,
				'weekly_reports_enabled' => true,
				'measurement_reminders_enabled' => true,
				'age' => 25,
				'gender' => 'Male',
				'height_cm' => 173,
				'email_verified_at' => now(),
			]
		);

		$user->bodyCompositions()->delete();

		$measurements = [
			[
				'measurement_date' => '2025-11-08',
				'measurement_time' => '07:15',
				'weight_kg' => 49.8,
				'body_fat_percent' => 8.4,
				'body_fat_kg' => 4.18,
				'body_water_percent' => 61.2,
				'muscle_mass' => 42.6,
				'physical_rating' => 5,
				'bone_mass' => 2.4,
				'kcal' => 1284,
				'body_age' => 20,
				'visceral_fat' => 1.0,
			],
			[
				'measurement_date' => '2025-12-06',
				'measurement_time' => '07:20',
				'weight_kg' => 50.4,
				'body_fat_percent' => 8.5,
				'body_fat_kg' => 4.28,
				'body_water_percent' => 61.0,
				'muscle_mass' => 42.9,
				'physical_rating' => 5,
				'bone_mass' => 2.4,
				'kcal' => 1298,
				'body_age' => 20,
				'visceral_fat' => 2.0,
			],
			[
				'measurement_date' => '2026-01-03',
				'measurement_time' => '07:10',
				'weight_kg' => 51.0,
				'body_fat_percent' => 8.7,
				'body_fat_kg' => 4.44,
				'body_water_percent' => 60.8,
				'muscle_mass' => 43.2,
				'physical_rating' => 6,
				'bone_mass' => 2.4,
				'kcal' => 1310,
				'body_age' => 19,
				'visceral_fat' => 1.9,
			],
			[
				'measurement_date' => '2026-01-31',
				'measurement_time' => '07:10',
				'weight_kg' => 51.6,
				'body_fat_percent' => 8.8,
				'body_fat_kg' => 4.54,
				'body_water_percent' => 60.6,
				'muscle_mass' => 43.6,
				'physical_rating' => 6,
				'bone_mass' => 2.4,
				'kcal' => 1322,
				'body_age' => 19,
				'visceral_fat' => 1.8,
			],
			[
				'measurement_date' => '2026-02-14',
				'measurement_time' => '07:05',
				'weight_kg' => 52.0,
				'body_fat_percent' => 8.9,
				'body_fat_kg' => 4.63,
				'body_water_percent' => 60.4,
				'muscle_mass' => 43.9,
				'physical_rating' => 6,
				'bone_mass' => 2.5,
				'kcal' => 1330,
				'body_age' => 19,
				'visceral_fat' => 1.6,
			],
			[
				'measurement_date' => '2026-03-01',
				'measurement_time' => '07:00',
				'weight_kg' => 52.2,
				'body_fat_percent' => 9.0,
				'body_fat_kg' => 4.7,
				'body_water_percent' => 60.2,
				'muscle_mass' => 44.2,
				'physical_rating' => 6,
				'bone_mass' => 2.5,
				'kcal' => 1336,
				'body_age' => 18,
				'visceral_fat' => 1.5,
			],
			[
				'measurement_date' => '2026-03-15',
				'measurement_time' => '07:00',
				'weight_kg' => 52.4,
				'body_fat_percent' => 9.1,
				'body_fat_kg' => 4.77,
				'body_water_percent' => 60.0,
				'muscle_mass' => 44.6,
				'physical_rating' => 7,
				'bone_mass' => 2.5,
				'kcal' => 1341,
				'body_age' => 18,
				'visceral_fat' => 1.3,
			],
			[
				'measurement_date' => '2026-03-29',
				'measurement_time' => '07:05',
				'weight_kg' => 52.6,
				'body_fat_percent' => 9.2,
				'body_fat_kg' => 4.84,
				'body_water_percent' => 59.9,
				'muscle_mass' => 44.9,
				'physical_rating' => 7,
				'bone_mass' => 2.5,
				'kcal' => 1345,
				'body_age' => 18,
				'visceral_fat' => 1.2,
			],
			[
				'measurement_date' => '2026-04-12',
				'measurement_time' => '07:00',
				'weight_kg' => 52.7,
				'body_fat_percent' => 9.2,
				'body_fat_kg' => 4.85,
				'body_water_percent' => 59.8,
				'muscle_mass' => 45.1,
				'physical_rating' => 7,
				'bone_mass' => 2.5,
				'kcal' => 1348,
				'body_age' => 18,
				'visceral_fat' => 1.1,
			],
			[
				'measurement_date' => '2026-04-26',
				'measurement_time' => '07:10',
				'weight_kg' => 52.9,
				'body_fat_percent' => 9.3,
				'body_fat_kg' => 4.91,
				'body_water_percent' => 59.6,
				'muscle_mass' => 45.4,
				'physical_rating' => 7,
				'bone_mass' => 2.5,
				'kcal' => 1350,
				'body_age' => 18,
				'visceral_fat' => 1.0,
			],
		];

		foreach ($measurements as $measurement) {
			BodyComposition::create([
				'user_id' => $user->id,
				...$measurement,
			]);
		}
	}
}
