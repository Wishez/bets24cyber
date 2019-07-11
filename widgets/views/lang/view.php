<?php
use yii\helpers\Html;

?>




<div class="relative siteMetaLanguages font-family_main parent centered">
        <base-button :action="switchLanguageState" id="openOrCloseLanguagesButton" label="Показать открыть выбор языка" unstyled="">
            <?= strtoupper($current->url);?>
            <chevron-icon :position="isLanguagesShown ? 'up' : 'down'" aria-labeledby="openOrCloseLanguagesButton">
            </chevron-icon>
        </base-button>
        <transition appear="" name="fadeTranslateToBottom">
            <ul :aria-expanded="isLanguagesShown" aria-label="Навигация по страницам с разным переводом" class="absolute siteMetaLanguages__list font-size_18 popupList popupList_centered" role="navigation" v-if="isLanguagesShown">
            	<?php foreach ($langs as $lang):?>
                <li >
                    <a   data-pjax="0" href="<?='http://'.Yii::$app->request->serverName.($lang->default!=1  ? '/'.$lang->url : '').Yii::$app->getRequest()->getLangUrl()?>">
                        <?=strtoupper($lang->url)?>
                    </a>
                </li>
            <?php endforeach?>
            </ul>
        </transition>
    </div>