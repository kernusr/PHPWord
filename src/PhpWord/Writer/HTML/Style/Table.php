<?php

namespace PhpOffice\PhpWord\Writer\HTML\Style;

class Table extends AbstractStyle
{

    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Table) {
            return '';
        }
        $css = [];
        if ($style->getLayout() == \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED) {
            $css['table-layout'] = 'fixed';
        } elseif ($style->getLayout() == \PhpOffice\PhpWord\Style\Table::LAYOUT_AUTO) {
            $css['table-layout'] = 'auto';
        }
        $css = array_merge($css, $this->getBorderStyles());
        return $this->assembleCss($css);
    }
}