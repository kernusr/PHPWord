<?php

namespace PhpOffice\PhpWord\Writer\HTML\Style;

class Cell extends AbstractStyle
{

    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Cell) {
            return '';
        }
        $css = [];
        $css = array_merge($css, $this->getBorderStyles());
        return $this->assembleCss($css);
    }
}