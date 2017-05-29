$(document).ready(function () {  
// Отправка AJAX запроса
   $('.form-ask form').submit(function(e) {      // Обработка отправки данных формы
      e.preventDefault();                  // Сброс стандартного обработчика формы
      formData = $(this).serialize() + "&web_form_submit=Отправить";   // Сохраняем массив введенных данных включая значение кнопки "Отправить", без этого компонент Битрикса не примет данные
      
      $.post(      // Отправим POST запрос серверу
         $(this).attr('action') + '?AJAX_REQUEST=Y',   // Текущая страница с дописанным параметром - по нему подключается пустой шаблон с одним #WORK_AREA#
         formData,
         function(response){
            var message = $("#show-message-lightbox").html(response);         // Сохраняем загруженные данные на странице в невидимом блоке

            if(message.find(".fancybox-mes").length) {                        // Если в этих данных есть элементы для показа (ошибка или сообщение)
               var fancy_html = $(message.find(".fancybox-mes + div")).html();
               $.fancybox({content: fancy_html});
               captcha_update();         // Сбросим капчу в форме
            }

            if(message.find(".form-note").length) {   // Если данные формы приняты сбросим все поля
               $('.form-ask input').val(" ");
               $('.form-ask textarea').val(" ");
            }
         }
      );

      return false;
   });

// Обновить капчу
   function captcha_update() {
      $.ajax({
         url: "/bitrix/tools/captcha_ajax.php",
         success: function(data){
            $(".form-ask form").find('.antispam input[name="captcha_sid"]').val(data);
            $(".form-ask form").find('.antispam input[name="captcha_sid"] + img').attr("src", "/bitrix/tools/captcha.php?captcha_sid="+data);
         }
      });
   }

// Настройки всплывающего окна
   $(".fancybox-mes").fancybox({
      "type": "iframe",
      "padding": 10,
      "imageScale": false,
      "zoomOpacity": false,
      "zoomSpeedIn": 1000,
      "zoomSpeedOut": 1000,
      "width": 525,
      "height": 300,
      "overlayShow": true,
      "overlayOpacity": 0.8,
      "hideOnContentClick": false,
      "centerOnScroll": true
   });

});