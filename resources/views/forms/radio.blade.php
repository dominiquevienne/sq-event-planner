<div class="form-group col-sm-10" id="field-group-{{$field->id}}" data-condition="{{$field->condition}}">
    <label for="field-{{$field->id}}">{{$field->label}}</label>
    <br>
    @if ($mode === "show")
        <div class="readonly-form-value">{{$value}}</div>
        <input type="hidden" value="{{$value}}" name="field-{{$field->id}}"/>
    @else
        @foreach(preg_split('~;~',$field->options) as $option)
            <div class="form-check form-check-inline">
                <?php
                if ($option === $value) {
                    $checked = "checked";

                } else {
                    $checked = "";
                }
                if ($mode === "show") {
                    $disabled = 'disabled="true"';
                } else {
                    $disabled = '';
                }
                if (!empty($errors)){
                    $invalid = "is-invalid";
                } else {
                    $invalid = "";
                }
                ?>

                <input {{$disabled}} {{$checked}} class="form-check-input {{$invalid}}" type="radio" name="field-{{$field->id}}"
                       id="field-{{$field->id}}-{{$option}}"
                       value="{{$option}}">
                <label class="form-check-label" for="field-{{$field->id}}-{{$option}}">{{$option}}</label>
            </div>
        @endforeach
        @if(!empty($errors))
            <div class="invalid-feedback" style="display:block">
                @foreach ($errors as $error)
                    {{ $error }} <br>
                @endforeach
            </div>
        @endif

        <small class="form-text text-muted">{{$field->help}}</small>
    @endif

</div>




