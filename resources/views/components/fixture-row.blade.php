@props(['fixture'])

<div wire:click="$dispatch('fixtureDetail', { fixture: {{ $fixture->id }} })" class="cursor-pointer">
    <div v-for="fixture in stageFixtures" class="py-2 px-7 rounded-full bg-euro-dark border border-euro-dark font-bold text-euro-light">
        <div class="flex flex-row">
            <div class="flex flex-grow items-center">{{ __($fixture->homeTeam->name) }}</div>
            @if ($fixture->started)
            <div class="w-16 uppercase text-center">{{ $fixture->goals_home->count() }}</div>
            @else
            <div class="w-16 uppercase text-center">{{ $fixture->date->format('D d') }}</div>
            @endif
        </div>
        <div class="flex flex-row">
            <div class="flex flex-grow items-center">{{ __($fixture->awayTeam->name) }}</div>
            @if ($fixture->started)
            <div class="w-16 uppercase text-center">{{ $fixture->goals_away->count() }}</div>
            @else
            <div class="w-16 uppercase text-center">{{ $fixture->date->timezone('Europe/Zurich')->format('H:i') }}</div>
            @endif
        </div>
    </div>
</div>
