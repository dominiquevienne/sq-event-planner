<?php
if ($mode === "show") {
    $readonly = 'disabled';
} else {
    $readonly = '';
}
?>

<div class="form-group col-sm-10 {{$class ?? ''}}" id="field-group-{{$field->id}}" data-condition="{{$field->condition}}">
    <label for="field-{{$field->id}}">{{$field->label}}</label>
    <select class="col-sm-4" {{$readonly}} name="field-{{$field->id}}" id="field-{{$field->id}}" class="custom-select">

        <option>{{$placeholder ?? 'Choose...'}}</option>
        @foreach(preg_split('~;~',$field->options) as $option)
            <?php
            if ($option === $value) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            ?>
            <option {{$selected}} value="{{$option}}">{{$option}}</option>
        @endforeach
    </select>
</div>
