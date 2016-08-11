@forelse ($top as $i => $member)
{{ $i + 1 }}. {{ "@" . $member->messenger_name }} — {{ $member->total }} :postcoin:
@empty
    Top for this period is empty :(
@endforelse
