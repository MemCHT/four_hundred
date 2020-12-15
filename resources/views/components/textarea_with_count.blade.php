<?php
    $attributes_str = "";
    foreach($textarea_attributes as $key => $value)
        if($key != 'value')
            $attributes_str .= $key.'="'.$value.'" ';
?>

<div class="textarea_with_count">
    <textarea {!! $attributes_str !!}>{{ $textarea_attributes['value'] }}</textarea>
    <div class="mt-3 d-flex">
        <p id="{{ 'error_'.$textarea_attributes['id'] }}" class="text-danger flex-grow-1 text-left" hidden>最大文字数を超えています</p>
        <p id="{{ 'count_'.$textarea_attributes['id'] }}" class="flex-grow-1 text-right">〇〇 / {{ $max_count }}文字</p>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const textarea = document.getElementById('{{ $textarea_attributes["id"] }}');

        const [handleChangeTextarea] = useFunctions();

        textarea.addEventListener('keyup', handleChangeTextarea);
    });

    const useFunctions = () => {
        const textareaElm = document.getElementById('{{ $textarea_attributes["id"] }}');
        const countTextareaElm = document.getElementById('{{ "count_".$textarea_attributes["id"] }}');
        const errorTextareaElm = document.getElementById('{{ "error_".$textarea_attributes["id"] }}');
        let countTextarea = textareaElm.value.length;

        // 最大文字数を超えていないか判定して、クラスを付与する。
        const determineMaxCountAndAssignClass = () => {

            if(countTextarea > {{ $max_count }} ){
                textareaElm.classList.add('is-invalid');
                countTextareaElm.classList.add('text-danger');
                errorTextareaElm.hidden = false;
            }else{
                textareaElm.classList.remove('is-invalid');
                countTextareaElm.classList.remove('text-danger');
                errorTextareaElm.hidden = true;
            }
        };

        determineMaxCountAndAssignClass();

        countTextareaElm.innerHTML = countTextarea + ' / {{ $max_count }}文字';

        const handleChangeTextarea = (event) => {
            countTextarea = textareaElm.value.length;

            countTextareaElm.innerHTML = countTextarea + ' / {{ $max_count }}文字';
            determineMaxCountAndAssignClass();
        };

        return [handleChangeTextarea];
    };
</script>
