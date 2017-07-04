<div class="form-group{{ $element->getRequired() ? ' required' : '' }}{{ isset($errors[$element->getName()]) ? ' has-error' : '' }}">
    <label for="{{ $element->getName() }}">{{ $element->getLabel() }}</label>
    <textarea class="form-control" name="{{ $element->getName() }}" maxlength="{{ $element->getMaxLength() }}" rows="{{ $element->getNumRows() }}" placeholder="{{ $element->getPlaceholder() }}"{{ $element->getRequired() ? ' required' : '' }} />{{ $data->{$element->getName()} }}</textarea>
</div>