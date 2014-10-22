<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Паспортные данные</title>
    <script src="http://yastatic.net/jquery/2.1.1/jquery.min.js"></script>
    <script src="/js/jquery.maskedinput.min.js"></script>
    <script src="/js/jquery.validate.min.js"></script>
    <script src="/js/jquery.validate.messages_ru.min.js"></script>
    <script src="/js/jquery.liTranslit.js"></script>
    <script src="/js/formrepo.js"></script>
    <script>
        var $form = $('form');
        var repo = new FormRepo('passport');
            
        $(function(){
            $('#phone').mask('+7 (999) 999-99-99', {placeholder:' ', autoclear:false});
            $('#birth_d,#issued_d').mask('9999-99-99', {placeholder:' ', autoclear:false});
            $('#passport_id').mask('9999 999999', {placeholder:' ', autoclear:false});
            $('#issued_code').mask('999-999', {placeholder:' ', autoclear:false});

            jQuery.validator.addMethod('mask', function(value, element, params) {
                return this.optional(element) || params[0].test(value);
            }, jQuery.validator.format('{1}'));


            $('form').validate({
                rules: {
                    last_name:{required:true, rangelength:[1, 50]},
                    first_name:{required:true, rangelength:[1, 50]},
                    middle_name:{required:true, rangelength:[1, 50]},
                    birth_d:{required:true, date:true},
                    phone:{required:true, mask:[/\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}/, 'Введите правильный телефон']},
                    email:{required:true, email:true},
                    passport_id:{required:true, mask:[/\d{4} \d{6}/, 'Введите правильную серию и номер паспорта']},
                    issued_by:{required:true, rangelength:[1, 255]},
                    issued_d:{required:true, date:true},
                    issued_code:{required:true, mask:[/\d{3}-\d{3}/, 'Введите правильный код подразделения']},
                    birth_place:{required:true, rangelength:[1, 255]}
                }
            });

            var map = {
                'q' : 'й', 'w' : 'ц', 'e' : 'у', 'r' : 'к', 't' : 'е', 'y' : 'н', 'u' : 'г', 'i' : 'ш', 'o' : 'щ', 'p' : 'з', '[' : 'х', ']' : 'ъ', 'a' : 'ф', 's' : 'ы', 'd' : 'в', 'f' : 'а', 'g' : 'п', 'h' : 'р', 'j' : 'о', 'k' : 'л', 'l' : 'д', ';' : 'ж', '\'' : 'э', 'z' : 'я', 'x' : 'ч', 'c' : 'с', 'v' : 'м', 'b' : 'и', 'n' : 'т', 'm' : 'ь', ',' : 'б', '.' : 'ю','Q' : 'Й', 'W' : 'Ц', 'E' : 'У', 'R' : 'К', 'T' : 'Е', 'Y' : 'Н', 'U' : 'Г', 'I' : 'Ш', 'O' : 'Щ', 'P' : 'З', '[' : 'Х', ']' : 'Ъ', 'A' : 'Ф', 'S' : 'Ы', 'D' : 'В', 'F' : 'А', 'G' : 'П', 'H' : 'Р', 'J' : 'О', 'K' : 'Л', 'L' : 'Д', ';' : 'Ж', '\'' : 'Э', 'Z' : '?', 'X' : 'ч', 'C' : 'С', 'V' : 'М', 'B' : 'И', 'N' : 'Т', 'M' : 'Ь', ',' : 'Б', '.' : 'Ю',
            };

            $('#first_name,#last_name,#middle_name,#issued_by,#birth_place').each(function(){
                $(this).on('keyup', function(){
                    var str = $(this).val();
                    var r = '';
                    for (var i = 0; i < str.length; i++) {
                        r += map[str.charAt(i)] || str.charAt(i);
                    }
                    $(this).val(r);
                });
            });

            repo.restore($form);

            $form.find('input').on('keyup', function(){
                repo.preserve($form);
            });
        });
    </script>
    <style>
    </style>
</head>
<body>
    <?php if($done): ?>
    <h1>Спасибо за заполнение формы</h1>
    <script>
        repo.remove($form);
    </script>
    <?php else: ?>
    <form action="/" method="post">
        <table cellpadding="3" cellspacing="0">
            <tr>
                <td>Фамилия:</td>
                <td>
                    <input type="text" id="last_name" name="last_name" value="<?=input_value('last_name');?>">
                    <?if(isset($errors['last_name'])):?><label class="error"><?=$errors['last_name'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Имя:</td>
                <td>
                    <input type="text" id="first_name" name="first_name" value="<?=input_value('first_name');?>">
                    <?if(isset($errors['first_name'])):?><label class="error"><?=$errors['first_name'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Отчество:</td>
                <td>
                    <input type="text" id="middle_name" name="middle_name" value="<?=input_value('middle_name');?>">
                    <?if(isset($errors['middle_name'])):?><label class="error"><?=$errors['middle_name'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Дата рождения:</td>
                <td>
                    <input type="text" id="birth_d" name="birth_d" value="<?=input_value('birth_d');?>" placeholder="ГГГГ-ММ-ДД">
                    <?if(isset($errors['birth_d'])):?><label class="error"><?=$errors['birth_d'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Контактный телефон:</td>
                <td>
                    <input type="text" id="phone" name="phone" value="<?=input_value('phone');?>">
                    <?if(isset($errors['phone'])):?><label class="error"><?=$errors['phone'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Электронная почта:</td>
                <td>
                    <input type="text" name="email" value="<?=input_value('email');?>">
                    <?if(isset($errors['email'])):?><label class="error"><?=$errors['email'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Серия и номер паспорта:</td>
                <td>
                    <input type="text" id="passport_id" name="passport_id" value="<?=input_value('passport_id');?>">
                    <?if(isset($errors['passport_id'])):?><label class="error"><?=$errors['passport_id'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Кем выдан:</td>
                <td>
                    <input type="text" id="issued_by" name="issued_by" value="<?=input_value('issued_by');?>">
                    <?if(isset($errors['issued_by'])):?><label class="error"><?=$errors['issued_by'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Дата выдачи:</td>
                <td>
                    <input type="text" id="issued_d" name="issued_d" value="<?=input_value('issued_d');?>" placeholder="ГГГГ-ММ-ДД">
                    <?if(isset($errors['issued_d'])):?><label class="error"><?=$errors['issued_d'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Код подразделения:</td>
                <td>
                    <input type="text" id="issued_code" name="issued_code" value="<?=input_value('issued_code');?>">
                    <?if(isset($errors['issued_code'])):?><label class="error"><?=$errors['issued_code'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td>Место рождения:</td>
                <td>
                    <input type="text" id="birth_place" name="birth_place" value="<?=input_value('birth_place');?>">
                    <?if(isset($errors['birth_place'])):?><label class="error"><?=$errors['birth_place'];?></label><?endif;?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <label>
                        <input type="checkbox" name="confirmed" <?=checkbox_value('confirmed');?>>
                        Я на всё согласен
                    </label>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" value="Подтвердить">
                </td>
            </tr>
        </table>
    </form>
<?php endif; ?>
</body>
</html>