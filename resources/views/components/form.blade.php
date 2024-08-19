@if($type !== 'select')
  @if(!$buttonLabel)
  <div class="{{$containerClass}}">
  @endif
    @if($isCheck)
      <input
        type="{{$type}}"
        class="{{$inputClass}}"
        value="{{$value}}"
        id="{{$inputId}}"
        name="{{$name}}"
        role="{{$switch ? 'switch' : ''}}"/>
    @endif
    @if(isset($label))
      <label
        class="{{$labelClass}}"
        for="{{$inputId}}">
        {{$label}}
      </label>
    @endif
    @if(!$isCheck)
      <input
        type="{{$type}}"
        class="{{$inputClass}}"
        value="{{$value}}"
        placeholder="{{$placeholder}}"
        id="{{$inputId}}"
        name="{{$name}}"/>
    @endif
  @if(!$buttonLabel)
  </div>
  @endif
@else
  <select class="form-select" name="{{$name}}" id="{{$inputId}}">
    @foreach($options as $key => $value)
    <option value="{{$key}}">
      {{$value}}
    </option>
    @endforeach
  </select>
@endif