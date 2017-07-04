<div class="form-group{{ $element->getRequired() ? ' required' : '' }}{{ isset($errors[$element->getName()]) ? ' has-error' : '' }}">
    <label for="{{ $element->getName() }}">{{ $element->getLabel() }}</label>
    <input type="text" class="form-control" id="{{ $element->getName() }}" name="{{ $element->getName() }}" value="{{ $data->{$element->getName()} }}" placeholder="{{ $element->getPlaceholder() }}"{{ $element->getRequired() ? ' required' : '' }} style="max-width: 10em;" />
    <script>
        $('#{{ $element->getName() }}').datetimepicker({
            format: "yyyy-mm-dd",
            minView: 2,
            startView: 2,
            autoclose: true
        });
    </script>
</div>