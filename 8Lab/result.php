<?php
header("Content-Type: text/html; charset=utf-8");
mb_internal_encoding('UTF-8');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>
        Буртелов Вадим Михайлович, 191-321. Лабораторная работа №А-8.
        Основы работы со строковыми данными в РНР. Кодировка. Анализ текста.
    </title>
    <link rel="stylesheet" href="style.css">
</head>

<div id="container">
    <div id="header">
        <div class="logo">
            <img src="imgs/img.png" alt="logo" width="300" height="100">
        </div>
        <div class="main-menu">
            <p>Буртелов Вадим Михайлович, 191-321. <br> Лабораторная работа №А-8. </p>
        </div>
    </div>


    <div id="body">
        <?php
        if (isset($_POST['data']) && $_POST['data']) {             // если передан текст для анализа
            echo '<h1>Исходный текст:</h1><div class="src_text">' . $_POST['data'] . '</div><br>';      // выводим текст
            test_it(iconv("utf-8", "cp1251", $_POST['data']));                         // анализируем
        } else                                                         // если текста нет или он пустой
            echo '<div class="src_error">Нет текста для анализа</div>'; // выводим ошибку


        function test_it($text)
        {
            $cifra = array('0' => true, '1' => true, '2' => true,
                '3' => true, '4' => true, '5' => true, '6' => true,
                '7' => true, '8' => true, '9' => true);

            $punctuation = array('.' => true, ',' => true, '!' => true, '?' => true, ':' => true, '-' => true, ';' => true);

            $rus_alphavit = array('А' => true, 'Б' => true, 'В' => true, 'Г' => true, 'Д' => true, 'Е' => true, 'Ё' => true, 'Ж' => true, 'З' => true, 'И' => true, 'Й' => true, 'К' => true, 'Л' => true, 'М' => true, 'Н' => true, 'О' => true, 'П' => true, 'Р' => true, 'С' => true, 'Т' => true, 'У' => true, 'Ф' => true, 'Х' => true, 'Ц' => true, 'Ч' => true, 'Щ' => true, 'Ш' => true, 'Ъ' => true, 'Ы' => true, 'Ь' => true, 'Э' => true, 'Ю' => true, 'Я' => true);
            $cifra_amount = 0;
            $punctuation_amount = 0;
            $word = '';
            $words = array();
            $letters_up_amount = 0;
            $letters_down_amount = 0;
            $symb_amount = mb_strlen(iconv("cp1251", "utf-8", $text), 'utf-8');
            $letters_amount = 0;
            $p = 0;
            $abc = '';

            for ($i = 192; $i < 256; $i++) {
                $abc .= chr($i);
                if ($i < 224) {
                    $uppercase[$i - 192] = iconv('cp1251', 'utf-8', chr($i));
                } else $lowercase[$i - 224] = iconv('cp1251', 'utf-8', chr($i));
            }

            for ($i = 65; $i < 91; $i++) {
                $abc .= chr($i);
                $uppercase[$i - 33] = iconv('cp1251', 'utf-8', chr($i));
            }
            for ($i = 97; $i < 123; $i++) {
                $abc .= chr($i);
                $lowercase[$i - 65] = iconv('cp1251', 'utf-8', chr($i));
            }


            for ($i = 0; $i < strlen($text); $i++) {
                if (array_key_exists($text[$i], $cifra)) {
                    $cifra_amount++;
                }
                if (array_key_exists($text[$i], $punctuation)) {
                    $punctuation_amount++;
                }

                if ($text[$i] == ' ' || $text[$i] == '.' || $text[$i] == ',' || $text[$i] == ':' || $text[$i] == ';' || $text[$i] == '-' || $text[$i] == '!' || $text[$i] == '?' || $text[$i] == '1' || $text[$i] == '2' || $text[$i] == '3' ||
                    $text[$i] == '4' || $text[$i] == '5' || $text[$i] == '6' || $text[$i] == '7' || $text[$i] == '8' || $text[$i] == '9' || $text[$i] == '0' || $i == strlen($text) - 1) {
                    if ($i == (strlen($text) - 1) && $text[$i] != '.' && $text[$i] != ',' && $text[$i] != '!' && $text[$i] != '?' && $text[$i] != '-' && $text[$i] != ';' && $text[$i] != ':' && $text[$i] != ' ' && !(array_key_exists($text[$i], $cifra))) {
                        $word .= $text[$i];
                        $letters_amount++;
                        if (array_key_exists(iconv("cp1251", "utf-8", $text[$i]), $rus_alphavit) || ctype_upper($text[$i]))
                            $letters_up_amount++;
                        else
                            $letters_down_amount++;
                    }
                    if ($word) {
                        if (isset($words[$word]))
                            $words[$word]++;
                        else
                            $words[$word] = 1;
                        $word = '';
                    }

                } else {
                    $word .= $text[$i];
                    $letters_amount++;
                    if (array_key_exists(iconv("cp1251", "utf-8", $text[$i]), $rus_alphavit) || ctype_upper($text[$i]))
                        $letters_up_amount++;
                    else
                        $letters_down_amount++;
                }
            }


            echo("<table><tbody>");
            echo '<tr><td>Количество символов </td><td>' . $symb_amount . '</td></tr>';
            echo '<tr><td>Количество букв </td><td>' . $letters_amount . ' </td></tr>';
            echo '<tr><td>Количество строчных букв </td><td>' . $letters_down_amount . '</td></tr>';
            echo '<tr><td>Количество заглавных букв </td><td>' . $letters_up_amount . '</td></tr>';
            echo '<tr><td>Количество знаков препинания </td><td>' . $punctuation_amount . '</td></tr>';
            echo '<tr><td>Количество цифр </td><td>' . $cifra_amount . '</td></tr>';
            echo '<tr><td>Количество слов</td><td> ' . count($words) . '</td><tr>';
            echo '<tr><th colspan="2" >Количество вхождений каждого символа в текст : </th></tr><tr><td><strong>Символ</strong></td><td><strong>Количество</strong></td></tr>';


            $l_text = strtolower($text);


            for ($i = 0; $i < strlen($l_text); $i++) {

                for ($j = 0; $j < $i; $j++) {
                    if ($l_text[$i] == $l_text[$j])
                        $p++;
                }
                if ($p == 0) {
                    if ($l_text[$i] == " ")
                        echo('<tr>
                  <td>Пробел</td>
                  <td>' . substr_count($l_text, $l_text[$i]) . '</td>
                </tr>');
                    else
                        echo('<tr>
                  <td>' . iconv('cp1251', 'utf-8', $l_text[$i]) . '</td>
                  <td>' . substr_count($l_text, $l_text[$i]) . '</td>
                </tr>');
                }
                $p = 0;
            }

            echo '<tr><th colspan="2" >Список слов в тексте : </th></tr><tr><td><strong>Слово</strong></td><td><strong>Количество</strong></td></tr>';

            ksort($words);
            if (count($words) == 0) {
                echo '<tr><th colspan="2"><u>Слова в тексте отсутствуют</u></th></tr>';
            }
            foreach ($words as $key => $value) {
                echo '<tr><td>' . iconv("cp1251", "utf-8", $key) .
                    '</td><td>' . iconv("cp1251", "utf-8", $value) .
                    '</td></tr>';
            }
            echo '</table>';
            echo('</tbody></table>');
            echo("<hr>");


        }

        function count_symbs($text)
        {
            $symbs = array();
            $lowerText = strtolower($text);
            for ($i = 0; $i < strlen($lowerText); $i++) {
                if ($text[$i] == "\r")
                    continue;
                if ($text[$i] == "\n")
                    continue;
                if (isset($symbs[$lowerText[$i]]))
                    $symbs[$lowerText[$i]]++;
                else
                    $symbs[$lowerText[$i]] = 1;
            }
            return $symbs;
        }

        ?>
    </div>
    <div id="footer">
        <div id="footer-text"><p> Лабораторная работа №А-8. </p></div>
    </div>
</div>

</html>