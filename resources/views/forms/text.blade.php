<div class="form-group col-sm-10 {{$class ?? ''}}" id="field-group-{{$field->id}}" data-condition="{{$field->condition}}">
    <label for="field-{{$field->id}}">{{$field->label}}</label>
    @if ($mode === "show")
        <div class="readonly-form-value">{{$value}}</div>
        <input type="hidden" value="{{$value}}" name="field-{{$field->id}}"/>
    @else
        <?php
        if ($mode === "show") {
            $readonly = 'readonly';
        } else {
            $readonly = '';
        }
        if (!empty($errors)){
            $invalid = "is-invalid";
        } else {
            $invalid = "";
        }
        ?>
        <input {{$readonly}} type="text" value="{{$value}}" name="field-{{$field->id}}" class="col-sm-4 form-control {{$invalid}}" id="field-{{$field->id}}"
               placeholder="{{$field->placeholder}}">
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




