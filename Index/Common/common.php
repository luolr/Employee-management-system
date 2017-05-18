<?php

function p($array)
{
    dump($array, 1, '<pre>', 0);
}

function replace_phiz($content)
{

    preg_match_all('/\[em\_[0-9]{1,2}\]/', $content, $arr);

    if ($arr[0]) {

        foreach ($arr[0] as $v) {

            for ($i = 1; $i < 76; $i++) {
                if ($v == '[em_' . $i . ']') {

                    $content = str_replace($v, '<img src="' . __ROOT__ . '/Public/images/face/' . $i . '.gif" />', $content);

                }
            }

        }


    }

    return $content;

}

;
?>