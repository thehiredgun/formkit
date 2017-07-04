<div class="form-group{{ $element->getRequired() ? ' required' : '' }}{{ isset($errors[$element->getName()]) ? ' has-error' : '' }}">
    <label class="blockLabel" for="{{ $element->getName() }}">{{ $element->getLabel() }}</label>
    <div class="btn-group-vertical" data-toggle="buttons">
        @foreach ($element->getChoices() as $choice)
            <label class="btn btn-default formButton textLeft{{ $data->{$element->getName()} == $choice->id ? ' active' : '' }}">
                <input type="radio" name="{{ $element->getName() }}" value="{{ $choice->id }}"{{ $data->{$element->getName()} == $choice->id ? ' checked' : '' }} /><span class="radioIcon"></span> {{ $choice->name }}
            </label>
        @endforeach
    </div>
</div>