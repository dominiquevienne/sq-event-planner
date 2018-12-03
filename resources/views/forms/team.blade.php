<?php
if ($mode === "show") {
    $readonly = 'disabled';
} else {
    $readonly = '';
}

if (!empty($errors)) {
    $invalid = "is-invalid";
} else {
    $invalid = "";
}

$teamSizes = $event->teams($field);
?>

<div class="form-group col-sm-10 {{$class ?? ''}}" id="field-group-{{$field->id}}" data-condition="{{$field->condition}}">
    <label for="field-{{$field->id}}">{{$field->label}}</label>
    <select {{$readonly}} name="field-{{$field->id}}" id="field-{{$field->id}}" class="custom-select {{$invalid}}">

        <option value="" disabled selected>{{$placeholder ?? 'Choose...'}}</option>
        @foreach(preg_split('~;~',$field->options) as $team)
            <?php
            $teamName = preg_split('~:~', $team)[0];
            $teamCapacity = preg_split('~:~', $team)[1];
            if ($teamName === $value) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            ?>
            <option {{$selected}} value="{{$teamName}}">{{$teamName}} ({{$teamSizes[$teamName] ?? '0'}}/{{$teamCapacity}})</option>
        @endforeach
    </select>
    @if(!empty($errors))
        <div class="invalid-feedback" style="display:block">
            @foreach ($errors as $error)
                {{ $error }} <br>
            @endforeach
        </div>
    @endif
</div>
