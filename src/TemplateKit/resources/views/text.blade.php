<div class="form-group{{ $element->getRequired() ? ' required' : '' }}{{ isset($errors[$element->getName()]) ? ' has-error' : '' }}">
    <label for="{{ $element->getName() }}">{{ $element->getLabel() }}</label>
    <input type="text" class="form-control" name="{{ $element->getName() }}" value="{{ $data->{$element->getName()} }}" maxlength="{{ $element->getMaxLength() }}" placeholder="{{ $element->getPlaceholder() }}"{{ $element->getRequired() ? ' required' : '' }} />
</div>