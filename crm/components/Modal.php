<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 19.03.2018, 17:10
 */

namespace app\components;

/**
 * Class Modal
 * @package app\components
 */
class Modal extends \yii\bootstrap\Modal
{
    const SIZE_EXTRA_LARGE = 'modal-el';
    const SIZE_SUPER_EXTRA_LARGE = 'modal-sel';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $css = '
        @media (min-width: 1024px) {
            .' . self::SIZE_EXTRA_LARGE . ' {
                width: 1024px; 
            }
        }
        
        @media (min-width: 1280px) {
            .' . self::SIZE_SUPER_EXTRA_LARGE . ' {
                width: 1280px; 
            }
        }
        
        @media (min-width: 1400px) {
            .' . self::SIZE_SUPER_EXTRA_LARGE . ' {
                width: 1400px; 
            }
        }
        ';

        $this->getView()
             ->registerCss($css);
    }
}
