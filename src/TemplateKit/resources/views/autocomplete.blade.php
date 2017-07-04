<div class="form-group{{ $element->getRequired() ? ' required' : '' }}{{ isset($errors[$element->getName()]) ? ' has-error' : '' }}">
    <label for="{{ $element->getName() }}">{{ $element->getLabel() }}</label>
    <input type="text" class="form-control" name="{{ $element->getName() }}AutoComplete" value="" placeholder="{{ $element->getPlaceholder() }}"{{ $element->getRequired() ? ' required' : '' }} />
    <input type="hidden" name="{{ $element->getName() }}" value="{{ $data->{$element->getName()} }}" >
    <script>
        var {{ $element->getName() }}Suggestions = [
            @foreach ($element->getChoices() as $choice)
                {value:'{{ $choice->name }}', data:'{{ $choice->id }}'}{{ $loop->last ? '' : ',' }}
            @endforeach
        ];
        $("input[name='{{ $element->getName() }}AutoComplete']").autocomplete({
            lookup: {{ $element->getName() }}Suggestions,
            autoSelectFirst: true,
            onSelect: function(suggestion) {
                $("input[name='{{ $element->getName() }}']").val(suggestion.data);
            }
        });
        $(document).ready(function() {
            $.each({{ $element->getName() }}Suggestions, function() {
                if ("{{ $data->{$element->getName()} }}" == this.data) {
                    $("input[name='{{ $element->getName() }}AutoComplete']").val(this.value);
                    return;
                }
            });
        });
        $("input[name='{{ $element->getName() }}AutoComplete']").blur(function() {
            setTimeout(function() {
                var data = findSuggestionData({{ $element->getName() }}Suggestions, $("input[name='{{ $element->getName() }}AutoComplete']").val());
                if (data) {
                    $("input[name='{{ $element->getName() }}']").val(data);
                    $("input[name='{{ $element->getName() }}']").closest('.form-group').removeClass("has-error");
                } else {
                    $("input[name='{{ $element->getName() }}AutoComplete']").closest(".form-group").addClass('has-error');
                }
            }, 50);
        });
        function findSuggestionData(suggestions, value) {
            var data = false;
            $.each(suggestions, function() {
                if (this.value == value) {
                    data = this.data;
                }
            });
            return data;
        }
    </script>
</div>