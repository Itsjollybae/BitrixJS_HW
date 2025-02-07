<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");
// Подключение jQuery для работы с AJAX-запросами
   CJSCore::Init(array('ajax'));
   // Идентификатор формы для AJAX-запросов
   $sidAjax = 'testAjax';
   // Проверяем наличие AJAX-запроса от формы с указанным идентификатором
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
   $GLOBALS['APPLICATION']->RestartBuffer();
   // Формируем ответ в JSON-формате
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
   // И завершаем выполнение скрипта после отправки ответа
   die();
}

?>
<!-- HTML-разметка для отображения результатов -->
<div class="group">
    <!-- Блок для вывода результата -->
   <div id="block"></div >
   <!-- Индикатор процесса загрузки -->
   <div id="process">wait ... </div >
</div>

<script>
   window.BXDEBUG = true;
   // Функция для выполнения AJAX-запроса
function DEMOLoad(){
    // Скрываем блок для вывода результата
   BX.hide(BX("block"));
   // Показываем индикатор процесса загрузки
   BX.show(BX("process"));
   // Выполняем AJAX-запрос к текущей странице с параметром ajax_form
   BX.ajax.loadJSON(
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
      DEMOResponse
   );
}
// Обработчик ответа на AJAX-запрос
function DEMOResponse(data) {
   // Выводим отладочную информацию о полученном ответе
   BX.debug('AJAX-DEMOResponse ', data);

   // Заполняем блок результатом выполнения запроса
   BX("block").innerHTML = data.RESULT;
   // Показываем блок с результатом
   BX.show(BX("block"));
   // Скрываем индикатор процесса загрузки
   BX.hide(BX("process"));

   // Вызываем пользовательское событие 'DEMOUpdate'
   BX.onCustomEvent(
      BX(BX("block")),
      'DEMOUpdate'
   );
}

// Инициализация при загрузке страницы
BX.ready(function () {
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href;
   });
   */

   // Скрытие блоков до начала работы
   BX.hide(BX("block"));
   BX.hide(BX("process"));

   // Привязка обработчика клика к элементам с классом css_ajax
   BX.bindDelegate(
      document.body,
      'click',
      { className: 'css_ajax' },
      function (e) {
         // Если объект события отсутствует, используем глобальный объект event
         if (!e) e = window.event;

         // Вызов функции для выполнения AJAX-запроса
         DEMOLoad();

         // Отмена стандартного поведения браузера (например, переход по ссылке)
         return BX.PreventDefault(e);
      }
   );
});
</script>

<!-- Кнопка для вызова AJAX-запроса -->
<div class="css_ajax">click Me</div>

<?
// Подключение эпилога ядра Bitrix
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>
