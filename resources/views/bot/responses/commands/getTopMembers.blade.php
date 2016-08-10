@forelse ($top as $index => $member)
    {{ $index }}. {{ $member->to_name }} — {{ $member->amount }} coins
@empty
    Top for this period is empty :(
@endforelse