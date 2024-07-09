<div class="form-{{$is_check ? 'check' : 'group'}}">
  @if($is_check)
    <input
      type="{{$type}}"
      class="form-check"
      value="{{$value}}"
      id="{{$input_id}}"/>
  @endif
  @if(isset($label))
    <label>{{$label}}</label>
  @endif
  @if(!$is_check)
    <input
      type="{{$type}}"
      class="form-control{{$type === 'file' ? '-file' : ''}}"
      value="{{$value}}"
      placeholder="{{$placeholder}}"
      id="{{$input_id}}"/>
  @endif
</div>