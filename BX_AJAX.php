<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

//подключаем библиотеки BitrixJS, функцию ajax из ядра
   CJSCore::Init(array('ajax'));
   $sidAjax = 'testAjax';
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
   $GLOBALS['APPLICATION']->RestartBuffer();
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
   die();
}

?>

<!--создаем html блок-->
<div class="group">
   <div id="block"></div >
   <div id="process">wait ... </div >
</div>

<!--создаем обработчик-->
<script>
//включаем режим отладки
   window.BXDEBUG = true;
function DEMOLoad(){
//скрываем эелемент DOM структуры с ID block
   BX.hide(BX("block"));
//показываем эелемент DOM структуры с ID process
   BX.show(BX("process"));
//делаем ajax запрос и в случае успеха вызываем функцию DEMOResponse
   BX.ajax.loadJSON(
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
      DEMOResponse
   );
}

//создаем функцию DEMOResponse с параметром data для обработки ajax ответа
function DEMOResponse (data){
//включаем логирование
   BX.debug('AJAX-DEMOResponse ', data);
//обновляем html содержимое элемента с ID block значением из data.RESULT
   BX("block").innerHTML = data.RESULT;
//оказываем эелемент DOM структуры с ID block
   BX.show(BX("block"));
//скрываем эелемент DOM структуры с ID process
   BX.hide(BX("process"));

//создаем пользовательское событие 'DEMOUpdate'
   BX.onCustomEvent(
      BX(BX("block")),
      'DEMOUpdate'
   );
}

//создаем обработчик на событие когда DOM готова
BX.ready(function(){
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href;
   });
   */
   BX.hide(BX("block"));
   BX.hide(BX("process"));
   
   //создаем обработчик по событию click на элементах класса css_ajax, который обновит html при возниконвении события
    BX.bindDelegate(
      document.body, 'click', {className: 'css_ajax' },
      function(e){
         if(!e)
            e = window.event;
         
         DEMOLoad();
         return BX.PreventDefault(e);
      }
   );
   
});

</script>
<div class="css_ajax">click Me</div>
<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
