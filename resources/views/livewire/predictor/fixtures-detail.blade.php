<?php

use App\Models\User;
use function Livewire\Volt\{computed,state};

state([
    'fixture' => null,
]);

$users = computed(function () {
    return User::get();
});

?>

<section class="flex flex-col gap-6 mt-4">

    <button wire:click="$dispatch('closeMatch')" class="w-8 text-euro font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" classname="h-4" fill="currentColor" viewBox="4 5.01 12 9.99">
            <path fillrule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" cliprule="evenodd"></path>
        </svg>
    </button>

    <div class="max-w-5xl sm:px-6 lg:px-8 flex flex-col gap-4">

        <div class="py-6 px-7 bg-euro-dark rounded-4xl text-euro-light">

            <div class="">
                <p class="mb-2 uppercase tracking-wide text-sm text-euro font-bold">{{ $fixture->date->timezone('Europe/Zurich')->isoFormat('dddd DD MMMM - H:mm') }}</p>
                <div class="text-3xl flex flex-row">
                    <div class="flex-grow">{{ __($fixture->homeTeam->name) }}</div>
                    <div class="w-8">{{ $fixture->started ? $fixture->goals_home->count() : '' }}</div>
                </div>
                <div class="text-3xl flex flex-row">
                    <div class="flex-grow">{{ __($fixture->awayTeam->name) }}</div>
                    <div class="w-8">{{ $fixture->started ? $fixture->goals_away->count() : '' }}</div>
                </div>
                <p class="mt-2 tracking-tighter uppercase text-sm text-euro">{{ $fixture->venue->name }} - {{ $fixture->venue->city }}</p>
            </div>

        </div>

        @if ($fixture->events()->goals()->get()->isNotEmpty())
            <div class="py-6 px-7 bg-euro-dark rounded-4xl text-euro-light">
                @foreach ($fixture->events()->goals()->get() as $event)
                    <div class="border-b border-euro last:border-b-0 flex flex-row">
                        <div class="flex-1 font-bold">
                            @if ($event->team_id === $fixture->homeTeam->id)
                                {{ $event->player_name }}
                                {{ $event->isOwnGoal() ? '(OG)' : '' }}
                                {{ $event->isPenalty() ? '(Pen.)' : '' }}
                            @endif
                        </div>
                        <div class="w-10 text-center font-bold">{{ $event->time_elapsed }}'</div>
                        <div class="flex-1 font-bold text-right">
                            @if ($event->team_id === $fixture->awayTeam->id)
                                {{ $event->player_name }}
                                {{ $event->isOwnGoal() ? '(OG)' : '' }}
                                {{ $event->isPenalty() ? '(Pen.)' : '' }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($fixture->started)
            @foreach ($this->users as $user)
                <div class="py-1 pl-4 pr-1 bg-euro-dark rounded-4xl text-euro-light">
                    <div class="flex items-center">
                        <div class="flex-grow">
                            <p class="font-bold text-euro-light">
                                {{ $user->nickname ?? $user->name }}
                            </p>
                        </div>
                        @if ($user->prediction($fixture))
                            <div class="w-14 flex-shrink-0 text-2xl font-bold">
                                {{ $user->prediction($fixture)?->score_home }} - {{ $user->prediction($fixture)?->score_away }}
                            </div>
                            <div class="flex bg-euro-darkest w-10 h-10 flex-shrink-0 rounded-full ml-2 items-center justify-center">
                                <livewire:predictor.prediction-icon :prediction="$user->prediction($fixture)" />
                            </div>
                        @else
                            <div class="flex justify-center">
                                <span class="bg-euro-darkest text-euro font-bold px-10 py-1 rounded-full">FAIL</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</section>
