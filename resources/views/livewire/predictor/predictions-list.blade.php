<?php

use function Livewire\Volt\{state};
use function Livewire\Volt\{computed};
use function Livewire\Volt\{on};
use App\Models\Fixture;

state([
    'displayFixture' => null,
]);

$fixtures = computed(function () {
    return Fixture::with(['homeTeam', 'awayTeam', 'userPrediction'])->orderBy('date')->get();
});

$predictionDetail = fn (Fixture $fixture) => $this->displayFixture = $fixture;

on(['closePrediction' => function () {
    $this->displayFixture = null;
}]);

?>

<section>

    <div class="px-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if ($displayFixture)

                <livewire:predictor.predictions-detail :fixture="$displayFixture" />

            @else

                <div class="mt-2">
                    <div class="flex flex-col gap-4">
                        @foreach ($this->fixtures as $fixture)
                            <div wire:click="predictionDetail({{ $fixture }})" class="cursor-pointer">
                                <div v-for="fixture in stageFixtures" @class([
                                        'py-2 pl-7 pr-5',
                                        'rounded-full',
                                        'font-bold',
                                        'bg-euro-dark',
                                        'text-euro-light' => $fixture->can_predict,
                                        'text-euro-darkest' => !$fixture->can_predict,
                                    ])>
                                    <div class="flex flex-row">
                                        <div class="flex flex-grow items-center">{{ __($fixture->homeTeam->name) }}</div>
                                        <div class="w-8 uppercase text-center">{{ $fixture->userPrediction ? $fixture->userPrediction->score_home : '-' }}</div>
                                    </div>
                                    <div class="flex flex-row">
                                        <div class="flex flex-grow items-center">{{ __($fixture->awayTeam->name) }}</div>
                                        <div class="w-8 uppercase text-center">{{ $fixture->userPrediction ? $fixture->userPrediction->score_away : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @endif
        </div>
    </div>
</section>
