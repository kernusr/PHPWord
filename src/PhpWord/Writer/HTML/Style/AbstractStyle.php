<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Style;

use PhpOffice\PhpWord\SimpleType\Border;
use PhpOffice\PhpWord\Style\AbstractStyle as Style;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Table;

/**
 * Style writer.
 *
 * @since 0.10.0
 */
abstract class AbstractStyle
{
    /**
     * Parent writer.
     *
     * @var \PhpOffice\PhpWord\Writer\AbstractWriter
     */
    private $parentWriter;

    /**
     * Style.
     *
     * @var array|\PhpOffice\PhpWord\Style\AbstractStyle
     */
    private $style;

    /**
     * Write style.
     */
    abstract public function write();

    /**
     * Create new instance.
     *
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle $style
     */
    public function __construct($style = null)
    {
        $this->style = $style;
    }

    /**
     * Set parent writer.
     *
     * @param \PhpOffice\PhpWord\Writer\AbstractWriter $writer
     */
    public function setParentWriter($writer): void
    {
        $this->parentWriter = $writer;
    }

    /**
     * Get parent writer.
     *
     * @return \PhpOffice\PhpWord\Writer\AbstractWriter
     */
    public function getParentWriter()
    {
        return $this->parentWriter;
    }

    /**
     * Get style.
     *
     * @return array|\PhpOffice\PhpWord\Style\AbstractStyle $style
     */
    public function getStyle()
    {
        if (!$this->style instanceof Style && !is_array($this->style)) {
            return '';
        }

        return $this->style;
    }

    /**
     * Takes array where of CSS properties / values and converts to CSS string.
     *
     * @param array $css
     *
     * @return string
     */
    protected function assembleCss($css)
    {
        $pairs = [];
        $string = '';
        foreach ($css as $key => $value) {
            if ($value != '') {
                $pairs[] = $key . ': ' . $value;
            }
        }
        if (!empty($pairs)) {
            $string = implode('; ', $pairs) . ';';
        }

        return $string;
    }

    /**
     * Get value if ...
     *
     * @param null|bool $condition
     * @param string $value
     *
     * @return string
     */
    protected function getValueIf($condition, $value)
    {
        return $condition == true ? $value : '';
    }

    /**
     * Returns the CSS border values for Cell and Table elements
     *
     * @return array
     */
    protected function getBorderStyles()
    {
        $style = $this->getStyle();
        if (!$style instanceof Cell
            && !$style instanceof Table) {
            return [];
        }
        $css = [];
        $borders = ['top', 'left', 'bottom', 'right'];
        foreach ($borders as $side) {
            $ucfSide = ucfirst($side);
            $borderWidth = call_user_func([$style, "getBorder{$ucfSide}Size"]);
            if ($borderWidth !== null) {
                $borderWidth = (int)$borderWidth / 8;
                if ($borderWidth < 0.25) {
                    $borderWidth = 0;
                }
            }
            $borderStyle = call_user_func([$style, "getBorder{$ucfSide}Style"]);
            if ($borderStyle !== null) {
                $borderStyle = $this->getBorderStyleCSSValue($borderStyle);
            }
            $borderColor = call_user_func([$style, "getBorder{$ucfSide}Color"]);
            $css["border-{$side}-width"] = $this->getValueIf($borderWidth !== null, "{$borderWidth}pt");
            $css["border-{$side}-style"] = $borderStyle;
            $css["border-{$side}-color"] = $this->getValueIf($borderColor !== null, "#{$borderColor}");
        }
        return $css;
    }

    /**
     * Returns the corresponding CSS border style values
     *
     * @param string $xmlValue
     * @return string
     */
    protected function getBorderStyleCSSValue(string $xmlValue)
    {
        switch ($xmlValue) {
            case BORDER::DASH_DOT_STROKED:
            case BORDER::DASHED:
            case BORDER::DASH_SMALL_GAP:
                $cssValue = 'dashed';
                break;
            case BORDER::INSET:
                $cssValue = 'inset';
                break;
            case BORDER::NIL:
                $cssValue = 'hidden';
                break;
            case BORDER::NONE:
                $cssValue = 'none';
                break;
            case BORDER::OUTSET:
                $cssValue = 'outset';
                break;
            case BORDER::DOT_DASH:
            case BORDER::DOT_DOT_DASH:
            case BORDER::DOTTED:
                $cssValue = 'dotted';
                break;
            case BORDER::DOUBLE:
            case BORDER::DOUBLE_WAVE:
            case BORDER::TRIPLE:
                $cssValue = 'double';
                break;
            case BORDER::THREE_D_EMBOSS:
                $cssValue = 'ridge';
                break;
            case BORDER::THREE_D_ENGRAVE:
                $cssValue = 'groove';
                break;
            default:
                $cssValue = 'solid';
        }
        return $cssValue;
    }
}
