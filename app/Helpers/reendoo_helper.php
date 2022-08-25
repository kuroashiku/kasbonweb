<?php
    function formControl($label, $id, $height='0%')
    {
        echo '<tr height="'.$height.'">
            <td style="white-space:nowrap;vertical-align:middle"
                id="'.$id.'-label" width="0%">'.$label.'</td>
            <td style="white-space:nowrap" width="100%"><input id="'.$id.'"></td>
        </tr>';
    }

    function formLabel($label, $height=0)
    {
        echo '<tr height="'.$height.'%" style="font-size:14px"><td></td><td>'.$label.'</td></tr>';
    }

    function formControlCombine($label_1, $id_1, $label_2, $id_2)
    {
        echo '<tr height="0%">
        <td style="white-space:nowrap" width="0%" id="'.$id_1.'-label">'.$label_1.'</td>
        <td style="white-space:nowrap" width="100%" >
            <table id="form_inside"><td><input id="'.$id_1.'"></td><td id="'.$id_2.'-label">&nbsp;'.$label_2.'&nbsp;</td><td><input id="'.$id_2.'"></td></table>
        </td>
    </tr>';
    }

    function newFormLabel($label, $id, $height=0)
    {
        echo '<tr height="'.$height.'%" style="font-size:14px"><td></td><td id="'.$id.'">'.$label.'</td></tr>';
    }
?>