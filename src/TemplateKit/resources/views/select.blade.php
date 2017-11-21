<div class="form-group{{ $element->getRequired() ? ' required' : '' }}{{ isset($errors[$element->getName()]) ? ' has-error' : '' }}">
    <label for="{{ $element->getName() }}">{{ $element->getLabel() }}</label>
    <select class="form-control" name="{{ $element->getName() }}"{{ $element->getRequired() ? ' required' : '' }}>
        <option></option>
        @foreach ($element->getChoices() as $choice)
            <option value="{{ $choice->id }}"{{ $data->{$element->getName()} == $choice->id ? ' selected' : '' }}>{{ $choice->name }}</option>
        @endforeach
    </select>
</div>
