<?php

use function Livewire\Volt\{state};
use function Livewire\Volt\{mount};
use App\Models\Fixture;
use App\Models\Prediction;

state([
    'fixture' => null,
    'prediction' => null,
]);

mount(function (Fixture $fixture) {
    $this->prediction = Prediction::firstOrCreate([
        'user_id' => auth()->user()->id,
        'fixture_id' => $fixture->id,
    ]);

    $this->prediction->score_home = $this->prediction->score_home ?? 0;
    $this->prediction->score_away = $this->prediction->score_away ?? 0;

    $this->dispatch('$refresh');
});

$increaseScore = fn (string $team) => $this->prediction->increaseScore($team);

$decreaseScore = fn (string $team) => $this->prediction->decreaseScore($team);

?>

<section class="flex flex-col gap-6 mt-4">

    <button wire:click="$dispatch('closePrediction')" class="w-8 text-euro font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" classname="h-4" fill="currentColor" viewBox="4 5.01 12 9.99">
            <path fillrule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" cliprule="evenodd"></path>
        </svg>
    </button>

    <div class="max-w-5xl sm:px-6 lg:px-8">

        <div class="py-4 bg-euro-dark rounded-4xl text-euro-light">

            <div class="px-4">
                <x-banner-danger :show="!$prediction->fixture->can_predict">Predictions for this match are closed</x-banner-danger>
            </div>

            <div class="px-7">

                <p class="mb-2 uppercase tracking-wide text-sm text-euro font-bold">{{ $fixture->date->timezone('Europe/Zurich')->isoFormat('dddd DD MMMM - H:mm') }}</p>
                <div class="text-3xl flex flex-row">
                    <div class="flex-grow">{{ __($prediction->fixture->homeTeam->name) }}</div>
                    @if ($fixture->can_predict)
                    <div class="w-8 flex items-center justify-center cursor-pointer" wire:click="decreaseScore('home')">
                        -
                    </div>
                    @endif
                    <div class="w-8 flex items-center justify-center">
                        {{ $prediction->score_home }}
                    </div>
                    @if ($fixture->can_predict)
                    <div class="w-8 flex items-center justify-center cursor-pointer" wire:click="increaseScore('home')">
                        +
                    </div>
                    @endif
                </div>
                <div class="text-3xl flex flex-row">
                    <div class="flex-grow">{{ __($prediction->fixture->awayTeam->name) }}</div>
                    @if ($fixture->can_predict)
                    <div class="w-8 flex items-center justify-center cursor-pointer" wire:click="decreaseScore('away')">
                        -
                    </div>
                    @endif
                    <div class="w-8 flex items-center justify-center">
                        {{ $prediction->score_away }}
                    </div>
                    @if ($fixture->can_predict)
                    <div class="w-8 flex items-center justify-center cursor-pointer" wire:click="increaseScore('away')">
                        +
                    </div>
                    @endif
                </div>
                <p class="mt-2 tracking-tighter uppercase text-sm text-euro">{{ $fixture->venue->name }} - {{ $fixture->venue->city }}</p>
            </div>
        </div>

    </div>

</section>
