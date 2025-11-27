<?php

use App\Models\Shift;
use App\Models\ShiftTemplate;
use App\Livewire\Staff\ShiftForm;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('single date mode creates one shift', function () {
    $user = User::factory()->create();
    $template = ShiftTemplate::factory()->create([
        'name' => 'Mañana',
        'start_time' => '08:00:00',
        'end_time' => '16:00:00',
    ]);

    Livewire::test(ShiftForm::class)
        ->set('userId', $user->id)
        ->set('shift_template_id', $template->id)
        ->set('date', Carbon::tomorrow()->format('Y-m-d'))
        ->set('start_time', '08:00')
        ->set('end_time', '16:00')
        ->set('use_date_range', false)
        ->call('save')
        ->assertHasNoErrors();

    expect(Shift::count())->toBe(1);
    expect(Shift::first()->user_id)->toBe($user->id);
    expect(Shift::first()->shift_template_id)->toBe($template->id);
});

test('date range mode creates multiple shifts', function () {
    $user = User::factory()->create();
    $template = ShiftTemplate::factory()->create([
        'name' => 'Tarde',
        'start_time' => '14:00:00',
        'end_time' => '22:00:00',
    ]);

    $startDate = Carbon::tomorrow()->format('Y-m-d');
    $endDate = Carbon::tomorrow()->addDays(4)->format('Y-m-d'); // 5 días

    Livewire::test(ShiftForm::class)
        ->set('userId', $user->id)
        ->set('shift_template_id', $template->id)
        ->set('use_date_range', true)
        ->set('date', $startDate)
        ->set('end_date', $endDate)
        ->set('exclude_weekends', false)
        ->set('start_time', '14:00')
        ->set('end_time', '22:00')
        ->call('save')
        ->assertHasNoErrors();

    expect(Shift::count())->toBe(5);
    expect(Shift::where('user_id', $user->id)->count())->toBe(5);
});

test('date range mode excludes weekends when option is enabled', function () {
    $user = User::factory()->create();
    $template = ShiftTemplate::factory()->create([
        'name' => 'Noche',
        'start_time' => '22:00:00',
        'end_time' => '06:00:00',
    ]);

    // Encontrar el próximo lunes
    $monday = Carbon::now()->next(Carbon::MONDAY);
    $friday = $monday->copy()->addDays(4); // Lunes a viernes = 5 días laborables
    $sunday = $monday->copy()->addDays(6); // Incluye fin de semana = 7 días totales

    Livewire::test(ShiftForm::class)
        ->set('userId', $user->id)
        ->set('shift_template_id', $template->id)
        ->set('use_date_range', true)
        ->set('date', $monday->format('Y-m-d'))
        ->set('end_date', $sunday->format('Y-m-d'))
        ->set('exclude_weekends', true)
        ->set('start_time', '22:00') // Necesario para validación
        ->set('end_time', '06:00') // Necesario para validación
        ->call('save')
        ->assertHasNoErrors();

    // Solo debe crear 5 turnos (lunes a viernes)
    expect(Shift::count())->toBe(5);

    // Verificar que no hay turnos en sábado o domingo
    $shifts = Shift::all();
    foreach ($shifts as $shift) {
        $dayOfWeek = Carbon::parse($shift->date)->dayOfWeek;
        expect($dayOfWeek)->not->toBeIn([Carbon::SATURDAY, Carbon::SUNDAY]);
    }
});

test('date range requires template to be selected', function () {
    $user = User::factory()->create();

    Livewire::test(ShiftForm::class)
        ->set('userId', $user->id)
        ->set('use_date_range', true)
        ->set('date', Carbon::tomorrow()->format('Y-m-d'))
        ->set('end_date', Carbon::tomorrow()->addDays(3)->format('Y-m-d'))
        ->call('save');

    // Sin plantilla y en modo rango, debe usar el flujo normal (sin crear múltiples)
    // O podríamos validar que se requiere plantilla en modo rango
    expect(Shift::count())->toBe(0);
});

test('date range validates end date is after start date', function () {
    $user = User::factory()->create();
    $template = ShiftTemplate::factory()->create();

    Livewire::test(ShiftForm::class)
        ->set('userId', $user->id)
        ->set('shift_template_id', $template->id)
        ->set('use_date_range', true)
        ->set('date', Carbon::tomorrow()->addDays(5)->format('Y-m-d'))
        ->set('end_date', Carbon::tomorrow()->format('Y-m-d')) // End antes que start
        ->set('start_time', '08:00')
        ->set('end_time', '16:00')
        ->call('save')
        ->assertHasErrors(['end_date']);
});

test('editing existing shift ignores date range mode', function () {
    $user = User::factory()->create();
    $template = ShiftTemplate::factory()->create();
    $shift = Shift::factory()->create([
        'user_id' => $user->id,
        'shift_template_id' => $template->id,
        'date' => Carbon::tomorrow()->format('Y-m-d'),
        'start_time' => '08:00:00',
        'end_time' => '16:00:00',
    ]);

    Livewire::test(ShiftForm::class, ['shiftId' => $shift->id])
        ->set('use_date_range', true) // Intentar activar rango
        ->set('end_date', Carbon::tomorrow()->addDays(3)->format('Y-m-d'))
        ->call('save')
        ->assertHasNoErrors();

    // Solo debe actualizar el turno existente, no crear nuevos
    expect(Shift::count())->toBe(1);
});
