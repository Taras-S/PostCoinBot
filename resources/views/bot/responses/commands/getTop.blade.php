@forelse ($top as $index => $sending)
    {{ $index }}. @{{ $member->recipient->messenger_name }} — {{ $member->amount }} coins
@empty
    Top for this period  is empty =(
@endforelse